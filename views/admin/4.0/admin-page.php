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
				<div id="settings">
					<?php include_once( 'settings-tmpl.php' ); ?>
				</div>
			</div>
		</div>

		<?php include_once( 'support-area.php' ); ?>

</div><!-- .landing -->

	<?php else : ?>
		<?php if ( ! $data['is_connected'] && current_user_can( 'jetpack_connect' ) ) : ?>
			<a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="download-jetpack"><?php esc_html_e( 'Connect Jetpack', 'jetpack' ); ?></a>
		<?php elseif ( $data['is_connected'] && ! $data['is_user_connected'] && current_user_can( 'jetpack_connect_user' ) ) : ?>
			<a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="download-jetpack"><?php esc_html_e( 'Connect your account', 'jetpack' ); ?></a>
		<?php endif; ?>
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
