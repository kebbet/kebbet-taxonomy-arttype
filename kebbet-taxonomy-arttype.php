<?php
/**
 * Plugin Name:       Kebbet plugins - custom taxonomy: arttype
 * Plugin URI:        https://github.com/kebbet/kebbet-taxonomy-arttype
 * Description:       Registers the custom taxonomy arttype
 * Version:           1.1.0
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
const POST_TYPES = array( 'art', 'project' );
const HIDE_SLUG  = false;
const HIDE_DESC  = true;

/**
 * On plugin activation.
 */
function activation() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/activation.php';
	activation\activate();
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\activation' );

/**
 * On plugin de-activation.
 */
function deactivation() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/deactivation.php';
	deactivation\deactivate();
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\deactivation' );

/**
 * Main taxonomy registration.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/register.php';
/**
 * Hook into the 'init' action
 *
 * @since 1.0.0
 */
function init() {
	\kebbet\taxonomy\arttype\register\load_textdomain();
	\kebbet\taxonomy\arttype\register\register();
}
add_action( 'init', __NAMESPACE__ . '\init', 0 );

/**
 * Modifies the admin columns, for the taxonomy.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-columns.php';

/**
 * Modifies fields in the form, for the taxonomy.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/fields.php';
