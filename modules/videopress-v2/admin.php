<?php

/**
 * Things that still need doing:
 *  - Is there a way to use a query arg to hide the share link?  That'd be useful.
 *  - Load in the JS to resize the iframe to set the height automagically and such.
 *  - The secondary extension for `wpvideo` works, but on saving changes the shortcode to `videopress` -- nbd.
 *  - Should we handle other display methods apart from iframe in the editor?
 */

/**
 * WordPress Editor Views
 */
function videopress_editor_view_js_templates() {
	if ( ! isset( get_current_screen()->id ) || get_current_screen()->base != 'post' ) {
		return;
	}

	/**
	 * This template uses the following parameters, and displays the video as an iframe:
	 *  - data.guid     // The guid of the video.
	 *  - data.width    // The width of the iframe.
	 *  - data.height   // The height of the iframe.
	 *  - data.urlargs  // Arguments serialized into a get string.
	 *
	 * In addition, the calling script will need to ensure that the following
	 * JS file is added to the header of the editor iframe:
	 *  - https://s0.wp.com/wp-content/plugins/video/assets/js/next/videopress-iframe.js
	 */
	?>
	<script type="text/html" id="tmpl-videopress_iframe_vnext">
		<div class="tmpl-videopress_iframe_next">
			<iframe style="display: block;" width="{{ data.width }}" height="{{ data.height }}" src="https://videopress.com/embed/{{ data.guid }}?{{ data.urlargs }}" frameborder='0' allowfullscreen></iframe>
		</div>
	</script>
	<?php
}
add_action( 'admin_print_footer_scripts', 'videopress_editor_view_js_templates' );

/**
 * WordPress Shortcode Editor View JS Code
 *
 * For convenience and readability, this is printed out in the
 * footer, but ideally should be enqueued seperately.
 */
