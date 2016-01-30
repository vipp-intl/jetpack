<script id="tmpl-mod-feature-tab" type="text/html">
	<?php if ( Jetpack::is_development_mode() ) : ?>
	<div id="toggle-{{ data.module }}" data-index="{{ data.index }}" class="{{ data.activated ? 'activated' : '' }} {{ data.requires_connection && 'vaultpress' !== data.module ? 'unavailable' : '' }} j-row">
		<?php else : ?>
		<div id="toggle-{{ data.module }}" data-index="{{ data.index }}" class="{{ data.activated ? 'activated' : '' }} j-row">
			<?php endif; ?>
			<div href="{{ data.url }}" tabindex="0" data-index="{{ data.index }}" data-name="{{ data.name }}" class="feat j-col j-lrg-8 j-md-12 j-sm-7">
				<h4 title="{{ data.name }}" style="cursor: pointer; display: inline;">{{{ data.name }}}</h4>
				<# if ( 'vaultpress' == data.module ) { #>
					<span class="paid" title="<?php esc_attr_e( 'Premium Jetpack Service', 'jetpack' ); ?>"><?php esc_attr_e( 'PAID', 'jetpack' ); ?></span>
					<# } else if ( -1 == data.noConfig || data.configurable ) { #>
						<a href="{{ data.configure_url }}" class="dashicons dashicons-admin-generic" title="<?php esc_attr_e( 'Configure', 'jetpack' ); ?>"></a>
						<# } #>
							<p title="{{ data.short_description }}">{{{ data.short_description }}}</p>
			</div>
			<?php if ( current_user_can( 'jetpack_manage_modules' ) ) : ?>
				<div class="act j-col j-lrg-4 j-md-12 j-sm-5">
					<div class="module-action">
						<# if ( data.activated ) { #>
							<input class="is-compact form-toggle" type="checkbox" id="active-{{ data.module }}" checked />
							<# } else { #>
								<input class="is-compact form-toggle" type="checkbox" id="active-{{ data.module }}" />
								<# } #>
									<label class="form-toggle__label" for="active-{{ data.module }}">
										<img class="module-spinner-{{ data.module }}" style="display: none;" width="16" height="16" src="<?php echo esc_url( includes_url( 'images/spinner-2x.gif' ) ); ?>" alt="Loading ..." />
										<# if ( 'vaultpress' !== data.module ) { #>
											<label class="plugin-action__label" for="active-{{ data.module }}">
												<# if ( data.activated ) { #>
													<?php _e( 'Active', 'jetpack' ); ?>
													<# } else { #>
														<?php _e( 'Inactive', 'jetpack' ); ?>
														<# } #>
											</label>
											<# } #>

							<# if ( 'vaultpress' == data.module ) { #>
								<?php if ( is_plugin_active( 'vaultpress/vaultpress.php' ) ) : ?>
									<a href="<?php echo esc_url( $vp_link ); ?>" class="dashicons dashicons-external" title="<?php esc_attr_e( 'Configure', 'jetpack' ); ?>" target="<?php echo $target; ?>"></a>
								<?php else : ?>
									<a href="<?php echo esc_url( $vp_link ); ?>" class="lmore" title="<?php esc_attr_e( 'Learn More', 'jetpack' ); ?>" target="<?php echo $target; ?>"><?php _e( 'Learn More', 'jetpack' ); ?></a>
								<?php endif; ?>
							<# } else { #>
								<span class="form-toggle__switch"></span>
							<# } #>
						</label>
					</div>
				</div>
			<?php endif; ?>
		</div><?php // j-row ?>
</script>