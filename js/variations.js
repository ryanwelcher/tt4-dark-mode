/**
 * WordPress dependencies
 */
import { registerBlockVariation } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

registerBlockVariation('core/button', {
	name: 'tt4-dark-mode/toggle-color-scheme',
	title: __('Dark/Light Mode', 'tt4-dark-mode'),
	icon: 'smiley',
	description: __(
		'Handle the dark and light mode toggle with a single button.',
		'tt4-dark-mode'
	),
	isActive: ['className'],
	attributes: {
		className: 'is-dark-mode',
	},
	scope: ['inserter'],
});
