<?php
/**
 * BizJapan Unified Assets Management Module
 * Centralizes theme styles and scripts with cache-busting versioning and performance optimization.
 *
 * @package oscss-elementor-bizjapan
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'BIZJAPAN_ASSETS_VERSION' ) ) {
	define( 'BIZJAPAN_ASSETS_VERSION', '1.1.0' );
}

/**
 * Enqueue all BizJapan theme assets
 */
function bizjapan_enqueue_theme_assets() {
	$theme_uri = get_template_directory_uri();

	// 1. Main JavaScript (Loaded in footer with passive scroll handler)
	wp_enqueue_script(
		'bizjapan-main-script',
		$theme_uri . '/main.js',
		[],
		BIZJAPAN_ASSETS_VERSION,
		true // in footer
	);

	// 2. Custom HK Typography & Base Design
	wp_enqueue_style(
		'bizjapan-custom-style',
		$theme_uri . '/assets/css/bizjapan-custom.css',
		[],
		BIZJAPAN_ASSETS_VERSION
	);

	// 3. Universal Responsive Styles (Mobile / Tablet / PC)
	wp_enqueue_style(
		'bizjapan-responsive-style',
		$theme_uri . '/assets/css/bizjapan-responsive.css',
		[ 'bizjapan-custom-style' ],
		BIZJAPAN_ASSETS_VERSION
	);

	// 4. Contact Form CSS (Only on contact pages or when shortcode exists)
	if ( is_page( 'contact-us' ) || is_page( 'contact' ) || ( is_singular() && has_shortcode( get_post()->post_content ?? '', 'bizjapan_contact_form' ) ) ) {
		wp_enqueue_style(
			'bizjapan-contact-form-style',
			$theme_uri . '/assets/css/bizjapan-contact-form.css',
			[ 'bizjapan-responsive-style' ],
			BIZJAPAN_ASSETS_VERSION
		);
	}
}
add_action( 'wp_enqueue_scripts', 'bizjapan_enqueue_theme_assets', 99 );
