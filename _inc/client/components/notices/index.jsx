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
	getAdminNotices as _getAdminNotices
} from 'state/notices';
import QueryAdminNotices from 'components/data/query-admin-notices';

const Notices = React.createClass( {
	displayName: 'Notices',

	renderMessage: function( message ) {
		// Rationale behind returning an object and not just the string
		// https://facebook.github.io/react/tips/dangerously-set-inner-html.html
		return { __html: message };
	},

	maybeShowAdminNotice: function() {
		const notices = this.props.adminNotices( this.props );

		if ( 'success' === notices.code ) {
			return( <div dangerouslySetInnerHTML={ this.renderMessage( notices.message ) } /> );
		}
	},

	render() {
		return (
			<div>
				<QueryAdminNotices />
				{ this.maybeShowAdminNotice() }
			</div>
		);
	}
} );

export default connect(
	state => {
		return {
			adminNotices: () => _getAdminNotices( state )
		};
	}
)( Notices );
