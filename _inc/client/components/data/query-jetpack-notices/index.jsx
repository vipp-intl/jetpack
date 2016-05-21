/**
 * External dependencies
 */
import { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

/**
 * Internal dependencies
 */
import { fetchJetpackNotices } from 'state/jetpack-notices';

class QueryJetpackNotices extends Component {
	componentWillMount() {
		this.props.fetchJetpackNotices();
	}

	render() {
		return null;
	}
}

QueryJetpackNotices.defaultProps = {
	fetchJetpackNotices: () => {}
};

export default connect( ( state, ownProps ) => {
	return {
		fetchJetpackNotices: fetchJetpackNotices()
	};
}, ( dispatch ) => {
	return bindActionCreators( {
		fetchJetpackNotices
	}, dispatch );
}
)( QueryJetpackNotices );
