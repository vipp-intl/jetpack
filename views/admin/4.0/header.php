<?php $current = $_GET['page']; ?>
<div class="jp-content">
	<div class="jp-frame">
		<div class="jp-mast">
			<nav role="navigation" class="header-nav drawer-nav nav-horizontal">

				<ul class="jp-mast-nav">
					<li class="jetpack-logo">
						<a href="<?php echo Jetpack::admin_url(); ?>" title="<?php esc_attr_e( 'Jetpack', 'jetpack' ); ?>" <?php if ( 'jetpack' == $current ) { echo 'class="current"'; } ?>><span><?php esc_html_e( 'Jetpack', 'jetpack' ); ?></span></a>
					</li>
					<li class="">
						<a href="https://jetpack.me/support" target="_blank" class=""><?php esc_html_e( 'Need Help?', 'jetpack' ); ?></a>
					</li>
					<li class="">
						<a href="http://jetpack.me/survey/?rel=<?php echo JETPACK__VERSION; ?>" target="_blank" class=""><?php esc_html_e( 'Feedback', 'jetpack' ); ?></a>
					</li>
				</ul>

			</nav>

		</div><!-- .jp-mast -->

		<div class="wrapper">