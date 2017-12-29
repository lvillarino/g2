<?php
/*
 * Google Recaptcha Integration
 * 
 * Thanks to the plugin === Google Captcha (reCAPTCHA) by BestWebSoft ===
 * https://wordpress.org/plugins/google-captcha/
 */

class G2_Google_Recaptcha {

    public static $lang_domain = 'seguroso­grecaptcha';

    public static function define_public_hooks() {
        $config = get_option('g2GoogleCaptcha');
                
        if ($config['key'] != '' && $config['secret'] != ''){
            wp_enqueue_script('google-captcha', 'https://www.google.com/recaptcha/api.js');
        }
                
        //login
        if (1 == $config['login_form']) {
            add_filter('login_form', array('G2_Google_Recaptcha', 'captcha_display'));
            add_filter('login_form_bottom', array('G2_Google_Recaptcha', 'add_captcha_markup'));             
            add_action('authenticate', array('G2_Google_Recaptcha', 'login_check'), 21, 1);
        }

        //reset password
        if (1 == $config['reset_pwd_form']) {
            add_action('lostpassword_form', array('G2_Google_Recaptcha', 'captcha_display'));
            add_action('allow_password_reset', array('G2_Google_Recaptcha', 'reset_pwd_check'));
        }

        //Registeration form
        if ('1' == $config['registration_form']) {
            if (!is_multisite()) {
                add_action('register_form', array('G2_Google_Recaptcha', 'captcha_display'));
                add_action('registration_errors', array('G2_Google_Recaptcha', 'reset_pwd_check'));
            } else {
                add_action('signup_extra_fields', array('G2_Google_Recaptcha', 'captcha_display'));
                add_action('signup_blogform', array('G2_Google_Recaptcha', 'captcha_display'));
                add_filter('wpmu_validate_user_signup', array('G2_Google_Recaptcha', 'multisite_captcha_check'));
            }
            if ('1' == $config['comments_form']) {
                add_action('comment_form_after_fields', array('G2_Google_Recaptcha', 'captcha_display'));
                add_action('comment_form_logged_in_after', array('G2_Google_Recaptcha', 'captcha_display'));
                add_action('pre_comment_on_post', array('G2_Google_Recaptcha', 'check_comment_form'));
            }
        }
    }

    public function add_captcha_markup() {
        $config = get_option('g2GoogleCaptcha');
        return ' <div style="margin-bottom:10px"  class="g-recaptcha" data-sitekey="' . $config['key'] . '"></div>';
    }

    public function captcha_display() {
        $config = get_option('g2GoogleCaptcha');
        echo '<div  style="margin-bottom:10px" class="g-recaptcha" data-sitekey="' . $config['key'] . '"></div>';
    }

    public function multisite_captcha_check($result) {
        global $current_user;
        if (is_admin() && !empty($current_user->data->ID))
            return $result;
        $check_result = G2_Google_Recaptcha::verify_captcha();
        if ($check_result['response'] || $check_result['reason'] == 'ERROR_NO_KEYS')
            return $result;
        $error = $result['errors'];
        $error->add('g2_google_captcha', __('ERROR', G2_Google_Recaptcha::$lang_domain) . ':&nbsp;' . __('You have entered an incorrect reCAPTCHA value', G2_Google_Recaptcha::$lang_domain) . '.');

        return $result;
    }

    public function check_comment_form() {

        $response = G2_Google_Recaptcha::verify_captcha();
        if ($response['response'] || $response['reason'] == 'ERROR_NO_KEYS')
            return;
        wp_die(__('ERROR', G2_Google_Recaptcha::$lang_domain) . ':&nbsp;' . __('You have entered an incorrect reCAPTCHA value. Click the BACK button on your browser, and try again.', G2_Google_Recaptcha::$lang_domain));
    }

    public static function get_response($private_key, $user_ip) {
        $args = array(
            'body' => array(
                'secret' => $private_key,
                'response' => stripslashes(esc_html($_POST["g-recaptcha-response"])),
                'remoteip' => $user_ip,
            ),
            'sslverify' => FALSE
        );
        $resp = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', $args);

        return json_decode(wp_remote_retrieve_body($resp), TRUE);
    }

    public static function verify_captcha($debug = false) {
        $config = get_option('g2GoogleCaptcha');

        $public_key = $config['key'];
        $private_key = $config['secret'];
        if (!$public_key || !$private_key) {
            return array(
                'response' => FALSE,
                'reason' => 'ERROR_NO_KEYS'
            );
        }
        $user_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

        if (!isset($_POST["g-recaptcha-response"])) {
            return array(
                'response' => FALSE,
                'reason' => 'RECAPTCHA_NO_RESPONSE'
            );
        } elseif (empty($_POST["g-recaptcha-response"])) {
            return array(
                'response' => FALSE,
                'reason' => 'RECAPTCHA_EMPTY_RESPONSE'
            );
        }


        $response = G2_Google_Recaptcha::get_response($private_key, $user_ip);

        if (isset($response['success']) && !!$response['success']) {
            return array(
                'response' => TRUE,
                'reason' => ''
            );
        } else {
            return array(
                'response' => FALSE,
                'reason' => $debug ? $response['error-codes'] : 'VERIFICATION_FAILED'
            );
        }
    }

