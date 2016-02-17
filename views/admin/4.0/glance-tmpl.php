<h2>Welcome to At A Glance!</h2>

<?php
/*
 * Stats area
 */
?>

<div id="at-a-glance-stats">
	<h2>Stats</h2>
	<?php
	// Loads the stats chart & resources.
	if ( Jetpack::is_module_active( 'stats' ) ) {
		stats_reports_page( true );
	} else {
		echo 'Please activate Stats!';
	}
	?>
</div>

<!--<button id="activate-stats" class="state-trigger">Activate-stats</button>-->
<!--<button id="deactivate-stats" class="state-trigger">Deactivate Stats</button>-->
<script type="text/html" id="tmpl-glance-mosaic">
	<div class="jp-mosaic-box mosaic-{{ data.glanceData.size }}">
		<h4 class="jp-mosaic-title">{{ data.glanceData.title }}</h4>
		<span>{{ data.glanceData.data }}</span>
		<p>{{ data.glanceData.message }}</p>
	</div>
</script>

<div id="at-a-glance-site-security" class="glance-section">
	<h2>Site Security:</h2>
	<div id="glance-metabox-protect"></div>
	<div id="glance-metabox-scan"></div>
	<div id="glance-metabox-monitor"></div>
</div>

<div id="glance-site-health" class="glance-section">
	<h2>Site Health</h2>
	<div id="glance-metabox-anti-spam"></div>
	<div id="glance-metabox-site-backups"></div>
	<div id="glance-metabox-plugin-updates"></div>
</div>

<div id="glance-site-traffic-tools" class="glance-section">
	<h2>Traffic Tools</h2>
	<div id="glance-metabox-img-performance"></div>
	<div id="glance-metabox-site-verification"></div>
</div>

<a href="#">What would you like to see on your dashboard?</a>

<style>
	.glance-section {
		width: 100%;
	}

	#chartswitch li {
		display: inline;
		margin: 0 20px;
	}

	#chartswitch li a.active {
		border-bottom: 1px solid;
		padding-bottom: 6px;
	}

	ul#chartswitch {
		text-align: right;
	}

	/**
	 * Stats Nuggets
	 */
	#stats-nuggets {
		background: #fff;
	}
	#stats-nuggets ul {
		padding: 20px 10px 0 10px;
		margin-top: 0;
		overflow: hidden;
		position: relative;
		_height: 1%;
	}
	#stats-nuggets ul li {
		float: left;
	}
	#stats-nuggets ul li span {
		font-weight: lighter;
		font-size: 16pt;
		line-height: 1.5em;
		display: block;
	}
	#stats-nuggets li {
		font-size: 12px;
		width: 37.5%;
		float: left;
		white-space: nowrap;
		box-sizing: border-box;
		-moz-box-sizing:border-box;
		-webkit-box-sizing:border-box;
		-ms-box-sizing:border-box;
		text-align: center;
		padding: 0 20px;
		line-height: 2;
	}
	#stats-nuggets li h3, #stats-nuggets li em {
		display: block;
		font-style: normal;
		font-weight: normal;
		float: none;
		text-align: center;
	}
	#stats-nuggets li h3 {
		margin: 0 !important;
		background: 0 !important;
	}
	#stats-nuggets li strong {
		width: 50%;
		float: left;
		font-weight: normal;
	}
	#stats-nuggets li a {
		text-decoration: none;
		color: #404040;
	}
	#stats-nuggets li a:hover {
		color: #F1831E;
	}
	#stats-nuggets li#bestever {
		width: 25%;
		border-left: 1px dotted #ababab;
		border-right: 1px dotted #ababab;
	}



	/* Mosaic stuff */
	.jp-mosaic-box {
		background: #fff;
		display: inline-block;
		min-height: 100px;
		margin: 1%;
	}

	.mosaic-large {
		width: 48%;
		float: left;
		height: 200px;
	}

	.mosaic-small {
		width: 48%;
		float: left;
	}

</style>