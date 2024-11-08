<?php

/**
 * Theme functions file, which is autoloaded by WordPress. This file is used to
 * load any other necessary PHP files and bootstrap the theme.
 *
 * @author    Your Name <yourname@some-email-service-or-another.com>
 * @copyright Copyright (c) 2024, Your Name
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/justintadlock/tt4-dark-mode
 */

// Adds an editor stylesheet.
add_action(
	'enqueue_block_editor_assets',
	function() {
		wp_enqueue_style(
			'tt4-dark-mode-editor',
			get_theme_file_uri( 'assets/editor.css' ),
			filemtime( get_theme_file_path( 'assets/editor.css' ) )
		);

		// Enqueue the variation.
		$assets_file = get_stylesheet_directory() . '/build/variations/variations.asset.php';
		if ( file_exists( $assets_file ) ) {
			$assets = include $assets_file;
			wp_enqueue_script(
				'dark-mode-toggle-variation',
				get_stylesheet_directory_uri() . '/build/variations/variations.js',
				$assets['dependencies'],
				$assets['version'],
				true
			);
		}
	}
);

// User meta.
add_action(
	'init',
	function() {
		register_meta(
			'user',
			'color-scheme',
			array(
				'show_in_rest' => true,
				'type'         => 'string',
				'single'       => true,
			),
		);
	}
);

add_filter(
	'render_block_core/button',
	function( $block_content, $block ) {
		if ( isset( $block['attrs']['className'] ) && 'is-dark-mode' === $block['attrs']['className'] ) {
			// enqueue the scripts/styles
			wp_enqueue_style( 'dark-mode-button' );
			wp_enqueue_script_module( 'dark-mode-toggle-view' );
			wp_enqueue_script( 'wp-api-fetch' );

			// Setup the initial state for the store
			wp_interactivity_state(
				'tt4-dark-mode',
				array(
					'userID'      => get_current_user_id(), // user ID or 0;
					'colorScheme' => is_user_logged_in() ? get_user_meta( get_current_user_id(), 'color-scheme', true ) : '',
					'darkMode'    => get_user_meta( get_current_user_id(), 'color-scheme', true ) === 'dark',
				)
			);

			ob_start();
			?>
				<button
					data-wp-interactive="tt4-dark-mode"
					data-wp-bind--aria-pressed="state.darkMode"
					data-wp-on--click="actions.toggleMode"
					data-wp-watch="callbacks.updateColorScheme"
					data-wp-init="callbacks.getColorScheme"
					class="toggle"
					type="button"
				>
					<span class="toggle__display" hidden>
						<!-- The toggle does not change at all -->
					</span>
					Button
				</button>
			<?php
			return ob_get_clean();

		}
		return $block_content;
	},
	10,
	2
);

add_action( 'wp_enqueue_scripts', function() {

	// Register the style.
	$url = get_stylesheet_directory_uri() . '/css/dark-mode-button.css';
	wp_register_style( 'dark-mode-button', $url, array(), 1 );

	$js_file = get_stylesheet_directory() . '/build/iapi/view.asset.php';
	if ( file_exists( $js_file ) ) {

		// Register view.js script module.
		$js_assets = include $js_file;
		wp_register_script_module(
			'dark-mode-toggle-view',
			get_stylesheet_directory_uri() . '/build/iapi/view.js',
			$js_assets['dependencies'],
			$js_assets['version']
		);
	}
} );