function videopress_editor_view_footer_scripts() {
	global $content_width;
	if ( ! isset( get_current_screen()->id ) || get_current_screen()->base != 'post' ) {
		return;
	}
	?>
	<script>
		/* global tinyMCE, console */
		(function( $, wp ){
			wp.mce = wp.mce || {};
			wp.mce.videopress_wp_view_renderer = {
				shortcode_string : 'videopress',
				shortcode_data : {},
				template       : wp.template( 'videopress_iframe_vnext' ),
				getContent     : function() {
					var urlargs = 'for=<?php echo esc_js( parse_url( home_url(), PHP_URL_HOST ) ); ?>',
						named = this.shortcode.attrs.named,
						options, key, width;

					for ( key in named ) {
						switch ( key ) {
							case 'at' :
								if ( parseInt( named[ key ], 10 ) ) {
									urlargs += '&' + key + '=' + parseInt( named[ key ], 10 );
								} // Else omit, as it's the default.
								break;
							case 'permalink' :
								if ( 'false' === named[ key ] ) {
									urlargs += '&' + key + '=0';
								} // Else omit, as it's the default.
								break;
							case 'hd' :
							case 'loop' :
							case 'autoplay' :
								if ( 'true' === named[ key ] ) {
									urlargs += '&' + key + '=1';
								} // Else omit, as it's the default.
								break;
							default:
								// Unknown parameters?  Ditch it!
								break;
						}
					}

					options = {
						width   : <?php echo esc_js( $content_width ); ?>,
						height  : <?php echo esc_js( intval( $content_width * 0.5625 ) ); /* 0.5625 = 9/16 -- a 16:9 HD aspect ratio */ ?>,
						guid    : this.shortcode.attrs.numeric[0],
						urlargs : urlargs
					};

					if ( typeof named.w !== 'undefined' ) {
						width = parseInt( named.w, 10 );
						if ( width > 60 && width < <?php echo esc_js( $content_width ); ?> ) {
							options.width  = width;
							options.height = parseInt( width * 0.5625, 10 );
						}
					}

					return this.template( options );
				},
				edit: function( data, update ) {
					var shortcode_data = wp.shortcode.next( this.shortcode_string, data ),
						values        = shortcode_data.shortcode.attrs.named;

					values.guid = shortcode_data.shortcode.attrs.numeric[0];

					wp.mce.videopress_wp_view_renderer.popupwindow( tinyMCE.activeEditor, values );
				},
				popupwindow: function( editor, values, onsubmit_callback ){
					var renderer = this,
						key;

					/**
					 * Populate the defaults.
					 */
					values = $.extend( {
						w         : '',
						at        : 0,
						permalink : false,
						hd        : true,
						loop      : true,
						freedom   : true,
						autoplay  : true,
						flashonly : true
					}, values );

					/**
					 * Set up a fallback onsubmit callback handler.
					 *
					 * A custom one can be provided as the third argument if desired.
					 */
					if ( typeof onsubmit_callback !== 'function' ) {
						onsubmit_callback = function( e ) {
							var s = '[' + renderer.shortcode_string,
								i;
							for ( i in e.data ) {
								switch( i ) {
									case 'guid' :
										s += ' ' + e.data.guid;
										break;
									case 'w' :
									case 'at' :
										if ( parseInt( e.data[ i ], 10 ) ) {
											s += ' ' + i + '="' + parseInt( e.data[ i ], 10 ) + '"';
										} // Else omit, as it's the default.
										break;
									case 'permalink' :
										if ( ! e.data[ i ] ) {
											s += ' ' + i + '="false"';
										} // Else omit, as it's the default.
										break;
									case 'hd' :
									case 'loop' :
									case 'freedom' :
									case 'autoplay' :
									case 'flashonly' :
										if ( e.data[ i ] ) {
											s += ' ' + i + '="true"';
										} // Else omit, as it's the default.
										break;
									default:
										// Unknown parameters?  Ditch it!
										break;
								}
							}
							s += ']';
							editor.insertContent( s );
						};
					}

					/**
					 * Cast the checked options to true or false as needed.
					 */
					for ( key in values ) {
						switch ( key ) {
							case 'permalink' :
								if ( $.inArray( values[ key ], [ false, 'false', '0' ] ) ) {
									values[ key ] = false;
								} else {
									values[ key ] = true;
								}
								break;
							case 'hd' :
							case 'loop' :
							case 'freedom' :
							case 'autoplay' :
							case 'flashonly' :
								if ( $.inArray( values[ key ], [ true, 'true', '1' ] ) ) {
									values[ key ] = true;
								} else {
									values[ key ] = false;
								}
								break;
							default:
								break;
						}
					}

					/**
					 * Declare the fields that will show in the popup when editing the shortcode.
					 */
					editor.windowManager.open( {
						title : '<?php echo esc_js( __( 'VideoPress Shortcode', 'jetpack' ) ); ?>', // This should be internationalized via wp_localize_script
						body  : [
							{
								type  : 'textbox',
								name  : 'guid',
								label : '<?php echo esc_js( __( 'Video GUID', 'jetpack' ) ); ?>',
								value : values.guid
							},
							{
								type  : 'textbox',
								name  : 'w',
								label : '<?php echo esc_js( __( 'Width (in pixels)', 'jetpack' ) ); ?>',
								value : values.w
							},
							{
								type  : 'textbox',
								name  : 'at',
								label : '<?php echo esc_js( __( 'Start how many seconds in?', 'jetpack' ) ); ?>',
								value : values.at
							},
							{
								type    : 'checkbox',
								name    : 'hd',
								label   : '<?php echo esc_js( __( 'Default to High Definition version?', 'jetpack' ) ); ?>',
								checked : values.hd
							},
							{
								type    : 'checkbox',
								name    : 'permalink',
								label   : '<?php echo esc_js( __( 'Link the video title to the video\'s URL on VideoPress.com?', 'jetpack' ) ); ?>',
								checked : values.permalink
							},
							{
								type    : 'checkbox',
								name    : 'autoplay',
								label   : '<?php echo esc_js( __( 'Autoplay video on load?', 'jetpack' ) ); ?>',
								checked : values.autoplay
							},
							{
								type    : 'checkbox',
								name    : 'loop',
								label   : '<?php echo esc_js( __( 'Loop playback indefinitely?', 'jetpack' ) ); ?>',
								checked : values.loop
							},
							{
								type    : 'checkbox',
								name    : 'freedom',
								label   : '<?php echo esc_js( __( 'Use only Open Source codecs? (this may degrade performance)', 'jetpack' ) ); ?>',
								checked : values.freedom
							},
							{
								type    : 'checkbox',
								name    : 'flashonly',
								label   : '<?php echo esc_js( __( 'Use the legacy flash player? (not recommended)', 'jetpack' ) ); ?>',
								checked : values.flashonly
							}
						],
						onsubmit : onsubmit_callback
					} );
				}
			};
			wp.mce.views.register( 'videopress', wp.mce.videopress_wp_view_renderer );

			// Extend the videopress one to also handle `wpvideo` instances.
			wp.mce.wpvideo_wp_view_renderer = _.extend( {}, wp.mce.videopress_wp_view_renderer, {
				shortcode_string : 'wpvideo'
			});
			wp.mce.views.register( 'wpvideo', wp.mce.wpvideo_wp_view_renderer );
		}( jQuery, wp ));
	</script>
	<?php
}
add_action( 'admin_print_footer_scripts', 'videopress_editor_view_footer_scripts' );

