<?php
/**
 * Adds possibility to sort terms
 *
 * @since 1.2.0
 *
 * @package kebbet-taxonomy-arttype
 * @author Erik Betshammar
 */

namespace kebbet\taxonomy\arttype\sorting;

use const kebbet\taxonomy\arttype\TAXONOMY;
const META_SLUG = 'term-sorting';

/**
 * Add the field to the new term form
 *
 * @since 1.2.0
 */
function display_add_field() {

	wp_nonce_field( TAXONOMY . '_sorting_nonce_verify', TAXONOMY . '_sorting_nonce', true, true );
	?>
	<div class="form-field term-sorting">
		<label for="<?php echo esc_attr( META_SLUG ); ?>"><?php esc_html_e( 'Numeric sorting value', 'kebbet-taxonomy-arttype' ); ?></label>
		<input id="<?php echo esc_attr( META_SLUG ); ?>" name="<?php echo esc_attr( META_SLUG ); ?>" type="number" aria-describedby="sorting-description"/>
		<p id="sorting-description"><?php esc_html_e( 'Add a sorting number to the tags. On the front page, the tags are sorted from lowest number to highest.', 'kebbet-taxonomy-arttype' ); ?></p>
	</div>
	<?php
}
add_action( TAXONOMY . '_add_form_fields', __NAMESPACE__ . '\display_add_field', 10, 0 );

/**
 * Save the field to the term when created
 *
 * @since 1.2.0
 *
 * @param int $term_id The term id.
 */
function save_field( $term_id ) {
	// Check what we have posted, but verify nonce first.
	if ( ! isset( $_REQUEST[ TAXONOMY . '_sorting_nonce' ] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST[ TAXONOMY . '_sorting_nonce' ] ) ), TAXONOMY . '_sorting_nonce_verify' ) ) {
		return;
	}
	if ( isset( $_POST[ META_SLUG ] ) && '' !== $_POST[ META_SLUG ] ) {
		$value = sanitize_text_field( wp_unslash( $_POST[ META_SLUG ] ) );
		add_term_meta( $term_id, META_SLUG, $value, true );
	}
}
add_action( 'created_' . TAXONOMY, __NAMESPACE__ . '\save_field', 10, 1 );

/**
 * Add the field to the edit form for the term
 *
 * @since 1.2.0
 *
 * @param WP_term $term The term object.
 */
function display_edit_field( $term ) {
	$value = get_term_meta( $term->term_id, META_SLUG, true );
	wp_nonce_field( TAXONOMY . '_sorting_nonce_verify', TAXONOMY . '_sorting_nonce', true, true );

	?>
	<tr class="form-field term-group-wrap">
		<th scope="row"><label for="<?php echo esc_attr( META_SLUG ); ?>"><?php esc_html_e( 'Numeric sorting value', 'kebbet-taxonomy-arttype' ); ?></label></th>
		<td>
			<input type="number" id="<?php echo esc_attr( META_SLUG ); ?>" name="<?php echo esc_attr( META_SLUG ); ?>" value="<?php echo esc_html( $value ); ?>" aria-describedby="sorting-description">
			<p id="sorting-description" class="description"><?php esc_html_e( 'Add a sorting number to the tag. On the front page, the tags are sorted from lowest number to highest.', 'kebbet-taxonomy-arttype' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( TAXONOMY . '_edit_form_fields', __NAMESPACE__ . '\display_edit_field', 10, 1 );

/**
 * Update the field value from the edit term screen
 *
 * @since 1.2.0
 *
 * @param int $term_id The term id.
 */
function update_field( $term_id ) {

	// Check what we have posted, but verify nonce first.
	if ( ! isset( $_REQUEST[ TAXONOMY . '_sorting_nonce' ] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST[ TAXONOMY . '_sorting_nonce' ] ) ), TAXONOMY . '_sorting_nonce_verify' ) ) {
		return;
	}

	if ( isset( $_POST[ META_SLUG ] ) && '' !== $_POST[ META_SLUG ] ) {
		$value = sanitize_text_field( wp_unslash( $_POST[ META_SLUG ] ) );
		update_term_meta( $term_id, META_SLUG, $value );
	}
}
add_action( 'edited_' . TAXONOMY, __NAMESPACE__ . '\update_field', 10, 1 );

/**
 * Register the admin column header and content
 *
 * @since 1.2.0
 */
function sorting_add_columns() {
	add_filter( 'manage_edit-' . TAXONOMY . '_columns', __NAMESPACE__ . '\column_header' );
	add_filter( 'manage_' . TAXONOMY . '_custom_column', __NAMESPACE__ . '\column_content', 10, 3 );
}
add_action( 'admin_init', __NAMESPACE__ . '\sorting_add_columns' );

/**
 * Add column to the taxonomy table
 *
 * @since 1.2.0
 *
 * @param array $columns The original columns.
 * @return array
 */
function column_header( $columns ) {
	$columns['sorting'] = esc_html__( 'Sorting value', 'kebbet-taxonomy-arttype' );
	return $columns;
}

/**
 * Add data to the term row, in the taxonomy table
 *
 * @since 1.2.0
 *
 * @param string $string      The original columns.
 * @param string $column_name The column name.
 * @param int    $term_id     The term id.
 * @return string
 */
function column_content( $string, $column_name, $term_id ) {
	$meta = get_term_meta( $term_id, META_SLUG, true );
	if ( 'sorting' === $column_name && $meta ) {
		return esc_html( $meta );
	}
}