    public function login_check($user) {
        $response = G2_Google_Recaptcha::verify_captcha();
        if (!$response['response']) {
            if ($response['reason'] == 'ERROR_NO_KEYS') {
                return $user;
            }
            $error_message = sprintf('<strong>%s</strong>: %s', __('Error', 'g2-google-captcha'), __('You have entered an incorrect reCAPTCHA value.', G2_Google_Recaptcha::$lang_domain));
            if ($response['reason'] == 'VERIFICATION_FAILED') {
                wp_clear_auth_cookie();

                return new WP_Error('gglcptch_error', $error_message);
            }
            if (isset($_REQUEST['log']) && isset($_REQUEST['pwd'])) {
                return new WP_Error('gglcptch_error', $error_message);
            } else {
                return $user;
            }
        } else {
            return $user;
        }
    }

    public function reset_pwd_check($allow) {
        $response = G2_Google_Recaptcha::verify_captcha();
        if ($response['response'] || $response['reason'] == 'ERROR_NO_KEYS')
            return $allow;
        if (!is_wp_error($allow))
            $allow = new WP_Error();
        $allow->add('g2-google-captcha', __('ERROR', G2_Google_Recaptcha::$lang_domain) . ':&nbsp;' . __('You have entered an incorrect reCAPTCHA value', G2_Google_Recaptcha::$lang_domain) . '.');

        return $allow;
    }

    public static function settings_page() {
        $forms = array(
            array('login_form', __('Login form', G2_Google_Recaptcha::$lang_domain)),
            array('registration_form', __('Registration form', G2_Google_Recaptcha::$lang_domain)),
            array('reset_pwd_form', __('Reset password form', G2_Google_Recaptcha::$lang_domain)),
            array('comments_form', __('Comments form', G2_Google_Recaptcha::$lang_domain)),
        );
        if ($_POST['action'] == 1) {
            $savedData = get_option('g2GoogleCaptcha', array('key' => '', 'secret' => ''));
            if (update_option('g2GoogleCaptcha', $_POST['config']) || $savedData === $_POST['config']) :
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e('Configuration Saved!', G2_Google_Recaptcha::$lang_domain); ?></p>
                </div>
            <?php else: ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php _e('Configuration cant be saved.Please try again!', G2_Google_Recaptcha::$lang_domain); ?></p>
                </div>
            <?php
            endif;
        }
        $savedData = get_option('g2GoogleCaptcha', array('key' => '', 'secret' => ''));
        if (empty($savedData)) {
            $savedData = array('key' => '', 'secret' => '');
        }
        ?>
        <div class="wrap">
            <h1 style="line-height: normal;"><?php _e('Google Captcha Configuration', G2_Google_Recaptcha::$lang_domain); ?></h1>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=g2-security-google-recaptcha">
                <input type="hidden" name="action" value="1" id="">
                <h3><?php _e('Authentication', G2_Google_Recaptcha::$lang_domain); ?></h3>
                <p><?php printf(__('Before you are able to do something, you must to register %shere%s', G2_Google_Recaptcha::$lang_domain), '<a target="_blank" href="https://www.google.com/recaptcha/admin#list">', '</a>.'); ?></p>
                <p><?php _e('Enter site key and secret key, that you get after registration.', G2_Google_Recaptcha::$lang_domain); ?></p>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Site Key', G2_Google_Recaptcha::$lang_domain); ?></th>
                        <td>
                            <input type="text" name="config[key]" value="<?php echo $savedData['key']; ?>" maxlength="200"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Site Secret', G2_Google_Recaptcha::$lang_domain); ?></th>
                        <td>
                            <input type="text" name="config[secret]" value="<?php echo $savedData['secret']; ?>" maxlength="200"/>
                        </td>
                    </tr>
                </table>
                <h3><?php _e('Options', G2_Google_Recaptcha::$lang_domain); ?></h3>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Enable reCAPTCHA for', G2_Google_Recaptcha::$lang_domain); ?></th>
                        <td>
                            <fieldset>
                                <p>
                                    <i><?php _e('WordPress default', G2_Google_Recaptcha::$lang_domain); ?></i>
                                </p>
                                <?php foreach ($forms as $form) {
                                    ?>
                                    <label>
                                        <input type="checkbox" name="config[<?php echo $form[0]; ?>]" value="1" <?php if ($savedData[$form[0]] == 1) : ?>checked="checked"<?php endif; ?> /> <?php echo $form[1]; ?>
                                    </label>
                                    <br>
                                <?php } ?>
                            </fieldset>
                        </td>
                    </tr>

                </table>
                <p class="submit">
                    <input id="bws-submit-button" type="submit" class="button-primary" value="<?php _e('Save Changes', G2_Google_Recaptcha::$lang_domain); ?>" name="save_changes"/>
                    <input type="hidden" name="g2submit" value="submit"/>
                </p>
            </form>
        </div>
        <?php
    }

}
