<h2>Welcome to At A Glance!</h2>

<?php
/*
 * Stats area
 */

// Loads the stats chart & resources.
if ( Jetpack::is_module_active( 'stats' ) ) {
	stats_reports_load(); stats_js_remove_stnojs_cookie(); stats_js_load_page_via_ajax(); stats_reports_page( true );
} else {
	echo 'activate stats to see some awesome stuff! <br>';
}
?>

<b>Site Security</b>
<ul>
	<li>Protect</li>
	<li>Securty Scan</li>
	<li>Site Monitoring</li>
</ul>

<b>Site Health</b>
<ul>
	<li>Anti-spam</li>
	<li>Image Performance</li>
	<li>Backups</li>
	<li>Plugin Updates</li>
	<li>Site Verification</li>
</ul>

<a href="#">What would you like to see on your dashboard?</a>

<style>
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
</style>