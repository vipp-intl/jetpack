<div class="page-content landing">
	<?php Jetpack::init()->load_view( 'admin/network-activated-notice.php' ); ?>

	<?php
		/**
		 * Fires when a notice is displayed in the Jetpack menu.
		 *
		 * @since 3.0.0
		 */
		do_action( 'jetpack_notices' );
	?>

	<?php if ( $data['is_connected'] ) : ?>

		<div id="jp-contain">

			<ul class="jp-nav jp-nav-tabs">
				<li><a href="#glance">At a Glance</a></li>
				<li><a href="#security">Security</a></li>
				<li><a href="#health">Health</a></li>
				<li><a href="#traffic">Traffic</a></li>
				<li><a href="#more">More</a></li>
				<li><a href="#notices">Notices</a></li>
				<li><a href="#settings">Settings</a></li>
			</ul>


			<div id="jp-tab-content">
				<div id="glance">
					<?php include_once( 'glance-tmpl.php' ); ?>
				</div>
				<div id="security"></div>
				<div id="health"></div>
				<div id="traffic"></div>
				<div id="more">
					<?php include_once( 'more-tmpl.php' ); ?>
				</div>
				<div id="notices">
					<?php include_once( 'notices-tmpl.php' ); ?>
				</div>
				<div id="settings">
					<?php include_once( 'settings-tmpl.php' ); ?>
				</div>
			</div>
		</div>

		<?php if ( $data['show_jumpstart'] && 'new_connection' === Jetpack_Options::get_option( 'jumpstart' ) && current_user_can( 'jetpack_manage_modules' ) && ! Jetpack::is_development_mode() ) : ?>
			<?php include_once( 'jumpstart.php' ); ?>
		<?php endif; ?>

		<?php if ( $data['is_connected'] && ! $data['is_user_connected'] && current_user_can( 'jetpack_connect_user' ) ) : ?>
			<div class="link-button" style="width: 100%; text-align: center; margin-top: 15px;">
				<a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="download-jetpack"><?php esc_html_e( 'Link your account to WordPress.com', 'jetpack' ); ?></a>
			</div>
		<?php endif; ?>

		<?php include_once( 'support-area.php' ); ?>

</div><!-- .landing -->

	<?php else : ?>
		<div id="jump-start-area" class="j-row">
			<h1 title="<?php esc_attr_e( 'Please Connect Jetpack', 'jetpack' ); ?>"><?php esc_html_e( 'Please Connect Jetpack', 'jetpack' ); ?></h1>
			<div class="connect-btn j-col j-sm-12 j-md-12">
				<p><?php echo wp_kses( __( 'Connecting Jetpack will show you <strong>stats</strong> about your traffic, <strong>protect</strong> you from brute force attacks, <strong>speed up</strong> your images and photos, and enable other <strong>traffic and security</strong> features.', 'jetpack' ), 'jetpack' ) ?></p>
				<?php if ( ! $data['is_connected'] && current_user_can( 'jetpack_connect' ) ) : ?>
					<a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="download-jetpack"><?php esc_html_e( 'Connect Jetpack', 'jetpack' ); ?></a>
				<?php elseif ( $data['is_connected'] && ! $data['is_user_connected'] && current_user_can( 'jetpack_connect_user' ) ) : ?>
					<a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="download-jetpack"><?php esc_html_e( 'Connect your account', 'jetpack' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
<div id="deactivate-success"></div>

<style>
	.jp-nav li {
		list-style: none;
		display: inline;
		padding: 0 10px;
		border-right: 1px solid black;
	}

</style>
