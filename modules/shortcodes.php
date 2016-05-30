<?php

/**
 * Module Name: Shortcode Embeds
 * Module Description: Embed content from YouTube, Vimeo, SlideShare, and more, no coding necessary.
 * Sort Order: 3
 * First Introduced: 1.1
 * Major Changes In: 1.2
 * Requires Connection: No
 * Auto Activate: Yes
 * Module Tags: Photos and Videos, Social, Writing, Appearance
 * Additional Search Queries: shortcodes, shortcode, embeds, media, bandcamp, blip.tv, dailymotion, digg, facebook, flickr, google calendars, google maps, google+, polldaddy, recipe, recipes, scribd, slideshare, slideshow, slideshows, soundcloud, ted, twitter, vimeo, vine, youtube
 */

/**
 * Transforms the $atts array into a string that the old functions expected
 *
 * The old way was:
 * [shortcode a=1&b=2&c=3] or [shortcode=1]
 * This is parsed as array( a => '1&b=2&c=3' ) and array( 0 => '=1' ), which is useless
 *
 * @param Array $params
 * @param Bool $old_format_support true if [shortcode=foo] format is possible.
 * @return String $params
 */
function shortcode_new_to_old_params( $params, $old_format_support = false ) {
	$str = '';

	if ( $old_format_support && isset( $params[0] ) ) {
		$str = ltrim( $params[0], '=' );
	} elseif ( is_array( $params ) ) {
		foreach ( array_keys( $params ) as $key ) {
			if ( ! is_numeric( $key ) )
				$str = $key . '=' . $params[$key];
		}
	}

	return str_replace( array( '&amp;', '&#038;' ), '&', $str );
}

function jetpack_load_shortcodes() {
	global $wp_version;

	$shortcode_includes = array();

	foreach ( Jetpack::glob_php( dirname( __FILE__ ) . '/shortcodes' ) as $file ) {
		$shortcode_includes[] = $file;
	}

/**
 * This filter allows other plugins to override which shortcodes Jetpack loads.
 *
 * @module shortcodes
 *
 * @since 2.2.1
 *
 * @param array $shortcode_includes An array of which shortcodes to include.
 */
	$shortcode_includes = apply_filters( 'jetpack_shortcodes_to_include', $shortcode_includes );

	foreach ( $shortcode_includes as $include ) {
		if ( version_compare( $wp_version, '3.6-z', '>=' ) && stristr( $include, 'audio.php' ) ) {
			continue;
		}

		include $include;
	}
}

/**
 * Runs preg_replace so that replacements don't happen within open tags.  
 * Parameters are the same as preg_replace, with an added optional search param for improved performance
 *
 * @param String $pattern
 * @param String $replacement
 * @param String $content
 * @param String $search
 * @return String $content
 */
function jetpack_preg_replace_outside_tags( $pattern, $replacement, $content, $search = null ) {
	if( ! function_exists( 'wp_html_split' ) ) {
		return $content;
	}

	if ( $search && false === strpos( $content, $search ) ) {
		return $content;
	}
	
	$textarr = wp_html_split( $content );
	unset( $content );
	foreach( $textarr as &$element ) {
	    if ( '' === $element || '<' === $element{0} )
	        continue;
	    $element = preg_replace( $pattern, $replacement, $element );
	}
	
	return join( $textarr );
}

/**
 * Runs preg_replace_callback so that replacements don't happen within open tags.  
 * Parameters are the same as preg_replace, with an added optional search param for improved performance
 *
 * @param String $pattern
 * @param String $replacement
 * @param String $content
 * @param String $search
 * @return String $content
 */
function jetpack_preg_replace_callback_outside_tags( $pattern, $callback, $content, $search = null ) {
	if( ! function_exists( 'wp_html_split' ) ) {
		return $content;
	}

	if ( $search && false === strpos( $content, $search ) ) {
		return $content;
	}
	
	$textarr = wp_html_split( $content );
	unset( $content );
	foreach( $textarr as &$element ) {
	    if ( '' === $element || '<' === $element{0} )
	        continue;
	    $element = preg_replace_callback( $pattern, $callback, $element );
	}
	
	return join( $textarr );
}

global $wp_version;

if ( version_compare( $wp_version, '3.6-z', '>=' ) ) {
	add_filter( 'shortcode_atts_audio', 'jetpack_audio_atts_handler', 10, 3 );

	function jetpack_audio_atts_handler( $out, $pairs, $atts ) {
		if( isset( $atts[0] ) )
			$out['src'] = $atts[0];

		return $out;
	}

	function jetpack_shortcode_get_audio_id( $atts ) {
		if ( isset( $atts[ 0 ] ) )
			return $atts[ 0 ];
		else
			return 0;
	}
}

if ( ! function_exists( 'jetpack_shortcode_get_wpvideo_id' ) ) {
	function jetpack_shortcode_get_wpvideo_id( $atts ) {
		if ( isset( $atts[ 0 ] ) )
			return $atts[ 0 ];
		else
			return 0;
	}
}

jetpack_load_shortcodes();

/**
 * Replaces plain-text URLs to Vimeo videos with Vimeo embeds.
 * Or plain text URLs https://vimeo.com/1234 | vimeo.com/1234 | //vimeo.com/1234
 * Links are left intact.
 *
 * @since 4.0.4
 *
 * @param string $content HTML content
 * @return string The content with embeds instead of URLs
 */
function jetpack_autoembed_in_comments( $content ) {
	global $wp_embed;

	$content = $wp_embed->autoembed( $content );

	return $content;
}

/**
 * Replaces shortcodes with Vimeo embeds.
 * Covers shortcode usage [vimeo 1234] | [vimeo https://vimeo.com/1234] | [vimeo http://vimeo.com/1234]
 *
 * @since 4.0.4
 *
 * @param string $content HTML content
 * @return string The content with embeds instead of URLs
 */
function jetpack_shortcode_in_comments( $content ) {
	global $shortcode_tags;

	// Backup current registered shortcodes
	$orig_shortcode_tags = $shortcode_tags;

	$whitelist = array( 'vimeo' );

	// We're only going to execute the whitelisted shortcodes
	$shortcode_tags = array_intersect_key( $shortcode_tags, array_flip( $whitelist ) );

	$content = do_shortcode( $content );

	// Put the original shortcodes back
	$shortcode_tags = $orig_shortcode_tags;

	return $content;
}

/**
 * Wrap core WordPress embeds in a div to allow easier targeting via CSS.
 *
 * @since 4.0.4
 *
 * @param string $html HTML that will be embedded.
 * @param object $data Oembed Provider.
 *
 * @return string
 */
function jetpack_wrap_embeds_in_a_div( $html, $data ) {
	if ( ! empty( $data->provider_name ) && ( 'video' == $data->type || 'rich' == $data->type ) ) {
		$html = '<div class="embed-' . esc_attr( strtolower( sanitize_html_class( $data->provider_name ) ) ) . '">' . $html . '</div>';
	}
	return $html;
}

/** This filter is documented in modules/shortcodes/youtube.php */
if ( apply_filters( 'jetpack_comments_allow_oembed', get_option('embed_autourls') ) ) {
	// We attach wp_kses_post to comment_text in default-filters.php with priority of 10 anyway, so the iframe gets filtered out.
	if ( ! is_admin() ) {
		// Higher priority because we need it before auto-link and autop get to it
		add_filter( 'comment_text', 'jetpack_autoembed_in_comments', 1 );
		add_filter( 'comment_text', 'jetpack_shortcode_in_comments', 8 );
		add_filter( 'oembed_dataparse', 'jetpack_wrap_embeds_in_a_div', 5, 2 );
	}
}