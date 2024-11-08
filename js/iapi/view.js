/**
 * WordPress dependencies
 */
import { store } from '@wordpress/interactivity';

const { state } = store( 'tt4-dark-mode', {
	state: {
		get isLoggedIn() {
			return state.userID > 0;
		},
	},
	actions: {
		toggleMode() {
			state.darkMode = ! state.darkMode;
			console.log( 'Dark mode is now:', state.darkMode );
		},
	},
	callbacks: {
		updateColorScheme() {
			if ( 'undefined' === typeof state.darkMode ) {
				return;
			}
			const root = document.querySelector( ':root' );
			root.style.setProperty(
				'color-scheme',
				state.darkMode ? 'dark' : 'light'
			);

			// need to fix this so it doesn't run on initial load.
			if ( state.isLoggedIn ) {
				wp.apiFetch( {
					path: `/wp/v2/users/${ state.userID }`,
					method: 'POST',
					data: {
						meta: {
							'color-scheme': state.darkMode ? 'dark' : 'light',
						},
					},
				} ).then( ( res ) => {} );
			} else {
				document.cookie = `color-scheme:${ state.colorScheme };path=/`;

				localStorage.setItem(
					'color-scheme',
					state.darkMode ? 'dark' : 'light'
				);
			}
		},
		getColorScheme() {
			if ( state.isLoggedIn && state.colorScheme.length > 0 ) {
				console.log( 'Getting color scheme from user meta' );
				state.darkMode = state.colorScheme === 'dark';
			} else {
				// Handle a user preference in the browser
				const userPreference = localStorage.getItem( 'color-scheme' );
				if ( userPreference ) {
					state.darkMode = userPreference === 'dark';
					return;
				}

				console.log( document.cookie );
				state.darkMode =
					window.matchMedia &&
					window.matchMedia( '(prefers-color-scheme: dark)' ).matches;
			}
		},
	},
} );
