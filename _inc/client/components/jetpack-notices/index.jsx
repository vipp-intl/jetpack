/**
 * External dependencies
 */
import React from 'react';
import { connect } from 'react-redux';
import SimpleNotice from 'components/notice';
import { translate as __ } from 'lib/mixins/i18n';

/**
 * Internal dependencies
 */
import {
	getJetpackNotices as _getJetpackNotices
} from 'state/jetpack-notices';
import QueryJetpackNotices from 'components/data/query-jetpack-notices';
import { getSiteConnectionStatus as _getSiteConnectionStatus } from 'state/connection';

const JetpackNotices = React.createClass( {
	displayName: 'JetpackNotices',

	renderMessage: function( message ) {
		// Rationale behind returning an object and not just the string
		// https://facebook.github.io/react/tips/dangerously-set-inner-html.html
		return { __html: message };
	},

	maybeShowDevVersion: function() {
		if ( window.Initial_State.isDevVersion ) {
			const text = __( 'You are currently running a development version of Jetpack. {{a}} Submit your feedback {{/a}}',
				{
					components: {
						a: <a href="https://jetpack.com/contact-support/beta-group/" target="_blank" />
					}
				}
			);

			return (
				<SimpleNotice showDismiss={ false }>
					{ text }
				</SimpleNotice>
			);
		}
	},

	maybeShowDevMode: function() {
		const devMode = window.Initial_State.connectionStatus.devMode;
		if ( ! devMode.isActive ) {
			return;
		}

		let devModeType = '';
		if ( devMode.filter ) { devModeType += __( 'the jetpack_development_mode filter. ' ); }
		if ( devMode.constant ) { devModeType += __( 'the JETPACK_DEV_DEBUG constant. ' ); }
		if ( devMode.url ) { devModeType += __( 'your site URL lacking a dot (e.g. http://localhost).' ); }

		const text = __( 'Currently in {{a}}Development Mode{{/a}} VIA ' + devModeType,
			{
				components: {
					a: <a href="https://jetpack.com/support/development-mode/" target="_blank" />
				}
			}
		);

		return (
			<SimpleNotice showDismiss={ false }>
				{ text }
			</SimpleNotice>
		);
	},

	renderContent: function() {
		console.log( window.Initial_State.connectionStatus );
		const notices = this.props.jetpackNotices( this.props );

		if ( false ) {
			return (
				<div>
					<SimpleNotice showDismiss={ true }>
						<div dangerouslySetInnerHTML={ this.renderMessage( notices.message ) } />
					</SimpleNotice>
				</div>
			);
		}
	},

	render() {
		return (
			<div>
				<QueryJetpackNotices />
				{ this.maybeShowDevVersion() }
				{ this.maybeShowDevMode() }
				{ this.renderContent() }
			</div>
		);
	}
} );

export default connect(
	state => {
		return {
			jetpackNotices: () => _getJetpackNotices( state )
		};
	}
)( JetpackNotices );
