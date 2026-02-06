<?php
/*
 * Plugin Name: Advance Portfolio Grid
 * Plugin URI: https://wpbean.com/downloads/wpb-filterable-portfolio/
 * Description: Advance Portfolio Grid, a highly customizable most advance portfolio plugin for WordPress. Use this shortcode [wpb-portfolio]
 * Author: WPBean
 * Author URI: https://wpbean.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version: 1.07.7
 * Requires at least: 6.7
 * Requires PHP: 7.4
 * Text Domain: advance-portfolio-grid
 * Domain Path: /languages
 */
if (! defined('ABSPATH')) exit; // Exit if accessed directly 

if (! function_exists('is_plugin_active')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

/**
 * Define constants
 */

if (! defined('WPB_FP_FREE_INIT')) {
    define('WPB_FP_FREE_INIT', plugin_basename(__FILE__));
}

/**
 * This version can't be activate if premium version is active
 */
if (defined('WPB_FP_PREMIUM')) {
    function wpb_fp_install_free_admin_notice()
    {
?>
        <div class="error">
            <p><?php esc_html_e('You can\'t activate the free version of WPB Filterable Portfolio while you are using the premium one.', 'advance-portfolio-grid'); ?></p>
        </div>
<?php
    }

    add_action('admin_notices', 'wpb_fp_install_free_admin_notice');
    deactivate_plugins(plugin_basename(__FILE__));
    return;
}


/**
 * Add plugin action links
 */
function wpb_fp_lite_plugin_actions($links)
{
    $links[] = '<a href="' . menu_page_url('portfolio-settings', false) . '">' . esc_html__('Settings', 'advance-portfolio-grid') . '</a>';
    $links[] = '<a href="https://wpbean.com/support/" target="_blank">' . esc_html__('Support', 'advance-portfolio-grid') . '</a>';
    $links[] = '<a href="https://wpbean.com/downloads/wpb-filterable-portfolio/" target="_blank" style="color: #39b54a; font-weight: 700;">' . esc_html__('Buy Pro Version', 'advance-portfolio-grid') . '</a>';
    return $links;
}

/**
 * Pro version discount admin notice
 */
function wpb_fp_pro_discount_admin_notice()
{
    $user_id = get_current_user_id();
    if (!get_user_meta($user_id, 'wpb_fp_pro_discount_dismissed')) {
        printf('<div class="wpb-fp-discount-notice updated" style="padding: 30px 20px;border-left-color: #27ae60;border-left-width: 5px;margin-top: 20px;"><p style="font-size: 18px;line-height: 32px">%s <a target="_blank" href="%s">%s</a>! %s <b>%s</b></p><a href="%s">%s</a></div>', esc_html__('Get a 10% exclusive discount on the premium version of the', 'advance-portfolio-grid'), 'https://wpbean.com/downloads/wpb-filterable-portfolio/', esc_html__('Advance Portfolio Grid', 'advance-portfolio-grid'), esc_html__('Use discount code - ', 'advance-portfolio-grid'), 'NewCustomer', esc_url(add_query_arg('wpb-fp-pro-discount-admin-notice-dismissed', 'true')), esc_html__('Dismiss', 'advance-portfolio-grid'));
    }
}

/**
 * Discount Notice Dismiss
 *
 * @return void
 */
function wpb_fp_pro_discount_admin_notice_dismissed()
{
    $user_id = get_current_user_id();
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if (isset($_GET['wpb-fp-pro-discount-admin-notice-dismissed'])) {
        add_user_meta($user_id, 'wpb_fp_pro_discount_dismissed', 'true', true);
    }
}

/**
 * Plugin Deactivation
 */
function wpb_fp_lite_plugin_deactivation()
{
    $user_id = get_current_user_id();
    if (get_user_meta($user_id, 'wpb_fp_pro_discount_dismissed')) {
        delete_user_meta($user_id, 'wpb_fp_pro_discount_dismissed');
    }

    flush_rewrite_rules();
}

/**
 * Plugin Activation
 */
function wpb_fp_lite_plugin_activation()
{
    flush_rewrite_rules();
}

/**
 * Plugin Init
 */
function wpb_fp_lite_plugin_init()
{
    register_deactivation_hook(plugin_basename(__FILE__), 'wpb_fp_lite_plugin_deactivation');
    register_activation_hook(plugin_basename(__FILE__), 'wpb_fp_lite_plugin_activation');
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpb_fp_lite_plugin_actions');
    add_action('admin_notices', 'wpb_fp_pro_discount_admin_notice');
    add_action('admin_init', 'wpb_fp_pro_discount_admin_notice_dismissed');

    require_once dirname(__FILE__) . '/inc/wpb_scripts.php';
    require_once dirname(__FILE__) . '/inc/wpb-fp-shortcode.php';
    require_once dirname(__FILE__) . '/inc/wpb-fp-post-type.php';
    require_once dirname(__FILE__) . '/admin/wpb_aq_resizer.php';
    require_once dirname(__FILE__) . '/admin/wpb-fp-admin.php';
    require_once dirname(__FILE__) . '/admin/wpb-class.settings-api.php';
    require_once dirname(__FILE__) . '/admin/wpb-settings-config.php';
    require_once dirname(__FILE__) . '/inc/wpb-functions.php';
    require_once dirname(__FILE__) . '/inc/wpb_fp_metabox.php';

    if (defined('ELEMENTOR__FILE__')) {
        require_once dirname(__FILE__) . '/inc/wpb_fp_elementor.php';
    }
}
add_action('plugins_loaded', 'wpb_fp_lite_plugin_init');
