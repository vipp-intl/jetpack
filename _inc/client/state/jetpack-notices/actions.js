/**
 * Internal dependencies
 */
import {
	JETPACK_NOTICES_FETCH,
	JETPACK_NOTICES_FETCH_FAIL,
	JETPACK_NOTICES_FETCH_SUCCESS
} from 'state/action-types';
import restApi from 'rest-api';

export const fetchJetpackNotices = () => {
	return ( dispatch ) => {
		dispatch( {
			type: JETPACK_NOTICES_FETCH
		} );
		return restApi.fetchJetpackNotices().then( jetpackNotices => {
			dispatch( {
				type: JETPACK_NOTICES_FETCH_SUCCESS,
				jetpackNotices: jetpackNotices,
				success: true
			} );
		} )['catch']( error => {
			dispatch( {
				type: JETPACK_NOTICES_FETCH_FAIL,
				error: error
			} );
		} );
	}
}
