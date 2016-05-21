/**
 * External dependencies
 */
import { combineReducers } from 'redux';
import assign from 'lodash/assign';

/**
 * Internal dependencies
 */
import {
	JETPACK_NOTICES_FETCH,
	JETPACK_NOTICES_FETCH_FAIL,
	JETPACK_NOTICES_FETCH_SUCCESS,
	DISCONNECT_SITE_SUCCESS
} from 'state/action-types';
import restApi from 'rest-api';

const status = ( state = false , action ) => {
	switch ( action.type ) {
		case JETPACK_NOTICES_FETCH_SUCCESS:
			return action.jetpackNotices;

		case DISCONNECT_SITE_SUCCESS:
			return 'disconnected';

		default:
			return state;
	}
};

export const reducer = combineReducers( {
	status
} );

/**
 * Returns any Jetpack notice hooked onto 'jetpack_notices' in PHP
 *
 * @param  {Object} state Global state tree
 * @return {bool|string}  False if no notice, string if there is.
 */
export function getJetpackNotices( state ) {
	return state.jetpack.jetpackNotices.status;
}
