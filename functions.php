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
add_action( 'enqueue_block_editor_assets', function() {
	wp_enqueue_style(
		'tt4-dark-mode-editor',
		get_theme_file_uri( 'assets/editor.css' ),
		filemtime( get_theme_file_path( 'assets/editor.css' ) )
	);
} );
