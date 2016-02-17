<?php

class Jetpack_Admin_Data {

	function __construct() {
		if ( ! Jetpack::is_active() ) {
			return;
		}
	}

	/*
	 * Data for displaying in Protect section of At A Glance
	 */
	public static function glance_site_security_protect_state() {
		if ( ! Jetpack::is_module_active( 'protect' ) ) {
			return array(
				'title'   => 'Protect',
				'size'    => 'large',
				'state'   => 'inactive',
				'data'    => null,
				'message' => __( 'Please activate Protect', 'jetpack' )
			);
		}

		return array(
			'title'   => 'Protect',
			'size'    => 'large',
			'state'   => 'active',
			'data'    => get_site_option( 'jetpack_protect_blocked_attempts' ),
			'message' => __( 'Malicious attacks blocked.', 'jetpack' )
		);
	}

	/*
	 * Data for displaying in Scan section of At A Glance
	 */
	public static function glance_site_security_scan_state() {
		return array(
			'title'   => __( 'Security Scan', 'jetpack' ),
			'size'    => 'small',
			'state'   => 'inactive',
			'data'    => 'No Threats Found',
			'message' => __( 'This is a placeholder until we get live data', 'jetpack' )
		);
	}

	/*
	 * Data for displaying in Monitor section of At A Glance
	 */
	public static function glance_site_security_monitor_state() {
		if ( ! Jetpack::is_module_active( 'monitor' ) ) {
			return array(
				'title'   => __( 'Site Monitoring', 'jetpack' ),
				'size'    => 'small',
				'state'   => 'inactive',
				'data'    => null,
				'message' => __( 'Please activate Monitor', 'jetpack' )
			);
		}

		// Calculate "Days Since" last downtime.
		$monitor       = new Jetpack_Monitor();
		$last_downtime = $monitor->monitor_get_last_downtime();
		if ( is_wp_error( $last_downtime ) ) {
			$time_since = $last_downtime->get_error_message();
		} else {
			$time_since = human_time_diff( strtotime( $last_downtime ), strtotime( 'now' ) );
		}

		return array(
			'title'   => __( 'Site Monitoring', 'jetpack' ),
			'size'    => 'small',
			'state'   => 'active',
			'data'    => esc_html( $time_since ),
			'message' => __( 'without downtime.', 'jetpack' )
		);
	}

	/*
	 * Data for displaying in Monitor section of At A Glance
	 */
	public static function glance_site_health_akismet_state() {
		if ( ! is_plugin_active( 'akismet/akismet.php' ) ) {
			return array(
				'title'   => __( 'Anti-spam', 'jetpack' ),
				'size'    => 'large',
				'state'   => 'inactive',
				'data'    => null,
				'message' => __( 'Please activate Akismet', 'jetpack' )
			);
		}

		$akismet_key = Akismet::verify_key( Akismet::get_api_key() );
		if ( ! $akismet_key || 'invalid' === $akismet_key || 'failed' === $akismet_key ) {
			return array(
				'title'   => __( 'Anti-spam', 'jetpack' ),
				'size'    => 'large',
				'state'   => 'active-caution',
				'data'    => null,
				'message' => __( 'Something is wrong with your Anti-spam key.', 'jetpack' )
			);
		}

		$count_data = Akismet_Admin::get_stats( Akismet::get_api_key() );

		return array(
			'title'   => __( 'Anti-spam', 'jetpack' ),
			'size'    => 'large',
			'state'   => 'active-caution',
			'data'    => esc_html( $count_data['all']->spam ),
			'message' => __( 'Spam comments thwarted.', 'jetpack' )
		);
	}

	/*
	 * Data for displaying Backups in At A Glance
	 */
	public static function glance_site_health_backup_state() {
		return array(
			'title'   => __( 'Site Backups', 'jetpack' ),
			'size'    => 'small',
			'state'   => 'active-danger',
			'data'    => 'Last Backup Failed',
			'message' => __( 'This is a placeholder until we get live data', 'jetpack' )
		);
	}

	/*
	 * Data for displaying plugin updates in At A Glance
	 */
	public static function glance_site_health_plugin_updates_state() {
		$plugin_updates = count( get_plugin_updates() );

		if ( empty( $plugin_updates ) ) {
			return array(
				'title'   => __( 'Plugin Updates', 'jetpack' ),
				'size'    => 'small',
				'state'   => 'active',
				'data'    => null,
				'message' => __( 'All plugins up to date!', 'jetpack' )
			);
		} else {
			return array(
				'title'   => __( 'Plugin Updates', 'jetpack' ),
				'size'    => 'small',
				'state'   => 'active-caution',
				'data'    => esc_html( $plugin_updates ),
				'message' => __( 'Plugins need updating', 'jetpack' )
			);
		}
	}

	/*
	 * Data for displaying Image Performance (photon)
	 */
	public static function glance_traffic_tools_img_performance_state() {
		if ( ! Jetpack::is_module_active( 'photon' ) ) {
			return array(
				'title'   => __( 'Image Performance', 'jetpack' ),
				'size'    => 'small',
				'state'   => 'inactive',
				'data'    => null,
				'message' => __( 'Please activate Photon', 'jetpack' )
			);
		}

		return array(
			'title'   => __( 'Image Performance', 'jetpack' ),
			'size'    => 'small',
			'state'   => 'active',
			'data'    => null,
			'message' => __( 'Photon is on and enhancing image performance.', 'jetpack' )
		);
	}

	/*
	 * Data for displaying Site Verification
	 */
	public static function glance_traffic_tools_site_verification_state() {
		if ( ! Jetpack::is_module_active( 'verification-tools' ) ) {
			return array(
				'title'   => __( 'Site Verification', 'jetpack' ),
				'size'    => 'small',
				'state'   => 'inactive',
				'data'    => null,
				'message' => __( 'Please activate Site Verification.', 'jetpack' )
			);
		}

		$verification_services_codes = get_option( 'verification_services_codes' );

		if ( ! $verification_services_codes ) {
			return array(
				'title'   => __( 'Site Verification', 'jetpack' ),
				'size'    => 'small',
				'state'   => 'active-caution',
				'data'    => null,
				'message' => __( 'Verify your site with Google, Bing, and Pinterest for better indexing and ranking!', 'jetpack' )
			);
		}

		$services_with_code = '';
		foreach ( $verification_services_codes as $service => $code ) {
			$services_with_code .= $service . ', ';
		}

		// format the list string
		$services_with_code = rtrim( $services_with_code, ', ' );
		$last_comma         = strrpos( $services_with_code, ',' );
		$services_with_code = substr_replace( $services_with_code, __( ' and', 'jetpack' ), $last_comma, 1 );

		return array(
			'title'   => __( 'Site Verification', 'jetpack' ),
			'size'    => 'small',
			'state'   => 'active',
			'data'    => null,
			'message' => __( 'Your site is verified with ', 'jetpack' ) . esc_html( $services_with_code )
		);
	}

}