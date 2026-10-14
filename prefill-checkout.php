<?php
/**
 * Hangar Gym · Carrera: rellena el checkout de WooCommerce con los datos
 * que el usuario ya capturó en la landing (se pegan en Code Snippets o functions.php).
 * Recibe por URL: billing_first_name, billing_last_name, billing_email,
 * billing_phone, order_comments y coupon_code.
 */
add_filter( 'woocommerce_checkout_get_value', function ( $value, $input ) {
	$allowed = array( 'billing_first_name', 'billing_last_name', 'billing_email', 'billing_phone', 'order_comments' );
	if ( in_array( $input, $allowed, true ) && isset( $_GET[ $input ] ) ) {
		return sanitize_text_field( wp_unslash( $_GET[ $input ] ) );
	}
	return $value;
}, 10, 2 );

add_action( 'template_redirect', function () {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || empty( $_GET['coupon_code'] ) ) {
		return;
	}
	$code = wc_format_coupon_code( wp_unslash( $_GET['coupon_code'] ) );
	if ( WC()->cart && ! WC()->cart->has_discount( $code ) ) {
		WC()->cart->apply_coupon( $code );
	}
} );
