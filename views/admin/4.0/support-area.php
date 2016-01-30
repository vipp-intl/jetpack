<div class="nux-foot j-row">
	<div class="j-col j-lrg-8 j-md-8 j-sm-12">
		<?php
		// Get a list of Jetpack Happiness Engineers.
		$jetpack_hes = array(
			'724cd8eaaa1ef46e4c38c4213ee1d8b7',
			'623f42e878dbd146ddb30ebfafa1375b',
			'561be467af56cefa58e02782b7ac7510',
			'd8ad409290a6ae7b60f128a0b9a0c1c5',
			'790618302648bd80fa8a55497dfd8ac8',
			'6e238edcb0664c975ccb9e8e80abb307',
			'4e6c84eeab0a1338838a9a1e84629c1a',
			'9d4b77080c699629e846d3637b3a661c',
			'4626de7797aada973c1fb22dfe0e5109',
			'190cf13c9cd358521085af13615382d5',
		);

		// Get a fallback profile image.
		$default_he_img = plugins_url( 'images/jetpack-icon.jpg', JETPACK__PLUGIN_FILE );

		printf(
			'<a href="http://jetpack.me/support/" target="_blank"><img src="https://secure.gravatar.com/avatar/%1$s?s=75&d=%2$s" alt="Jetpack Happiness Engineer" /></a>',
			$jetpack_hes[ array_rand( $jetpack_hes ) ],
			urlencode( $default_he_img )
		);
		?>
		<p><?php _e( 'Help and Support', 'jetpack' ); ?></p>
		<p><?php _e( 'We offer free, full support to all Jetpack users. Our support team is always around to help you.', 'jetpack' ); ?></p>
		<ul class="actions">
			<li><a href="http://jetpack.me/support/" target="_blank" class="button"><?php esc_html_e( 'Visit support site', 'jetpack' ); ?></a></li>
			<li><a href="https://wordpress.org/support/plugin/jetpack" target="_blank"><?php esc_html_e( 'Browse forums', 'jetpack' ); ?></a></li>
			<li><a href="http://jetpack.me/contact-support/" target="_blank"><?php esc_html_e( 'Contact us directly', 'jetpack' ); ?></a></li>
		</ul>
	</div>
	<div class="j-col j-lrg-4 j-md-4 j-sm-12">
		<p><?php _e( 'Premium Add-ons', 'jetpack' ); ?></p>
		<p><?php esc_html_e( 'Business site? Safeguard it with real-time backups, security scans, and anti-spam.', 'jetpack' ); ?></p>
		<p>&nbsp;</p>
		<?php $normalized_site_url = Jetpack::build_raw_urls( get_home_url() ); ?>
		<div class="actions"><a href="<?php echo esc_url( 'https://wordpress.com/plans/' . $normalized_site_url ); ?>" target="_blank" class="button"><?php esc_html_e( 'Compare Options', 'jetpack' ); ?></a></div>
	</div>
</div><?php // nux-foot ?>