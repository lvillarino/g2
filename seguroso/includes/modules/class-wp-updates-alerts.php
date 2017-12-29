<?php
/*
 * Detect if any plugin needs an update and send an email
 * 
 */

class G2_WP_Updates_Alerts {
    static public $id = 'g2-security-wua';

    public function __construct() {
        $nextRun = get_option('g2_wp_updates_alerts_check_next');
        if (time() >= $nextRun) {
            update_option('g2_wp_updates_alerts_check_next', time() + ( 12 * 60 * 60 ) + rand(60,600));
            add_action('init', array($this, 'wp_update_check'), 20);
        } 
        //update_option('g2_wp_updates_alerts_check_next', time() - ( 12 * 60 * 60 ));
    }
    
    public function wp_update_check() {
        $config = get_option('g2Security');
        if (empty($config['email'])) return;
        
        //Get List of Plugins/Theme to update
        $updateList = getUpdates();
        update_option('g2_wp_updates_alerts_check', time());
        
        if (sizeof($updateList) > 0){
            $config = get_option('g2Security');
            if (!empty($config['email'])) {
                $plugin_url = get_admin_url() . 'plugins.php?plugin_status=upgrade';

                $message = G2_Email::get_header()
                        . 'We have detected one or more of your plugins/theme need an update.';
                
                if (!$updates['core_latest']){
                    $message .= "Wordpress Need an Update" . "\n\n";
                }
                
                if (sizeof($updateList['plugins']) > 0){
                    $message .= "Plugins" . "\n";
                    $message .= implode("\n - ", $updateList['plugins']) . "\n\n";
                }        
                     
                if (sizeof($updateList['themes']) > 0){
                    $message .= "Themes" . "\n";
                    $message .= implode("\n - ", $updateList['themes']) . "\n\n";
                }
                
                $message .= 'Please visit this url to update your wordpress site: ' .$plugin_url
                        . G2_Email::get_footer();

                $email = trim($config['email']);
                wp_mail($email, G2_Email::get_subject('alert') . ' - Available Updates', $message);
            }
        }
    }
    
    public function getUpdates() {
	$plugins = get_plugin_updates();
	foreach ( $plugins as $plugin ) {
		$updates['plugins'][] = $plugin->Name;
	}
	//
	$themes = get_theme_updates();
	foreach ( $themes as $theme ) {
		$updates['themes'][] = $theme->update['theme'];
	}
	$update = get_core_updates();
	if ( ! isset( $update->response ) || 'latest' == $update->response ){
		$updates['core_latest'] = TRUE; // if latest
        }else{
		$updates['core_latest'] = FALSE; // if
        }
        
	return $updates;
    }


    /**
     * Removes the update nag for non admin users.
     *
     * @return void
     */
    public static function remove_update_nag_for_nonadmins() {
        if ( !current_user_can( 'update_plugins' ) ) { 
                remove_action( 'admin_notices', 'update_nag', 3 );
        }
    }
    
}