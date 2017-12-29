<div class="wrap">

    <h1>G2 Security DashBoard</h1>

    <p>For support, e-mail us at <a target="_blank" href="https://ezosc.com/?utm_source=g2&utm_medium=link&utm_campaign=dashboard">support@ezosc.com</a>
        <br>Love <b>G2 Security</b>? You can help by doing one simple thing: <a href="https://wordpress.org/plugins/seguroso/" target="_blank">Go to WordPress.org now and give this plugin a 5★ rating.</a> </p>

    <h3>Notifications</h3>
    <table class="wp-list-table widefat striped plugins">
        <thead>
            <tr>
                <td class="manage-column check-column" scope="col">&nbsp;</td>
                <th width="250" class="manage-column column-name column-primary" scope="col">Type</th>
                <th class="manage-column column-description" scope="col">Status</th>
            </tr>
        </thead>
        <tbody id="report-themes">
            <tr>
                <th align="center" class="check-column" scope="row">&nbsp; <span style="color:green" class="dashicons dashicons-yes"></span></th>
                <td class="plugin-title column-primary">WPScan Vulnerability Database</td>
                <td><?php printf(__('Last request to %s on %s', G2_Vulnerability_Alerts::$id), '<a href="https://wpvulndb.com/" target="_blank">WPScan Vulnerability Database</a>', date_i18n(get_option('date_format') . ' ' . get_option('time_format'), get_option('g2_report_last_check'))); ?>
                </td>
            </tr>
            <tr>
                <th align="center" class="check-column" scope="row">&nbsp;
                    <?php if (get_option('g2_google_safe_browsing_report') == "Dangerous") { ?>
                        <span style="color:Crimson" class="dashicons dashicons-warning"></span>
                    <?php } else { ?>
                        <span style="color:green" class="dashicons dashicons-yes"></span>
                    <?php } ?>
                </th>
                <td class="plugin-title column-primary">Google Safe Browsing Site Status / BlackListed</td>
                <td>
                    <?php if (get_option('g2_google_safe_browsing_report') == "Dangerous") { ?>
                        <span style="color:red">
                            <?php printf(__('Current status: %s', G2_Google_Safe_Browsing::$id), get_option('g2_google_safe_browsing_report')); ?>
                        </span>
                    <?php } else { ?>
                        <span style="color:green">
                            <?php printf(__('Current status: %s', G2_Google_Safe_Browsing::$id), get_option('g2_google_safe_browsing_report')); ?>
                        </span>
                    <?php } ?>
                    <br><?php printf(__('Last request on %s', G2_Google_Safe_Browsing::$id), date_i18n(get_option('date_format') . ' ' . get_option('time_format'), get_option('g2_report_last_check'))); ?>
                    <br>for more information visit
                    <a href="https://transparencyreport.google.com/safe-browsing/search?url=<?php echo preg_replace('/^www\./', '', $_SERVER['SERVER_NAME']); ?>" target="_blank">Google Safe Browsing Site Status</a>
                </td>
                </td>
            </tr>
            <!-- File Scan-->
            <tr>
                <td>
                </td>
                <td><?php _e('File Scan', 'ldomain'); ?></td>
                <td><?php
                    global $wpdb;
                    $sql = "Select * from {$wpdb->prefix}g2_noti where module LIKE 'file%' limit 1 ";
                    $fileScan = $wpdb->get_row($sql);
                    _e('Last Scan on ', 'ldomain');
                    echo ( empty($fileScan->timestamp) ? 'n/a' : date('d F Y H:i:s', $fileScan->timestamp));
                    ?>
                    <a href="<?php echo admin_url('admin.php?page=g2-security-notifications&&id=' . $fileScan->id); ?>">View Details</a>
                </td>
            </tr>
            <?php
            global $wpdb;
            $sql = "Select * from {$wpdb->prefix}g2_noti where module LIKE 'virus%' limit 1 ";
            $virusScanResult = $wpdb->get_row($sql);
            if ($virusScanResult != NULL):
                $infectedFiles = explode('<br>', $virusScanResult->desc)
                ?>
                <tr>
                    <td>
                        <div id="content_virus_scan" style="display:none;">
                            <table>
                                <tr>
                                    <td><h2>Virus Found </h2></td>
                                </tr>
                                <tr>
                                    <td><?php
                                        foreach ($infectedFiles as $infectedFile) {
                                            echo '<div style="padding: 6px;margin-bottom: 2px;background-color: #fafafa;">' . $infectedFile . '</div>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td><?php _e('Virus Scan', 'ldomain'); ?> <span style="color: red">(<?php echo count($infectedFiles); ?> infected files)</span></td>
                    <td><?php _e('Last Scan on '); ?><?php echo date('d F Y H:i:s', $virusScanResult->timestamp); ?>
                        <a href="<?php echo admin_url('admin.php?page=g2-security-notifications&&id=' . $virusScanResult->id); ?>" >View Details</a>
                    </td>
                </tr>
<?php endif; ?>
        </tbody>
    </table>
    
        <h3>Recommendations</h3>
    <table class="wp-list-table widefat striped plugins">
        <thead>
            <tr>
                <td class="manage-column check-column" scope="col">&nbsp;</td>
                <th width="250" class="manage-column column-name column-primary" scope="col">Type</th>
                <th class="manage-column column-description" scope="col">Status</th>
            </tr>
        </thead>
        <tbody id="report-themes">
            <tr>
                <th align="center" class="check-column" scope="row">&nbsp; <span style="color:green" class="dashicons dashicons-yes"></span></th>
                <td class="plugin-title column-primary">WORDPRESS SUPPORT</td>
                <td>
                    <a target="_blank" href="http://ezosc.com" style="padding:1px 5px;color:#fff;background:#feba12;border-radius:1px;">Click here to get Wordpress Support</a>
                    <br>Do you have a WordPress issue that is driving you mad? If so let us fix it for you.  If we can’t fix it we will refund your money. You have nothing to lose!!!</td>
            </tr>

            <tr>
                <th align="center" class="check-column" scope="row">&nbsp; <span style="color:green" class="dashicons dashicons-yes"></span></th>
                <td class="plugin-title column-primary">SiteGround Wrodpress Hosting</td>
                <td><a href="https://www.siteground.com/go/g2-seguroso" target="_blank">
                        $3.95/mo. SiteGround has tools that make managing WordPress sites easy.
                        </a>
                </td>
            </tr>

        </tbody>
    </table>
        
    <h3>WordPress Core</h3>
    <table class="wp-list-table widefat striped plugins">
        <thead>
            <tr>
                <td scope="col" class="manage-column check-column">&nbsp;</td>
                <th scope="col" class="manage-column column-name column-primary" width="250"><?php _e('Name', G2_Vulnerability_Alerts::$id) ?></th>
                <th scope="col" class="manage-column column-description"><?php _e('Vulnerabilities', G2_Vulnerability_Alerts::$id) ?></th>
            </tr>
        </thead>
        <tbody id="report-wordpress">
            <tr>
                <th scope="row" class="check-column" align="center"><?php echo G2_Vulnerability_Alerts::get_status('wordpress') ?></span></th>
                <td class="plugin-title column-primary"><strong>WordPress</strong> <?php echo sprintf(__('Version %s', G2_Vulnerability_Alerts::$id), get_bloginfo('version')) ?></td>
                <td><?php G2_Vulnerability_Alerts::list_vulnerabilities('wordpress') ?></td>
            </tr>
        </tbody>
    </table>
    <h3><?php _e('Plugins', G2_Vulnerability_Alerts::$id) ?></h3>
    <table class="wp-list-table widefat striped plugins">
        <thead>
            <tr>
                <td scope="col" class="manage-column check-column">&nbsp;</td>
                <th scope="col" class="manage-column column-name column-primary" width="250"><?php _e('Name', G2_Vulnerability_Alerts::$id) ?></th>
                <th scope="col" class="manage-column column-description"><?php _e('Vulnerabilities', G2_Vulnerability_Alerts::$id) ?></th>
            </tr>
        </thead>
        <tbody id="report-plugins">
<?php foreach (get_plugins() as $name => $details) : ?>
                <tr>
                    <th scope="row" class="check-column" align="center"><?php echo G2_Vulnerability_Alerts::get_status('plugins', $name) ?></span></th>
                    <td class="plugin-title column-primary"><strong><?php echo $details['Name'] ?></strong> <?php echo sprintf(__('Version %s', G2_Vulnerability_Alerts::$id), $details['Version']) ?>
                    </td>
                    <td><?php G2_Vulnerability_Alerts::list_vulnerabilities('plugins', $name) ?></td>
                </tr>
<?php endforeach; ?>
        </tbody>
    </table>
    <h3><?php _e('Themes', G2_Vulnerability_Alerts::$id) ?></h3>
    <table class="wp-list-table widefat striped plugins">
        <thead>
            <tr>
                <td scope="col" class="manage-column check-column">&nbsp;</td>
                <th scope="col" class="manage-column column-name column-primary" width="250"><?php _e('Name', G2_Vulnerability_Alerts::$id) ?></th>
                <th scope="col" class="manage-column column-description"><?php _e('Vulnerabilities', G2_Vulnerability_Alerts::$id) ?></th>
            </tr>
        </thead>
        <tbody id="report-themes">
<?php foreach (wp_get_themes() as $name => $details) : ?>
                <tr>
                    <th scope="row" class="check-column" align="center"><?php echo G2_Vulnerability_Alerts::get_status('themes', $name) ?></span></th>
                    <td class="plugin-title column-primary"><strong><?php echo $details['Name'] ?></strong> <?php echo sprintf(__('Version %s', G2_Vulnerability_Alerts::$id), $details['Version']) ?>
                    </td>
                    <td><?php G2_Vulnerability_Alerts::list_vulnerabilities('themes', $name) ?></td>
                </tr>
<?php endforeach; ?>
        </tbody>
    </table>
    <p>Love <b>G2 Security</b>? You can help by doing one simple thing:
        <a href="https://wordpress.org/plugins/seguroso/" target="_blank">Go to WordPress.org now and give this plugin a 5★ rating.</a>
    </p>
</div>