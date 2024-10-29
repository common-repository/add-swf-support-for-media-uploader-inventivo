<?php /*
Contributors: inventivogermany
Plugin Name:  Add SWF Support for Media Uploader | inventivo
Plugin URI:   https://www.inventivo.de/wordpress-agentur/wordpress-plugins
Description:  Add SWF Support for Media Uploader
Version:      1.0.3
Author:       Nils Harder
Author URI:   https://www.inventivo.de
Tags: swf, swf media uploader, upload swf
Requires at least: 3.0
Tested up to: 5.7.1
Stable tag: 1.0.3
Text Domain: iventivo-addswfsupport
Domain Path: /languages
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (! defined('ABSPATH')) {
    exit;
}

class InventivoAddSwfSupport
{
    public function __construct()
    {
        add_filter('upload_mimes', array($this, 'allow_swf'));
        add_action('admin_notices', array($this, 'admin_notice_get_pro'));
        add_action('admin_enqueue_scripts', array($this, 'admin_style'));
        add_action( 'admin_init', array($this, 'notice_dismissed') );
        register_deactivation_hook( __FILE__, array($this, 'plugin_deactivate') );
    }

    public function allow_swf($mimes)
    {
        if (function_exists('current_user_can')) {
            $unfiltered = $user ? user_can($user, 'unfiltered_html') : current_user_can('unfiltered_html');
        }

        if (!empty($unfiltered)) {
            $mimes['swf'] = 'application/x-shockwave-flash';
        }

        return $mimes;
    }

    public function admin_notice_get_pro() {
        $user_id = get_current_user_id();
        if (!get_user_meta( $user_id, 'inv_notice_dismissed')) {
            echo '<div class="notice notice-success is-dismissible">
                <div class="hreflang-x-default-tag-for-wpml-inventivo-wrapper">
                    <div class="hreflang-x-default-tag-for-wpml-inventivo-element">
                        <a href="https://www.inventivo.de/en/the-x-default-tag-in-wpml-is-missing-what-now#pluginkaufen" target="_blank">
                            <img src="' . plugins_url() . '/add-swf-support-for-media-uploader-inventivo/admin/images/icon-256x256-1.png" />
                        </a>
                    </div>
                    <div class="hreflang-x-default-tag-for-wpml-inventivo-element">
                        <h2>Hey Dude!</h2>
                        <p><strong>Are you using WPML Multilanguage Plugin in your site?</strong><br />
                        WPML does not add the x-default hreflang-Tag which may be a problem for your onpage SEO.
                        <br />I just wrote a handy plugin which adds the x-default tag to your WordPress + WPML website.<br />
                        </p>
                        <p>
                            <a class="wp-core-ui button" target="_blank" href="https://www.inventivo.de/en/the-x-default-tag-in-wpml-is-missing-what-now#pluginkaufen" style="color: #FFFFFF; background: #A6CE38; border-color: #A6CE38">
                            Learn more & get it now!
                            </a>
                            <br /><br />
                            <a href="?inv-notice-dismissed" style="color: #AAAAAA;">Dismiss</a>
                        </p>
                    </div>
                </div>
            </div>';
        }
    }

    public function admin_style() {
        wp_enqueue_style('admin-styles', plugins_url().'/add-swf-support-for-media-uploader-inventivo/admin/css/admin-styles.css');
    }

    public function notice_dismissed()
    {
        $user_id = get_current_user_id();
        if (isset( $_GET['inv-notice-dismissed'])) {
            add_user_meta( $user_id, 'inv_notice_dismissed', 'true', true );
        }
    }

    public function plugin_deactivate()
    {
        $user_id = get_current_user_id();
        delete_user_meta($user_id, 'inv_notice_dismissed');
    }
}

if (is_admin()) {
    $inventivoAddSwfSupport = new InventivoAddSwfSupport();
}
