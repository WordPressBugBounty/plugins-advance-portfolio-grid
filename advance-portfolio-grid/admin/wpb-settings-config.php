<?php

/*
    WPB Portfolio PRO
    By WPBean
    
*/

if (! defined('ABSPATH')) exit; // Exit if accessed directly 


/**
 * installing setting api class by wpbean
 */
if (!class_exists('WPB_FP_Settings_Config')):
    class WPB_FP_Settings_Config
    {

        private $settings_api;

        function __construct()
        {
            $this->settings_api = new WPB_FP_WeDevs_Settings_API;

            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_menu', array($this, 'admin_menu'));
        }

        function admin_init()
        {

            //set the settings
            $this->settings_api->set_sections($this->get_settings_sections());
            $this->settings_api->set_fields($this->get_settings_fields());

            //initialize settings
            $this->settings_api->admin_init();
        }

        function admin_menu()
        {

            add_submenu_page(
                'edit.php?post_type=wpb_fp_portfolio',
                esc_html__('Portfolio Settings', 'advance-portfolio-grid'),
                esc_html__('Portfolio Settings', 'advance-portfolio-grid'),
                'delete_posts',
                'portfolio-settings',
                array($this, 'plugin_page')
            );
        }
        // setings tabs
        function get_settings_sections()
        {
            $sections = array(
                array(
                    'id'    => 'wpb_fp_general',
                    'title' => esc_html__('General Settings', 'advance-portfolio-grid')
                ),
                array(
                    'id'    => 'wpb_fp_advanced',
                    'title' => esc_html__('Advanced Settings', 'advance-portfolio-grid')
                ),
                array(
                    'id'    => 'wpb_fp_style',
                    'title' => esc_html__('Style Settings', 'advance-portfolio-grid')
                ),
                array(
                    'id'    => 'wpb_fp_slider',
                    'title' => esc_html__('Slider Settings', 'advance-portfolio-grid')
                ),
            );

            return $sections;
        }

        /**
         * Returns all the settings fields
         *
         * @return array settings fields
         */
        function get_settings_fields()
        {
            $settings_fields = array(

                'wpb_fp_general' => array(
                    array(
                        'name'              => 'wpb_fp_column_',
                        'label'             => esc_html__('Columns', 'advance-portfolio-grid'),
                        'desc'              => esc_html__('Number of portfolio columns.', 'advance-portfolio-grid'),
                        'type'              => 'select',
                        'default'           => '4',
                        'options'           => array(
                            '2'  => esc_html__('6 Columns', 'advance-portfolio-grid'),
                            '3'  => esc_html__('4 Columns', 'advance-portfolio-grid'),
                            '4'  => esc_html__('3 Columns', 'advance-portfolio-grid'),
                            '6'  => esc_html__('2 Columns', 'advance-portfolio-grid'),
                            '12' => esc_html__('1 Column', 'advance-portfolio-grid'),
                        )
                    ),
                    array(
                        'name'      => 'wpb_fp_number_of_post_',
                        'label'     => esc_html__('Number of post', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Number of post to show. Default -1, means show all.', 'advance-portfolio-grid'),
                        'type'      => 'number',
                        'default'   => -1
                    ),
                    array(
                        'name'      => 'wpb_fp_number_of_title_character',
                        'label'     => esc_html__('Number of Characters in Title', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Number of characters in title to show. Default 16. You have to must check Portfolio Title Character Limit to function this limit.', 'advance-portfolio-grid'),
                        'type'      => 'number',
                        'default'   => 16
                    ),
                ),
                'wpb_fp_advanced' => array(
                    array(
                        'name'      => 'wpb_post_type_select_',
                        'label'     => esc_html__('Post Type', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('You can select your own custom post type. Default: Our portfolio post type that come with plugin.', 'advance-portfolio-grid'),
                        'type'      => 'select',
                        'default'   => 'wpb_fp_portfolio',
                        'options'   => wpb_fp_post_type_select(),
                    ),
                    array(
                        'name'      => 'wpb_taxonomy_select_',
                        'label'     => esc_html__('Taxonomy', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('You can select your own custom taxonomy ( taxonomy means custom category ).  Default: Our portfolio category that come with plugin.', 'advance-portfolio-grid'),
                        'type'      => 'select',
                        'default'   => 'wpb_fp_portfolio_cat',
                        'options'   => wpb_fp_taxonomy_select(),
                    ),
                    array(
                        'name'      => 'wpb_fp_cat_exclude_',
                        'label'     => esc_html__('Exclude Categories', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('You can exclude selected categories from the portfolio.', 'advance-portfolio-grid'),
                        'type'      => 'multicheck',
                        'options'   => wpb_fp_exclude_categories(),
                    ),
                    array(
                        'name'      => 'wpb_fp_cat_include_',
                        'label'     => esc_html__('Include Categories', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('You can include selected categories from the portfolio.', 'advance-portfolio-grid'),
                        'type'      => 'multicheck',
                        'options'   => wpb_fp_exclude_categories(),
                    ),
                    array(
                        'name'      => 'wpb_fp_image_width_',
                        'label'     => esc_html__('Image Width', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Portfolio thumbnail width in Px. Minimum 200. Default 480', 'advance-portfolio-grid'),
                        'type'      => 'number',
                        'min'       => 200,
                        'default'   => 480
                    ),
                    array(
                        'name'      => 'wpb_fp_image_height_',
                        'label'     => esc_html__('Image height', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Portfolio thumbnail height in Px. Minimum 200. Default 480', 'advance-portfolio-grid'),
                        'type'      => 'number',
                        'min'       => 200,
                        'default'   => 480
                    ),
                    array(
                        'name'      => 'wpb_fp_show_overlay_',
                        'label'     => esc_html__('Portfolio overlay', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Portfolio overlay on mouse hover. Default: Show.', 'advance-portfolio-grid'),
                        'type'      => 'radio',
                        'default'   => 'show',
                        'options'   => array(
                            'show'  => esc_html__('Show', 'advance-portfolio-grid'),
                            'hide'  => esc_html__('Hide', 'advance-portfolio-grid'),
                        )
                    ),
                    array(
                        'name'      => 'wpb_fp_hover_bg',
                        'label'     => esc_html__('Portfolio Hover Background Color', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Need hover background color for portfolio items. Default: Yes.', 'advance-portfolio-grid'),
                        'type'      => 'radio',
                        'default'   => 'yes',
                        'options'   => array(
                            'yes'  => esc_html__('Yes', 'advance-portfolio-grid'),
                            'no'  => esc_html__('No', 'advance-portfolio-grid'),
                        )
                    ),
                    array(
                        'name'      => 'wpb_fp_show_links_',
                        'label'     => esc_html__('Portfolio overlay Links', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Portfolio overlay on mouse hover showing two links. Default: Show.', 'advance-portfolio-grid'),
                        'type'      => 'radio',
                        'default'   => 'show',
                        'options'   => array(
                            'show'  => esc_html__('Show', 'advance-portfolio-grid'),
                            'hide'  => esc_html__('Hide', 'advance-portfolio-grid'),
                        )
                    ),
                    array(
                        'name'      => 'wpb_fp_full_grid_link',
                        'label'     => esc_html__('Portfolio Full Grid Link', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Link the full grid to the popup. Default: No.', 'advance-portfolio-grid'),
                        'type'      => 'radio',
                        'default'   => 'no',
                        'options'   => array(
                            'yes'  => esc_html__('Yes', 'advance-portfolio-grid'),
                            'no'   => esc_html__('No', 'advance-portfolio-grid'),
                        )
                    ),
                    array(
                        'name'      => 'wpb_fp_link_full_grid_type_',
                        'label'     => esc_html__('Full Grid Link type', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('If you disable / hide the overlay on mouse hover the grid and enable the full grid linking, you may want to link either portfolio details page or qiickview popup.', 'advance-portfolio-grid'),
                        'type'      => 'radio',
                        'default'   => 'quickview_popup',
                        'options'   => array(
                            'details_page'      => esc_html__('Portfolio details / External URL', 'advance-portfolio-grid'),
                            'quickview_popup'   => esc_html__('QuickView Popup', 'advance-portfolio-grid'),
                        )
                    ),
                    array(
                        'name'      => 'wpb_fp_view_portfolio_btn_text_',
                        'label'     => esc_html__('View Portfolio Button Text', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('View portfolio button that allow you to link your external site or anything else. You can change that button text.', 'advance-portfolio-grid'),
                        'type'      => 'text',
                        'default'   => esc_html__('View Portfolio', 'advance-portfolio-grid'),
                    ),
                ),
                'wpb_fp_style' => array(
                    array(
                        'name'      => 'wpb_fp_primary_color_',
                        'label'     => esc_html__('Primary color', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Select your portfolio primary color. Default: #21cdec', 'advance-portfolio-grid'),
                        'type'      => 'color',
                        'default'   => '#21cdec'
                    ),
                    array(
                        'name'      => 'wpb_fp_primary_color_hover',
                        'label'     => esc_html__('Primary color hover', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Select your portfolio primary hover color. Default: #009cba', 'advance-portfolio-grid'),
                        'type'      => 'color',
                        'default'   => '#009cba'
                    ),
                    array(
                        'name'      => 'wpb_fp_popup_effect_',
                        'label'     => esc_html__('Quick View Effect.', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Select your Quick View Effect popup effect.', 'advance-portfolio-grid'),
                        'type'      => 'select',
                        'default'   => 'mfp-zoom-in',
                        'options'   => array(
                            'mfp-zoom-in'           => esc_html__('Zoom effect', 'advance-portfolio-grid'),
                            'mfp-newspaper'         => esc_html__('Newspaper effect', 'advance-portfolio-grid'),
                            'mfp-move-horizontal'   => esc_html__('Move-horizontal effect', 'advance-portfolio-grid'),
                            'mfp-move-from-top'     => esc_html__('Move-from-top effect', 'advance-portfolio-grid'),
                            'mfp-3d-unfold'         => esc_html__('3d unfold', 'advance-portfolio-grid'),
                            'mfp-zoom-out'          => esc_html__('Zoom-out effect', 'advance-portfolio-grid'),
                        ),
                    ),
                    array(
                        'name'      => 'wpb_fp_hover_effect_',
                        'label'     => esc_html__('Hover Effect.', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Select an effect for mouse hover on portfolio.', 'advance-portfolio-grid'),
                        'type'      => 'select',
                        'default'   => 'effect-oscar',
                        'options'   => array(
                            'effect-roxy'     => esc_html__('Roxy', 'advance-portfolio-grid'),
                            'effect-bubba'    => esc_html__('Bubba', 'advance-portfolio-grid'),
                            'effect-marley'   => esc_html__('Marley', 'advance-portfolio-grid'),
                            'effect-oscar'    => esc_html__('Oscar', 'advance-portfolio-grid'),
                            'effect-layla'    => esc_html__('Layla', 'advance-portfolio-grid'),
                        ),
                    ),
                    array(
                        'name'      => 'wpb_fp_title_font_size_',
                        'label'     => esc_html__('Portfolio title font size.', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Font size for portfolio title. Default 20px.', 'advance-portfolio-grid'),
                        'type'      => 'number',
                        'default'   => 20
                    ),
                    array(
                        'name'      => 'wpb_fp_qv_max_width',
                        'label'     => esc_html__('Quick View Max Width', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Quick View LightBox Max Width. Default 980px.', 'advance-portfolio-grid'),
                        'type'      => 'number',
                        'default'   => 980
                    ),
                ),

                'wpb_fp_slider' => array(
                    array(
                        'name'  => 'wpb_fp_enable_slider',
                        'label' => esc_html__('Enable Slider', 'advance-portfolio-grid'),
                        'desc'  => esc_html__('Check this to enable the portfolio slider', 'advance-portfolio-grid'),
                        'type'  => 'checkbox'
                    ),
                    array(
                        'name'      => 'wpb_fp_autoplay',
                        'label'     => esc_html__('Slider Autoplay', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Check this to enable the slider autoplay', 'advance-portfolio-grid'),
                        'type'      => 'checkbox',
                        'default'   => 'on',
                    ),
                    array(
                        'name'      => 'wpb_fp_loop',
                        'label'     => esc_html__('Slider Loop', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Check this to enable the slider loop', 'advance-portfolio-grid'),
                        'type'      => 'checkbox'
                    ),
                    array(
                        'name'      => 'wpb_fp_navigation',
                        'label'     => esc_html__('Slider Navigation', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Check this to enable the slider navigation', 'advance-portfolio-grid'),
                        'type'      => 'checkbox',
                        'default'   => 'on',
                    ),
                    array(
                        'name'      => 'wpb_fp_pagination',
                        'label'     => esc_html__('Slider Pagination', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Check this to enable the slider pagination', 'advance-portfolio-grid'),
                        'type'      => 'checkbox',
                        'default'   => 'on',
                    ),
                    array(
                        'name'      => 'wpb_fp_margin',
                        'label'     => esc_html__('Slider Margin', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Space between slider items. Default 15px.', 'advance-portfolio-grid'),
                        'type'      => 'number',
                        'default'   => 15
                    ),
                    array(
                        'name'      => 'wpb_fp_items',
                        'label'     => esc_html__('Slider Column', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Slider column. Default 3.', 'advance-portfolio-grid'),
                        'type'      => 'number',
                        'default'   => 3
                    ),
                    array(
                        'name'      => 'wpb_fp_items_tablet',
                        'label'     => esc_html__('Slider Column in Tablet', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Slider column in tablet. Default 2.', 'advance-portfolio-grid'),
                        'type'      => 'number',
                        'default'   => 2
                    ),
                    array(
                        'name'      => 'wpb_fp_items_mobile',
                        'label'     => esc_html__('Slider Column in Mobile', 'advance-portfolio-grid'),
                        'desc'      => esc_html__('Slider column in mobile. Default 1.', 'advance-portfolio-grid'),
                        'type'      => 'number',
                        'default'   => 1
                    ),
                ),
            );
            return $settings_fields;
        }

        // warping the settings
        function plugin_page()
        {
?>
            <?php do_action('wpb_fp_before_settings'); ?>
            <div class="wpb_fp_settings_area">

                <div class="wrap wpb_fp_settings">
                    <?php $this->settings_api->show_navigation(); ?>
                    <?php $this->settings_api->show_forms(); ?>
                    <div class="wpb_fp_settings_content">
                        <?php do_action('wpb_fp_settings_content'); ?>
                    </div>
                </div>
            </div>
            <?php do_action('wpb_fp_after_settings'); ?>
<?php
        }

        /**
         * Get all the pages
         *
         * @return array page names with key value pairs
         */
        function get_pages()
        {
            $pages = get_pages();
            $pages_options = array();
            if ($pages) {
                foreach ($pages as $page) {
                    $pages_options[$page->ID] = $page->post_title;
                }
            }
            return $pages_options;
        }
    }
endif;

$settings = new WPB_FP_Settings_Config();


//--------- trigger setting api class---------------- //

function wpb_fp_get_option($option, $section, $default = '')
{

    $options = get_option($section);

    if (isset($options[$option])) {
        return $options[$option];
    }

    return $default;
}
