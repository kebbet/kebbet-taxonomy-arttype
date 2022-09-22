<?php
/**
 * Plugin Name:       Kebbet plugins - custom taxonomy: arttype
 * Plugin URI:        https://github.com/kebbet/kebbet-taxonomy-arttype
 * Description:       Register the custom taxonomy arttype
 * Version:           1.0
 * Author:            Erik Betshammar
 * Author URI:        https://verkan.se
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Update URI:        false
 *
 * @package kebbet-taxonomy-arttype
 * @author Erik Betshammar
 */

namespace kebbet\taxonomy\arttype;

const TAXONOMY   = 'arttype';
const POST_TYPES = array( 'art' );
const HIDE_SLUG  = false;

/**
 * Hook into the 'init' action
 */
function init() {
	load_textdomain();
	register();
}
add_action( 'init', __NAMESPACE__ . '\init', 0 );

/**
 * Flush rewrite rules on registration.
 */
function rewrite_flush() {
	// First, we "add" the custom taxonomy via the above written function.
	register();

	// ATTENTION: This is *only* done during plugin activation hook in this example!
	// You should *NEVER EVER* do this on every page load!!
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\rewrite_flush' );

/**
 * Load plugin textdomain.
 */
function load_textdomain() {
	load_plugin_textdomain( 'kebbet-taxonomy-arttype', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Register the taxonomy
 */
function register() {

	$tax_labels = array(
		'name'                       => _x( 'Art type', 'taxonomy general name', 'kebbet-taxonomy-arttype' ),
		'menu_name'                  => __( 'Art type', 'kebbet-taxonomy-arttype' ),
		'singular_name'              => _x( 'Art type', 'taxonomy singular name', 'kebbet-taxonomy-arttype' ),
		'all_items'                  => __( 'All art type tags', 'kebbet-taxonomy-arttype' ),
		'edit_item'                  => __( 'Edit tag', 'kebbet-taxonomy-arttype' ),
		'view_item'                  => __( 'View tag', 'kebbet-taxonomy-arttype' ),
		'update_item'                => __( 'Update tag', 'kebbet-taxonomy-arttype' ),
		'add_new_item'               => __( 'Add new tag', 'kebbet-taxonomy-arttype' ),
		'new_item_name'              => __( 'New tag name', 'kebbet-taxonomy-arttype' ),
		'separate_items_with_commas' => __( 'Separate art type tags with commas', 'kebbet-taxonomy-arttype' ),
		'search_items'               => __( 'Search tags', 'kebbet-taxonomy-arttype' ),
		'add_or_remove_items'        => __( 'Add or remove tags', 'kebbet-taxonomy-arttype' ),
		'choose_from_most_used'      => __( 'Choose from the most used  tags', 'kebbet-taxonomy-arttype' ),
		'not_found'                  => __( 'No tags found.', 'kebbet-taxonomy-arttype' ),
		'popular_items'              => __( 'Popular tags', 'kebbet-taxonomy-arttype' ),
		'parent_item'                => __( 'Parent tag', 'kebbet-taxonomy-arttype' ),
		'parent_item_colon'          => __( 'Parent tag:', 'kebbet-taxonomy-arttype' ),
		'back_to_items'              => __( '&larr; Back to tags', 'kebbet-taxonomy-arttype' ),
		'name_field_description'     => __( 'The name is how it appears in the user interface.', 'kebbet-taxonomy-arttype' ),
		'slug_field_description'     => __( 'The &#8220;slug&#8221; is a sanitized version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens. Do not change if not needed.', 'kebbet-taxonomy-arttype' ),
		'desc_field_description'     => __( 'The description is not used.', 'kebbet-taxonomy-arttype' ),
	);

	$capabilities = array(
		'manage_terms' => 'manage_categories', // Previous 'manage_options'.
		'edit_terms'   => 'manage_categories', // Previous 'manage_options'.
		'delete_terms' => 'manage_categories', // Previous 'manage_options'.
		'assign_terms' => 'publish_posts',
	);

	$tax_args = array(
		'capabilities'          => $capabilities,
		'hierarchical'          => false,
		'has_archive'           => false,
		'labels'                => $tax_labels,
		'public'                => false,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => false,
		'show_in_rest'          => true,
		'rewrite'               => false,
		'description'           => __( 'Metadata tag for sorting and grouping posts.', 'kebbet-taxonomy-arttype' ),
	);

	register_taxonomy( TAXONOMY, POST_TYPES, $tax_args );
}

/**
 * Remove the 'slug' column from the table in 'edit-tags.php'
 */
function remove_column_slug( $columns ) {
    if ( isset( $columns['slug'] ) )
        unset( $columns['slug'] );   

    return $columns;
}

/**
 * Run filter only if constant says so.
 */
if ( true === HIDE_SLUG ) {
	add_filter( 'manage_edit-' . TAXONOMY . '_columns', __NAMESPACE__ . '\remove_column_slug' );
}
