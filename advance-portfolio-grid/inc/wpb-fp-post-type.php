<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly 

/**
 * Register Custom Post Type for portfolio
 */
if (! function_exists('wpb_fp_post_type')) {
	function wpb_fp_post_type()
	{
		$labels = array(
			'name'                => esc_html_x('Portfolios', 'Post Type General Name', 'advance-portfolio-grid'),
			'singular_name'       => esc_html_x('Portfolio', 'Post Type Singular Name', 'advance-portfolio-grid'),
			'menu_name'           => esc_html__('Portfolio Grid', 'advance-portfolio-grid'),
			'parent_item_colon'   => esc_html__('Parent Portfolio:', 'advance-portfolio-grid'),
			'all_items'           => esc_html__('All Portfolios', 'advance-portfolio-grid'),
			'view_item'           => esc_html__('View Portfolio', 'advance-portfolio-grid'),
			'add_new_item'        => esc_html__('Add New Portfolio', 'advance-portfolio-grid'),
			'add_new'             => esc_html__('Add New', 'advance-portfolio-grid'),
			'edit_item'           => esc_html__('Edit Portfolio', 'advance-portfolio-grid'),
			'update_item'         => esc_html__('Update Portfolio', 'advance-portfolio-grid'),
			'search_items'        => esc_html__('Search Portfolio', 'advance-portfolio-grid'),
			'not_found'           => esc_html__('Not found', 'advance-portfolio-grid'),
			'not_found_in_trash'  => esc_html__('Not found in Trash', 'advance-portfolio-grid'),
		);
		$rewrite = array(
			'slug'                => apply_filters('wpb_fp_post_type_slug', 'portfolio-items'),
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);
		$args = array(
			'label'               => esc_html__('Portfolio', 'advance-portfolio-grid'),
			'description'         => esc_html__('Portfolio Grid plugin post type', 'advance-portfolio-grid'),
			'labels'              => $labels,
			'supports'            => array('title', 'editor', 'thumbnail', 'page-attributes'),
			'show_in_rest'	      => true,
			'taxonomies'          => array('wpb_fp_portfolio_cat'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 50,
			'menu_icon'           => 'dashicons-portfolio',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
		);
		register_post_type('wpb_fp_portfolio', $args);
	}

	// Hook into the 'init' action
	add_action('init', 'wpb_fp_post_type', 0);
}




/**
 * Register Theme Features (feature image for portfolio)
 */
if (! function_exists('wpb_fp_theme_support')) {
	function wpb_fp_theme_support()
	{
		add_theme_support('post-thumbnails', array('wpb_fp_portfolio'));
	}
	add_action('after_setup_theme', 'wpb_fp_theme_support');
}

/**
 * Register Custom Taxonomy for Portfolio
 */
if (! function_exists('wpb_fp_taxonomy')) {
	function wpb_fp_taxonomy()
	{
		$labels = array(
			'name'                       => esc_html_x('Portfolio Categories', 'Taxonomy General Name', 'advance-portfolio-grid'),
			'singular_name'              => esc_html_x('Portfolio Category', 'Taxonomy Singular Name', 'advance-portfolio-grid'),
			'menu_name'                  => esc_html__('Portfolio Category', 'advance-portfolio-grid'),
			'all_items'                  => esc_html__('All Categories', 'advance-portfolio-grid'),
			'parent_item'                => esc_html__('Parent Category', 'advance-portfolio-grid'),
			'parent_item_colon'          => esc_html__('Parent Category:', 'advance-portfolio-grid'),
			'new_item_name'              => esc_html__('New Category Name', 'advance-portfolio-grid'),
			'add_new_item'               => esc_html__('Add New Category', 'advance-portfolio-grid'),
			'edit_item'                  => esc_html__('Edit Category', 'advance-portfolio-grid'),
			'update_item'                => esc_html__('Update Category', 'advance-portfolio-grid'),
			'separate_items_with_commas' => esc_html__('Separate categories with commas', 'advance-portfolio-grid'),
			'search_items'               => esc_html__('Search categories', 'advance-portfolio-grid'),
			'add_or_remove_items'        => esc_html__('Add or remove Categories', 'advance-portfolio-grid'),
			'choose_from_most_used'      => esc_html__('Choose from the most used categories', 'advance-portfolio-grid'),
			'not_found'                  => esc_html__('Not Found', 'advance-portfolio-grid'),
		);
		$rewrite = array(
			'slug'                       => 'portfolio-category',
			'with_front'                 => true,
			'hierarchical'               => false,
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
			'rewrite'                    => $rewrite,
			'show_in_rest'				 => true,
		);
		register_taxonomy('wpb_fp_portfolio_cat', array('wpb_fp_portfolio'), $args);
	}

	// Hook into the 'init' action
	add_action('init', 'wpb_fp_taxonomy', 0);
}


/**
 *  Filtering Posts by Taxonomies in the Dashboard
 */
add_action('restrict_manage_posts', 'wpb_fp_portfolio_filter_by_taxonomy', 10, 2);
function wpb_fp_portfolio_filter_by_taxonomy($post_type, $which)
{
	// Only add filter to your specific custom post type
	if ($post_type == 'wpb_fp_portfolio') {
		$taxonomy = 'wpb_fp_portfolio_cat';
		$selected = isset($_GET[$taxonomy]) ? sanitize_text_field(wp_unslash($_GET[$taxonomy])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$info_taxonomy = get_taxonomy($taxonomy);

		wp_dropdown_categories(array(
			// translators: %s: taxonomy label
			'show_option_all' => sprintf(__('All %s', 'advance-portfolio-grid'), esc_html($info_taxonomy->label)),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => esc_attr($selected),
			'show_count'      => true,
			'hide_empty'      => true,
			'value_field'     => 'slug',
		));
	}
}
