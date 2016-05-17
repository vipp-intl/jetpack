/**
 * External dependencies
 */
import { combineReducers } from 'redux';
import assign from 'lodash/assign';

/**
 * Internal dependencies
 */
import {
	ADMIN_NOTICES_FETCH,
	ADMIN_NOTICES_FETCH_FAIL,
	ADMIN_NOTICES_FETCH_SUCCESS
} from 'state/action-types';
import restApi from 'rest-api';

const adminNotices = ( state = false , action ) => {
	switch ( action.type ) {
		case ADMIN_NOTICES_FETCH_SUCCESS:
			return action.adminNotices;

		default:
			return state;
	}
};

export const reducer = combineReducers( {
	adminNotices
} );

/**
 * Returns any Jetpack notice hooked onto 'jetpack_notices' in PHP
 *
 * @param  {Object} state Global state tree
 * @return {bool|string}  False if no notice, string if there is.
 */
export function getAdminNotices( state ) {
	return state.jetpack.notices.adminNotices;
}
