<?php

/**
* Required for class.media-extractor.php to match expected function naming convention.
*
* @param $url Can be just the $url or the whole $atts array
* @return bool|mixed The Youtube video ID via jetpack_get_youtube_id
*/

function jetpack_shortcode_get_youtube_id( $url ) {
    return jetpack_get_youtube_id( $url );
}

/**
* @param $url Can be just the $url or the whole $atts array
* @return bool|mixed The Youtube video ID
*/
function jetpack_get_youtube_id( $url ) {
	// Do we have an $atts array?  Get first att
	if ( is_array( $url ) ) {
		$url = reset( $url );
	}

	$url = youtube_sanitize_url( $url );
	$url = parse_url( $url );
	$id  = false;

	if ( ! isset( $url['query'] ) )
		return false;

	parse_str( $url['query'], $qargs );

	if ( ! isset( $qargs['v'] ) && ! isset( $qargs['list'] ) )
		return false;

	if ( isset( $qargs['list'] ) )
		$id = preg_replace( '|[^_a-z0-9-]|i', '', $qargs['list'] );

	if ( empty( $id ) )
		$id = preg_replace( '|[^_a-z0-9-]|i', '', $qargs['v'] );

	return $id;
}

if ( !function_exists( 'youtube_sanitize_url' ) ) :
/**
* Normalizes a YouTube URL to include a v= parameter and a query string free of encoded ampersands.
*
* @param string $url
* @return string The normalized URL
*/
function youtube_sanitize_url( $url ) {
	$url = trim( $url, ' "' );
	$url = trim( $url );
	$url = str_replace( array( 'youtu.be/', '/v/', '#!v=', '&amp;', '&#038;', 'playlist' ), array( 'youtu.be/?v=', '/?v=', '?v=', '&', '&', 'videoseries' ), $url );

	// Replace any extra question marks with ampersands - the result of a URL like "http://www.youtube.com/v/9FhMMmqzbD8?fs=1&hl=en_US" being passed in.
	$query_string_start = strpos( $url, "?" );

	if ( false !== $query_string_start ) {
		$url = substr( $url, 0, $query_string_start + 1 ) . str_replace( "?", "&", substr( $url, $query_string_start + 1 ) );
	}

	return $url;
}
endif;

/**
 * Merge in three string helper functions from WPCOM.
 *
 * @see WPCOM/wp-content/mu-plugins/string-helpers.php
 */
if ( ! function_exists( 'wp_startswith' ) ) :
	function wp_startswith( $haystack, $needle ) {
		return 0 === strpos( $haystack, $needle );
	}
endif;


if ( ! function_exists( 'wp_endswith' ) ) :
	function wp_endswith( $haystack, $needle ) {
		return $needle === substr( $haystack, -strlen( $needle ));
	}
endif;

if ( ! function_exists( 'wp_in' ) ) :
	function wp_in( $needle, $haystack ) {
		return false !== strpos( $haystack, $needle );
	}
endif;

/**
 * Validate user-supplied guid values against expected inputs
 *
 * @since 1.1
 * @param string $guid video identifier
 * @return bool true if passes validation test
 */
function videopress_is_valid_guid( $guid ) {
	if ( ! empty( $guid ) && strlen( $guid ) === 8 && ctype_alnum( $guid ) ) {
		return true;
	}
	return false;
}

/**
 * VideoPress helper functions.
 *
 * Used in the VideoPress module, in the VideoPress shortcode, and in Jetpack_Media_Summary.
 */

/**
 * Get details about a specific video by GUID:
 *
 * @param $guid string
 * @return object
 */
function videopress_get_video_details( $guid ) {
	if ( ! videopress_is_valid_guid( $guid ) ) {
		return new WP_Error( 'bad-guid-format', __( 'Invalid Video GUID!', 'jetpack' ) );
	}

	$version  = '1.1';
	$endpoint = sprintf( '/videos/%1$s', $guid );
	$response = wp_remote_get( sprintf( 'https://public-api.wordpress.com/rest/v%1$s%2$s', $version, $endpoint ) );
	$data     = json_decode( wp_remote_retrieve_body( $response ) );

	/**
	 * Allow functions to modify fetched video details.
	 *
	 * This filter allows third-party code to modify the return data
	 * about a given video.  It may involve swapping some data out or
	 * adding new parameters.
	 *
	 * @since 4.0.0
	 *
	 * @param object $data The data returned by the WPCOM API. See: https://developer.wordpress.com/docs/api/1.1/get/videos/%24guid/
	 * @param string $guid The GUID of the VideoPress video in question.
	 */
	return apply_filters( 'videopress_get_video_details', $data, $guid );
}


/**
 * Get an attachment ID given a URL.
 *
 * Modified from http://wpscholar.com/blog/get-attachment-id-from-wp-image-url/
 *
 * @todo: Add some caching in here.
 *
 * @param string $url
 *
 * @return int|bool Attachment ID on success, false on failure
 */
function videopress_get_attachment_id_by_url( $url ) {
	$wp_upload_dir = wp_upload_dir();
	// Strip out protocols, so it doesn't fail because searching for http: in https: dir.
	$dir = set_url_scheme( trailingslashit( $wp_upload_dir['baseurl'] ), 'relative' );

	// Is URL in uploads directory?
	if ( false !== strpos( $url, $dir ) ) {

		$file = basename( $url );

		$query_args = array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'fields'      => 'ids',
			'meta_query'  => array(
				array(
					'key'     => '_wp_attachment_metadata',
					'compare' => 'LIKE',
					'value'   => $file,
				),
			)
		);

		$query = new WP_Query( $query_args );

		if ( $query->have_posts() ) {
			foreach ( $query->posts as $attachment_id ) {
				$meta          = wp_get_attachment_metadata( $attachment_id );
				$original_file = basename( $meta['file'] );
				$cropped_files = wp_list_pluck( $meta['sizes'], 'file' );

				if ( $original_file === $file || in_array( $file, $cropped_files ) ) {
					return (int) $attachment_id;
				}
			}
		}

	}
	return false;
}
