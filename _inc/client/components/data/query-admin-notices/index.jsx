/**
 * External dependencies
 */
import { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

/**
 * Internal dependencies
 */
import { fetchAdminNotices } from 'state/notices';

class QueryAdminNotices extends Component {
	componentWillMount() {
		this.props.fetchAdminNotices();
	}

	render() {
		return null;
	}
}

QueryAdminNotices.defaultProps = {
	fetchAdminNotices: () => {}
};

export default connect( ( state, ownProps ) => {
	return {
		fetchAdminNotices: fetchAdminNotices()
	};
}, ( dispatch ) => {
	return bindActionCreators( {
		fetchAdminNotices
	}, dispatch );
}
)( QueryAdminNotices );
