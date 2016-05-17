/**
 * Internal dependencies
 */
import {
	ADMIN_NOTICES_FETCH,
	ADMIN_NOTICES_FETCH_FAIL,
	ADMIN_NOTICES_FETCH_SUCCESS
} from 'state/action-types';
import restApi from 'rest-api';

export const fetchAdminNotices = () => {
	return ( dispatch ) => {
		dispatch( {
			type: ADMIN_NOTICES_FETCH
		} );
		return restApi.fetchAdminNotices().then( adminNotices => {
			dispatch( {
				type: ADMIN_NOTICES_FETCH_SUCCESS,
				adminNotices: adminNotices,
				success: true
			} );
		} )['catch']( error => {
			dispatch( {
				type: ADMIN_NOTICES_FETCH_FAIL,
				error: error
			} );
		} );
	}
}
