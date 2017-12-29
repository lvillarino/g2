<?php

/*
 * Basic WP Security
 * 
 * Thanks to the plugin === BBQ: Block Bad Queries ===
 * https://wordpress.org/plugins/block-bad-queries/
 */

class G2_General {

    public static function define_public_hooks() {
        $config = get_option('g2Security');

        if ($config['gg_login_hint'] == 1) {
            add_filter('login_errors', array('G2_General', 'disable_login_hint'));
        }

        if ($config['gg_rpc'] == 1) {
            add_filter('xmlrpc_enabled', '__return_false');
        }
        if ($config['gg_disable_file_editor'] == 1) {
            define('DISALLOW_FILE_EDIT', TRUE);
        }
        if ($config['gg_remove_header'] == 1) {
            remove_action('wp_head', 'rsd_link');
            remove_action('wp_head', 'wlwmanifest_link');
            remove_action('wp_head', 'wp_generator');
        }
        if ($config['gg_block_query'] == 1) {
            add_action('plugins_loaded', array('G2_General', 'check_bad_query'));
        }
        if ($config['gg_disable_pingback']) {
            add_filter('xmlrpc_methods', array('G2_General', 'block_xmlrpc_attacks'));
            add_filter('wp_headers', array('G2_General', 'remove_x_pingback_header'));
        }
        if ($config['gg_remove_me'] == 1) {
            add_action('login_head', array('G2_General', 'remove_remember_me'));
        }
        if ($config['gg_disable_wp_updates'] == 1) {
            add_filter('pre_site_transient_update_core', array('G2_General', 'remove_core_updates'));
            add_filter('pre_site_transient_update_plugins', array('G2_General', 'remove_core_updates'));
            add_filter('pre_site_transient_update_themes', array('G2_General', 'remove_core_updates'));
        }
      
    }

    public function disable_login_hint() {
        $config = get_option('g2Security');
        if ($config['gg_login_error_message'] == '') {
            return __("The email and password you entered don't match.", 'ldomain');
        } else {
            return $config['gg_login_error_message'];
        }
    }

    public function check_bad_query() {

        if (!current_user_can('administrator')) {
            $request_uri_array = apply_filters('request_uri_items', array('eval\(', 'UNION(.*)SELECT', '\(null\)', 'base64_', '\/localhost', '\%2Flocalhost', '\/pingserver', '\/config\.', '\/wwwroot', '\/makefile', 'crossdomain\.', 'proc\/self\/environ', 'etc\/passwd', '\/https\:', '\/http\:', '\/ftp\:', '\/cgi\/', '\.cgi', '\.exe', '\.sql', '\.ini', '\.dll', '\.asp', '\.jsp', '\/\.bash', '\/\.git', '\/\.svn', '\/\.tar', ' ', '\<', '\>', '\/\=', '\.\.\.', '\+\+\+', '\/&&', '\/Nt\.', '\;Nt\.', '\=Nt\.', '\,Nt\.', '\.exec\(', '\)\.html\(', '\{x\.html\(', '\(function\(', '\.php\([0-9]+\)', '(benchmark|sleep)(\s|%20)*\('));
            $query_string_array = apply_filters('query_string_items', array('\.\.\/', '127\.0\.0\.1', 'localhost', 'loopback', '\%0A', '\%0D', '\%00', '\%2e\%2e', 'input_file', 'execute', 'mosconfig', 'path\=\.', 'mod\=\.', 'wp-config\.php'));
            $user_agent_array = apply_filters('user_agent_items', array('acapbot', 'binlar', 'casper', 'cmswor', 'diavol', 'dotbot', 'finder', 'flicky', 'morfeus', 'nutch', 'planet', 'purebot', 'pycurl', 'semalt', 'skygrid', 'snoopy', 'sucker', 'turnit', 'vikspi', 'zmeu'));

            $request_uri_string = false;
            $query_string_string = false;
            $user_agent_string = false;

            if (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']))
                $request_uri_string = $_SERVER['REQUEST_URI'];
            if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']))
                $query_string_string = $_SERVER['QUERY_STRING'];
            if (isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT']))
                $user_agent_string = $_SERVER['HTTP_USER_AGENT'];

            if ($request_uri_string || $query_string_string || $user_agent_string) {
                if (strlen($_SERVER['REQUEST_URI']) > 255 ||
                        preg_match('/' . implode('|', $request_uri_array) . '/i', $request_uri_string) ||
                        preg_match('/' . implode('|', $query_string_array) . '/i', $query_string_string) ||
                        preg_match('/' . implode('|', $user_agent_array) . '/i', $user_agent_string)
                ) {
                    $this->generate_bad_response_403();
                }
            }
        }
    }

    public function generate_bad_response_403() {
        header('HTTP/1.1 403 Forbidden');
        header('Status: 403 Forbidden');
        header('Connection: Close');
        exit;
    }

    public function block_xmlrpc_attacks($methods) {
        unset($methods['pingback.ping']);
        unset($methods['pingback.extensions.getPingbacks']);
        return $methods;
    }

    public function remove_x_pingback_header($headers) {
        unset($headers['X-Pingback']);
        return $headers;
    }

    public function remove_remember_me() {
        echo '<style type="text/css">.forgetmenot { display:none; }</style>' . "\n";
    }

    public function remove_core_updates() {
        global $wp_version;
        return(object) array('last_checked' => time(), 'version_checked' => $wp_version,);
    }

}
