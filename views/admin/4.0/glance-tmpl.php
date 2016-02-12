<h2>Welcome to At A Glance!</h2>

<?php
/*
 * Stats area
 */

// Loads the stats chart & resources.
stats_reports_load(); stats_js_remove_stnojs_cookie(); stats_js_load_page_via_ajax(); stats_reports_page( true ); ?>

<ul><li>Here are the stats</li></ul>

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