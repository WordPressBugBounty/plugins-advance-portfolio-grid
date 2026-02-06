<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly 

/**
 * Manage the columns of the "portfolio" post type
 *
 * @param array $columns Post type columns.
 * @return void
 */
function wpb_fp_manage_columns_for_portfolio($columns)
{
    unset($columns['date']);
    $columns['portfolio_featured_image'] = esc_html__('Featured Image', 'advance-portfolio-grid');
    $columns['date'] = esc_html__('Date', 'advance-portfolio-grid');
    return $columns;
}
add_action('manage_wpb_fp_portfolio_posts_columns', 'wpb_fp_manage_columns_for_portfolio');

/**
 * Populate custom columns for "portfolio" post type
 *
 * @param string $column The post type column name.
 * @param int $post_id The post id.
 * @return void
 */
function wpb_fp_populate_portfolio_columns($column, $post_id)
{
    if ($column == 'portfolio_featured_image') {
        if (has_post_thumbnail($post_id)) {
            $portfolio_featured_image = get_the_post_thumbnail($post_id, array(100, 100));
            echo wp_kses_post($portfolio_featured_image);
        } else {
            echo esc_html__('No featured image.', 'advance-portfolio-grid');
        }
    }
}
add_action('manage_wpb_fp_portfolio_posts_custom_column', 'wpb_fp_populate_portfolio_columns', 10, 2);
