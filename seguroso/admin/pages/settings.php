<div class="wrap">
    <h1>G2 Security <?php _e('Settings', 'ldomain'); ?></h1>
    <br>
    <?php

    function ___selected($a, $b, $checkbox = FALSE) {
        if ($a == $b) {
            if ($checkbox) {
                echo ' checked="checked" ';

                return;
            }
            echo ' selected="selected" ';
        }
    }

    $config = get_option('g2Security');
    if ($_POST['save'] == 1) {
        if ($config == $_POST['config']) {
            echo '<div class="updated notice notice-success is-dismissible"><p>' . __('Configuration Saved', 'ldomain') . '</p></div>';
        } else { //
            $_POST['config']['site_root'] = trim(rtrim($_POST['config']['site_root'], '/'));
            if (update_option('g2Security', $_POST['config'])) {
                echo '<div class="updated notice notice-success is-dismissible"><p>' . __('Configuration Saved', 'ldomain') . '</p></div>';
            } else {
                echo '<div class="updated error notice-error is-dismissible"><p>' . __('Configuration can\'t Saved', 'ldomain') . '</p></div>';
            }
        }
        $config = get_option('g2Security');
    }
    ?>
    <form action="" method="post">
        <input type="hidden" name="save" value="1" id="">
        <input type="hidden" name="config[file_check_method][size]" value="1">
        <input type="hidden" name="config[file_check_method][modified]" value="1">
        <input type="hidden" name="config[file_check_method][md5]" value="1">
        <input type="hidden" name="config[notify_by_email]" value="1">

        <h3>Notification</h3>
        Fill the option below if you want to be notified by mail.<br>
        <?php _e('Email Address', 'ldomain'); ?>: 
        <input type="email" class="regular-text" value="<?php echo $config['email']; ?>" name="config[email]">
        <br>

        <h3>Base Security</h3>

        <?php
        // Module G2_General
        $settings = array(
            'gg_rpc' => 'Disable XML­RPC ',
            'gg_login_hint' => 'Disable login hints ',
            'gg_block_query' => 'Block Bad Queries ',
            'gg_remove_header' => 'Removes Header ',
            'gg_disable_file_editor' => 'Disable File Editor ',
            'gg_disable_pingback' => 'Disable XML­RPC Pingback and remove header',
            'gg_remove_me' => 'Remove "Remember Me"',        
        );
        ?>
        <?php foreach ($settings as $k => $v): ?>
            <label for="config_<?php echo $k; ?>">
                <input value="1" <?php if ($config[$k] == 1): ?>checked="checked" <?php endif; ?> type="checkbox" name="config[<?php echo $k; ?>]" id="config_<?php echo $k; ?>"> <?php echo $v; ?>
            </label><br>
        <?php endforeach; ?>

        <?php _e('Login Error Message', 'ldomain'); ?>: 
        <input  type="text" class="regular-text"name="config[gg_login_error_message]" value="<?php echo $config['gg_login_error_message']; ?>" id="err_msg">
        <br>

        <h3><?php _e('File Scanner', 'ldomain'); ?></h3>

        <?php _e('File Check Interval', 'ldomain'); ?>:
        <select name="config[file_check_interval]">
            <option <?php ___selected($config['file_check_interval'], 'hourly'); ?> value="hourly">Hourly</option>
            <option <?php ___selected($config['file_check_interval'], 'twicedaily'); ?> value="twicedaily">Twice Daily</option>
            <option <?php ___selected($config['file_check_interval'], 'daily'); ?> value="daily">Daily</option>
        </select>
        <br>

        <?php _e('Scan Path', 'ldomain'); ?>:
        <input type="text" placeholder="" value="<?php echo $config['site_root']; ?>" class="form-control regular-text" name="config[site_root]" id="">
        <br>

        <?php _e('Dirs/Files To Ignore', 'ldomain'); ?>:
        <textarea rows="3" cols="60" name="config[exclude_paths_files]"><?php echo $config['exclude_paths_files']; ?></textarea>
        <br>

        <?php _e('File Extensions Scan', 'ldomain'); ?>:
        <select name="config[file_extension_mode]">
            <option <?php ___selected($config['file_extension_mode'], 0, FALSE); ?> selected="selected" value="0"><?php _e('Disabled', 'ldomain'); ?></option>
            <option <?php ___selected($config['file_extension_mode'], 1, FALSE); ?> value="1"><?php _e('Exclude files that have an extension listed below', 'ldomain'); ?></option>
            <option <?php ___selected($config['file_extension_mode'], 2, FALSE); ?> value="2"><?php _e('Only scan files that have an extension listed below', 'ldomain'); ?></option>
        </select>
        <br>

        <?php _e('File Extensions', 'ldomain'); ?>:
        <input value="<?php echo $config['file_extensions']; ?>" name="config[file_extensions]" class="regular-text">
        <span class="description"><?php _e('Separate extensions with | character.', 'ldomain'); ?></span>
        <br>

        <?php _e('Virus Strings', 'ldomain'); ?>:
        <textarea name="config[virus]" cols="60" rows="3"><?php echo $config['virus']; ?></textarea>
        <br>
        
        <h3><?php _e('Others', 'ldomain'); ?></h3>
        <?php
        // Others
        $settings = array(
            'gg_disable_wp_updates' => 'Disable WP Core Update Notifications',      
        );
        ?>
        <?php foreach ($settings as $k => $v): ?>
            <label for="config_<?php echo $k; ?>">
                <input value="1" <?php if ($config[$k] == 1): ?>checked="checked" <?php endif; ?> type="checkbox" name="config[<?php echo $k; ?>]" id="config_<?php echo $k; ?>"> <?php echo $v; ?>
            </label><br>
        <?php endforeach; ?>
                

        <p class="submit"></p>
        <button class="button button-primary" type="submit"><?php _e('Save Options', 'ldomain'); ?></button>
    </form>
    <p>Love <b>G2 Security</b>? You can help by doing one simple thing:
        <a href="https://wordpress.org/plugins/seguroso/" target="_blank">Go to WordPress.org now and give this plugin a 5★ rating.</a>
    </p>
</div>