<?php

require_once 'class.jetpack-sync-posts.php';
require_once 'class.jetpack-sync-comments.php';
require_once 'class.jetpack-sync-options.php';
require_once 'class.jetpack-sync-network-options.php';
require_once 'class.jetpack-sync-functions.php';
require_once 'class.jetpack-sync-constants.php';
require_once 'class.jetpack-sync-users.php';
require_once 'class.jetpack-sync-updates.php';
require_once 'class.jetpack-sync-reindex.php';
if ( is_multisite() ) {
	require_once 'class.jetpack-sync-network-options.php';
}

class Jetpack_Sync {
	static $do_shutdown = false;

	static function init() {
		Jetpack_Sync_Posts::init();
		Jetpack_Sync_Comments::init();
		Jetpack_Sync_Options::init();
		Jetpack_Sync_Users::init();
		Jetpack_Sync_Network_Options::init();
		Jetpack_Sync_Updates::init();
		Jetpack_Sync_Reindex::init();
	}

	static function schedule_shutdown() {
		if ( ! self::$do_shutdown ) {
			self::$do_shutdown = true;
			if ( function_exists( 'ignore_user_abort' ) ) {
				ignore_user_abort( true );
			}
			add_action( 'shutdown', array( __CLASS__, 'on_shutdown'), 9  );
		}
	}

	static function on_shutdown() {
		if ( ! self::should_sync() ) {
			return;
		}
		Jetpack::xmlrpc_async_call( 'jetpack.sync_v2', self::get_data_to_sync() );
	}

	static function should_sync() {
		if ( defined( 'WP_IMPORTING' ) && WP_IMPORTING ) {
			return false;
		}
		if ( Jetpack::is_development_mode() || Jetpack::is_staging_site() ) {
			return false;
		}

		return true;
	}

	static function get_data_to_sync() {
		$send['options']         = Jetpack_Sync_Options::get_to_sync();
		$send['options_delete']  = Jetpack_Sync_Options::get_to_delete();
		$send['constants']       = self::sync_if_has_changed( Jetpack_Sync_Constants::$check_sum_id, Jetpack_Sync_Constants::get_all() );
		$send['posts']           = Jetpack_Sync_Posts::posts_to_sync();
		$send['posts_delete']    = Jetpack_Sync_Posts::posts_to_delete();
		$send['comments']        = Jetpack_Sync_Comments::comments_to_sync();
		$send['delete_comments'] = Jetpack_Sync_Comments::comments_to_delete();
		$send['updates']         = Jetpack_Sync_Updates::get_to_sync();
		if ( false === ( $do_check = get_transient( 'jetpack_sync_functions' ) ) ) {
			$send['functions'] = self::sync_if_has_changed( Jetpack_Sync_Functions::$check_sum_id, Jetpack_Sync_Functions::get_all() );
			set_transient( 'jetpack_sync_functions', true, MINUTE_IN_SECONDS );
		}
		if ( is_multisite() ) {
			$send['network_options']        = Jetpack_Sync_Network_Options::get_to_sync();
			$send['network_options_delete'] = Jetpack_Sync_Network_Options::get_to_delete();
		}
		return array_filter( $send );
	}

	static function get_all_data() {
		$send['options']   = Jetpack_Sync_Options::get_all();
		$send['constants'] = Jetpack_Sync_Constants::get_all();
		$send['functions'] = Jetpack_Sync_Functions::get_all();
		if ( is_multisite() ) {
			$send['network_options'] = Jetpack_Sync_Network_Options::get_all();
		}

		return $send;
	}

	static function on_heartbeat() {
		// still needs to be implemented
	}

	static function force_sync() {
		// when the user forces the sync to happen

	}

	static function on_upgrade() {
		// when the user gets a new version of Jetpack

	}

	static function on_connect() {
		// when the user connects jetpack ( )
		// maybe trigger force_sync
	}

	static function sync_if_has_changed( $check_sum_id, $values ) {
		$current_check_sum = self::get_check_sum( $values );
		if ( Jetpack_Options::get_option( $check_sum_id ) !== $current_check_sum ) {
			Jetpack_Options::update_option( $check_sum_id, $current_check_sum );

			return $values;
		}

		return null;
	}

	static function get_check_sum( $values ) {
		return crc32( build_query( $values ) );
	}

}

Jetpack_Sync::init();