<?php
/**
 * Plugin deactivation.
 *
 * @package kebbet-taxonomy-arttype
 * @author Erik Betshammar
 */

namespace kebbet\taxonomy\arttype\deactivation;

use const kebbet\taxonomy\arttype\TAXONOMY;

/**
 * Un-register taxonomy and flush rewrite rules on deactivation.
 *
 * @since 1.1.0
 */
function deactivate() {
	// Unregister the taxonomy.
	\unregister_taxonomy( TAXONOMY );

	// Flush rules after unregistration.
	\flush_rewrite_rules();
}
