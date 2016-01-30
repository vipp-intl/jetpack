	</div><!-- .wrapper -->
		<div class="footer">

			<nav class="primary nav-horizontal">
				<div class="a8c-attribution">
					<span>
						<?php echo sprintf( __( 'An %s Airline', 'jetpack' ),
							'<a href="http://automattic.com/" class="a8c-logo">Automattic</a>'
						); ?>
					</span>
				</div>
			</nav><!-- .primary -->

			<nav class="secondary nav-horizontal">
				<div class="secondary-footer">
					<a href="http://jetpack.me">Jetpack <?php echo JETPACK__VERSION; ?></a>
					<a href="http://wordpress.com/tos/"><?php esc_html_e( 'Terms', 'jetpack' ); ?></a>
					<a href="http://automattic.com/privacy/"><?php esc_html_e( 'Privacy', 'jetpack' ); ?></a>
					<?php if ( current_user_can( 'jetpack_manage_modules' ) ) : ?><a href="<?php echo esc_url( Jetpack::admin_url( 'page=jetpack-debugger' ) ); ?>" title="<?php esc_attr_e( 'Test your site&#8217;s compatibility with Jetpack.', 'jetpack' ); ?>"><?php _e( 'Debug', 'jetpack' ); ?><?php endif; ?></a>
					<?php if ( Jetpack::is_active() && current_user_can( 'jetpack_disconnect' ) ) : ?>
						<a href="<?php echo esc_url( Jetpack::admin_url( 'page=my_jetpack#disconnect' ) ); ?>"><?php esc_html_e( 'Disconnect Jetpack', 'jetpack' ); ?></a>
					<?php endif; ?>
				</div>
			</nav><!-- .secondary -->
		</div><!-- .footer -->

	</div><!-- .jp-frame -->
</div><!-- .jp-content -->

<?php if ( 'jetpack_modules' == $_GET['page'] ) return; ?>