/**
 * Similar to `media_sideload_image` -- but returns an ID.
 *
 * @param $url
 * @param $attachment_id
 *
 * @return int|mixed|object|WP_Error
 */
function videopress_download_poster_image( $url, $attachment_id ) {
	// Set variables for storage, fix file filename for query strings.
	preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $url, $matches );
	if ( ! $matches ) {
		return new WP_Error( 'image_sideload_failed', __( 'Invalid image URL' ) );
	}

	$file_array = array();
	$file_array['name']     = basename( $matches[0] );
	$file_array['tmp_name'] = download_url( $url );

	// If error storing temporarily, return the error.
	if ( is_wp_error( $file_array['tmp_name'] ) ) {
		return $file_array['tmp_name'];
	}

	// Do the validation and storage stuff.
	$thumbnail_id = media_handle_sideload( $file_array, $attachment_id, null );

	// Flag it as poster image, so we can exclude it from display.
	update_post_meta( $thumbnail_id, 'videopress_poster_image', 1 );

	return $thumbnail_id;
}

/**
 * Creates a local media library item of a remote VideoPress video.
 *
 * @param $guid
 * @param int $parent_id
 *
 * @return int|object
 */
function create_local_media_library_for_videopress_guid( $guid, $parent_id = 0 ) {
	$vp_data = videopress_get_video_details( $guid );
	if ( ! $vp_data || is_wp_error( $vp_data ) ) {
		return $vp_data;
	}

	$args = array(
		'post_date'      => $vp_data->upload_date,
		'post_title'     => wp_kses( $vp_data->title, array() ),
		'post_content'   => wp_kses( $vp_data->description, array() ),
		'post_mime_type' => 'video/videopress',
		'guid'           => sprintf( 'https://videopress.com/embed/%s', $guid ),
	);

	$attachment_id = wp_insert_attachment( $args, null, $parent_id );

	if ( ! is_wp_error( $attachment_id ) ) {
		update_post_meta( $attachment_id, 'videopress_guid', $guid );
		wp_update_attachment_metadata( $attachment_id, array(
			'width'  => $vp_data->width,
			'height' => $vp_data->height,
		) );

		$thumbnail_id = videopress_download_poster_image( $vp_data->poster, $attachment_id );
		update_post_meta( $attachment_id, '_thumbnail_id', $thumbnail_id );
	}

	return $attachment_id;
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {

	/**
	 * Manage and import VideoPress videos.
	 */
	class VideoPress_CLI extends WP_CLI_Command {

		/**
		 * Import a VideoPress Video
		 *
		 * ## OPTIONS
		 *
		 * <guid>: Import the video with the specified guid
		 *
		 * ## EXAMPLES
		 *
		 * wp videopress import kUJmAcSf
		 *
		 */
		public function import( $args ) {
			$guid = $args[0];

			$attachment_id = create_local_media_library_for_videopress_guid( $guid );

			if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
				WP_CLI::success( sprintf( __( 'The video has been imported as Attachment ID %d', 'jetpack' ), $attachment_id ) );
			} else {
				WP_CLI::error( __( 'An error has been encountered.', 'jetpack' ) );
			}
		}
	}

	WP_CLI::add_command( 'videopress', 'VideoPress_CLI' );
}

