<?php

/**
 * Plugin Name: Headless WP Admin Access
 * Description: Control access to the WordPress admin.
 * Version:     0.1.0
 * Author:      Kellen Mace
 * Author URI:  https://kellenmace.com/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Control access to the WordPress admin.
 */
class AdminAccess
{
    public function register_hooks(): void
    {
        add_action('admin_init', [$this, 'log_out_and_redirect_non_admins']);
    }

    /**
     * If a non-Administrator attempts to visit the WordPress admin,
     * log them out and redirect them to the frontend app, if possible,
     * else the wp-admin login page.
     */
    public function log_out_and_redirect_non_admins(): void
    {
        $is_admin_user = current_user_can('administrator');

        if ($is_admin_user) {
            return;
        }

        wp_logout();

        $frontend_app_url = $this->get_frontend_app_url();

        // If a frontend app URL is set, send non-admin user there.
        // Otherwise, send them to the admin login page.
        if ($frontend_app_url) {
            wp_redirect($frontend_app_url);
        } else {
            wp_safe_redirect(admin_url());
        }
    }

    /**
     * Get frontend app URL. This comes from the 'Extend "Access-Control-Allow-Origin” header'
     * option on the WPGraphQL CORS plugin settings page.
     *
     * @return string Frontend app URL if set, else empty string.
     */
    private function get_frontend_app_url(): string
    {
        if (!function_exists('get_graphql_setting')) {
            return '';
        }

        $acao = get_graphql_setting('acao', '', 'graphql_cors_settings');

        if ($acao === '' || $acao === '*') {
            return '';
        }

        $acao_urls = explode(PHP_EOL, $acao);

        if (!$acao_urls) {
            return '';
        }

        return trim($acao_urls[0]);
    }
}

(new AdminAccess())->register_hooks();
