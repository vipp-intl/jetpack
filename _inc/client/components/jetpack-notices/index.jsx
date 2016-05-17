/**
 * External dependencies
 */
import React from 'react';
import { connect } from 'react-redux';
import SimpleNotice from 'components/notice';

/**
 * Internal dependencies
 */
import {
	getJetpackNotices as _getJetpackNotices
} from 'state/jetpack-notices';
import QueryJetpackNotices from 'components/data/query-jetpack-notices';

const JetpackNotices = React.createClass( {
	displayName: 'JetpackNotices',

	renderMessage: function( message ) {
		// Rationale behind returning an object and not just the string
		// https://facebook.github.io/react/tips/dangerously-set-inner-html.html
		return { __html: message };
	},

	renderContent: function() {
		const notices = this.props.jetpackNotices( this.props );

		if ( 'success' === notices.code ) {
			return(
				<div>
					<QueryJetpackNotices />
					<SimpleNotice
						showDismiss={ true }
					>
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
