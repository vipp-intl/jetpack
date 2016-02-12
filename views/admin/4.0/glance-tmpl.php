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