<div id="jump-start-success"></div>
<div id="jump-start-area" class="j-row">
	<h1 title="<?php esc_attr_e( 'Jump Start your site by activating these components', 'jetpack' ); ?>" class="jstart"><?php _e( 'Jump Start your site', 'jetpack' ); ?></h1>
	<div class="jumpstart-desc j-col j-sm-12 j-md-12">
		<div class="jumpstart-message">
			<p id="jumpstart-paragraph-before"><?php
				if ( count( $data['jumpstart_list'] ) > 1 ) {
					$last_item = array_pop( $data['jumpstart_list'] );
					/* translators: %1$s is a comma-separated list of module names or a single module name, %2$s is the last item in the module list */
					echo sprintf( __( 'To quickly boost performance, security, and engagement we recommend activating <strong>%1$s and %2$s</strong>. Click <strong>Jump Start</strong> to activate these features or <a class="pointer jp-config-list-btn">learn more</a>', 'jetpack' ), implode( $data['jumpstart_list'], ', ' ), $last_item );

				} else {
					/* translators: %s is a module name */
					echo sprintf( __( 'To quickly boost performance, security, and engagement we recommend activating <strong>%s</strong>. Click <strong>Jump Start</strong> to activate this feature or <a class="pointer jp-config-list-btn">learn more</a>', 'jetpack' ), $data['jumpstart_list'][0] );
				}
				?></p>
		</div><!-- /.jumpstart-message -->
	</div>
	<div class="jumpstart-message hide">
		<h1 title="<?php esc_attr_e( 'Your site has been sucessfully Jump Started.', 'jetpack' ); ?>" class="success"><?php _e( 'Success! You\'ve jump started your site.', 'jetpack' ); ?></h1>
		<p><?php echo sprintf( __( 'Check out other recommended features below, or go to the <a href="%s">settings</a> page to customize your Jetpack experience.', 'jetpack' ), admin_url( 'admin.php?page=jetpack_modules' ) ); ?></p>
	</div><!-- /.jumpstart-message -->
	<div id="jumpstart-cta" class="j-col j-sm-12 j-md-12 j-lrg-4">
		<img class="jumpstart-spinner" style="margin: 49px auto 14px; display: none;" width="17" height="17" src="<?php echo esc_url( includes_url( 'images/spinner-2x.gif' ) ); ?>" alt="Loading ..." />
		<a id="jump-start" class="button-primary" ><?php esc_html_e( 'Jump Start', 'jetpack' ); ?></a>
		<a class="dismiss-jumpstart pointer" ><?php esc_html_e( 'Skip', 'jetpack' ); ?></a>
	</div>
	<div id="jump-start-module-area">
		<div id="jp-config-list" class="clear j-row hide">
			<a class="pointer jp-config-list-btn close" ><span class="dashicons dashicons-no"></span></a>
		</div>
	</div>
</div>