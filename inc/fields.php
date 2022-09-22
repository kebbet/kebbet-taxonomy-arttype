<?php
/**
 * Modifies fields in the form, for the taxonomy.
 *
 * @package kebbet-taxonomy-arttype
 * @author Erik Betshammar
 */

namespace kebbet\taxonomy\arttype\fields;

use const kebbet\taxonomy\arttype\TAXONOMY;
use const kebbet\taxonomy\arttype\HIDE_DESC;

function hide_form_fields() {
	$screen = get_current_screen();
	$style  = '';

	if ( 'edit-' . TAXONOMY !== $screen->id ) {
		return;
	}

	if ( true === HIDE_DESC ) {
		$style  = '<style type="text/css">';
		$style .= '.term-description-wrap{';
		$style .= 'display:none;visibility:hidden;opacity:0';
		$style .= '}';
		$style .= '</style>';
	}

	if ( $style ) {
		echo $style;
	} else {
		return;
	}
}
add_filter( 'admin_head', __NAMESPACE__ . '\hide_form_fields' );
