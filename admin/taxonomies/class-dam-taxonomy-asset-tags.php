<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    Digital_Asset_Manager
 * @subpackage Digital_Asset_Manager/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Digital_Asset_Manager
 * @subpackage Digital_Asset_Manager/includes
 * @author     2ByteCode <support@2bytecode.com>
 */
class DAM_Taxonomy_Asset_Tags {

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->options = get_option( 'dam_settings_options', dam_settings_default_options() );
		add_filter( 'dam_asset_tags_taxonomy_args', array( $this, 'dam_set_asset_tags_taxonomy_visibility' ), 99, 1 );
	}

	/**
	 * Regsiter Custom Taxonomy - Asset Tags.
	 *
	 * @return void
	 */
	public function dam_taxonomy_asset_tags() {

		$labels = array(
			'name'                       => _x( 'Asset Tags', 'Taxonomy General Name', 'digital-asset-manager' ),
			'singular_name'              => _x( 'Asset Tags', 'Taxonomy Singular Name', 'digital-asset-manager' ),
			'menu_name'                  => __( 'Asset Tags', 'digital-asset-manager' ),
			'all_items'                  => __( 'All Tags', 'digital-asset-manager' ),
			'parent_item'                => __( 'Parent Tags', 'digital-asset-manager' ),
			'parent_item_colon'          => __( 'Parent Tags:', 'digital-asset-manager' ),
			'new_item_name'              => __( 'New Asset Tags', 'digital-asset-manager' ),
			'add_new_item'               => __( 'Add New Asset Tags', 'digital-asset-manager' ),
			'edit_item'                  => __( 'Edit Asset Tags', 'digital-asset-manager' ),
			'update_item'                => __( 'Update Asset Tags', 'digital-asset-manager' ),
			'view_item'                  => __( 'View Asset Tags', 'digital-asset-manager' ),
			'separate_items_with_commas' => __( 'Separate Asset Tags with commas', 'digital-asset-manager' ),
			'add_or_remove_items'        => __( 'Add or remove Asset Tags', 'digital-asset-manager' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'digital-asset-manager' ),
			'popular_items'              => __( 'Popular Asset Tags', 'digital-asset-manager' ),
			'search_items'               => __( 'Search Asset Tags', 'digital-asset-manager' ),
			'not_found'                  => __( 'Not Found', 'digital-asset-manager' ),
			'no_terms'                   => __( 'No Asset Tags', 'digital-asset-manager' ),
			'items_list'                 => __( 'Asset Tags list', 'digital-asset-manager' ),
			'items_list_navigation'      => __( 'Asset Tags list navigation', 'digital-asset-manager' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
		);
		register_taxonomy( 'asset-tags', array( 'dam-asset' ), apply_filters( 'dam_asset_tags_taxonomy_args', $args ) );

	}

	/**
	 * Set the visibility of the taxonomy.
	 *
	 * @param array $args Array of arguments to create custom taxonomy.
	 * @return array
	 */
	public function dam_set_asset_tags_taxonomy_visibility( $args ) {
		if ( isset( $this->options['asset_tags_taxonomy_visibility'] ) && 'public' === $this->options['asset_tags_taxonomy_visibility'] ) {
			$args['public']            = true;
			$args['show_in_nav_menus'] = true;
		}

		return $args;
	}

}