/*
 * This is the chunk that handles overriding core media stuff so VideoPress can display natively.
 */

/**
 * Media Grid:
 * Filter out any videopress video posters that we've downloaded,
 * so that they don't seem to display twice.
 */
add_filter( 'ajax_query_attachments_args', 'videopress_ajax_query_attachments_args' );
function videopress_ajax_query_attachments_args( $args ) {
	$meta_query = array(
		array(
			'key'     => 'videopress_poster_image',
			'compare' => 'NOT EXISTS',
		),
	);

	// If there was already a meta query, let's AND it via
	// nesting it with our new one. No need to specify the
	// relation, as it defaults to AND.
	if ( ! empty( $args['meta_query'] ) ) {
		$meta_query[] = $args['meta_query'];
	}
	$args['meta_query'] = $meta_query;

	return $args;
}

/**
 * Media List:
 * Do the same as ^^ but for the list view.
 */
add_action( 'pre_get_posts', 'videopress_media_list_table_query' );
function videopress_media_list_table_query( $query ) {
	if ( is_admin() && $query->is_main_query() && ( 'upload' === get_current_screen()->id ) ) {
		$meta_query = array(
			array(
				'key'     => 'videopress_poster_image',
				'compare' => 'NOT EXISTS',
			),
		);

		if ( $old_meta_query = $query->get( 'meta_query' ) ) {
			$meta_query[] = $old_meta_query;
		}

		$query->set( 'meta_query', $meta_query );
	}
}

/**
 * Make sure that any Video that has a VideoPress GUID passes that data back.
 */
add_filter( 'wp_prepare_attachment_for_js', 'videopress_prepare_attachment_for_js' );
function videopress_prepare_attachment_for_js( $post ) {
	if ( 'video' === $post['type'] ) {
		$guid = get_post_meta( $post['id'], 'videopress_guid' );
		if ( $guid ) {
			$post['videopress_guid'] = $guid;
		}
	}
	return $post;
}

/**
 * Wherever the Media Modal is deployed, also deploy our overrides.
 */
add_action( 'wp_enqueue_media', 'add_videopress_media_overrides' );
function add_videopress_media_overrides() {
	add_action( 'admin_print_footer_scripts', 'videopress_override_media_templates', 11 );
}

/**
 * Our video overrides!
 *
 * We have a template for the iframe to get injected.
 */
function videopress_override_media_templates(){
	?>
	<script type="text/html" id="tmpl-videopress_iframe_vnext">
		<iframe style="display: block; max-width: 100%;" width="{{ data.width }}" height="{{ data.height }}" src="https://videopress.com/embed/{{ data.guid }}?{{ data.urlargs }}" frameborder='0' allowfullscreen></iframe>
	</script>
	<script>
		(function( media ){
			if ( typeof media.view.Attachment.Details.TwoColumn !== 'undefined' ) {
				var TwoColumn = media.view.Attachment.Details.TwoColumn,
					old_render = TwoColumn.prototype.render,
					vp_template = wp.template('videopress_iframe_vnext');

				TwoColumn.prototype.render = function () {
					// Have the original renderer run first.
					old_render.apply(this, arguments);

					// Now our stuff!
					if ('video' === this.model.get('type')) {
						if (this.model.get('videopress_guid')) {
							this.$('.attachment-media-view .thumbnail-video').html(vp_template({
								guid: this.model.get('videopress_guid'),
								width: this.model.get('width'),
								height: this.model.get('height')
							}));
						}
					}
				};
			} else { console.log( 'media.view.Attachment.Details.TwoColumn undefined' ); }

			if ( typeof media.video !== 'undefined' ) {
				var old_video_shortcode = media.video.shortcode.prototype;

				media.video.defaults = _.extend(media.video.defaults, {
					videopress_guid: ''
				});

				media.video.shortcode.prototype = function (model) {
					model.videopress_guid = 'FOOBAR';
					console.log(model);
					return old_video_shortcode(model);
				};
			} else { console.log( 'media.video undefined' ); }

		})( wp.media );
	</script>
	<?php
}