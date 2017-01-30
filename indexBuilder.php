<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
  <style>
		body {
			padding: 100px 20px;
			background: #434343;
			min-width: 600px;
			color: #fff;
			font-family: Arial, sans-serif;
		}
		.site-header {
			background: #545454;
			width: 100%;
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			height: 80px;
			min-width: 600px
		}
		.site-header .fr {
			float: right;
			line-height: 80px;
			margin-right: 30px;
			color: #fff;
			font-size: 13px;
			letter-spacing: 3px;
		}
		h1 {
			display: block;
			margin-top: 50px;
			color: #b4ae98;
		}
		h1:first-of-type {
			margin-top: 30px;
		}
		a {
			display: block;
			padding: 20px 10px;
			color: #fff;
			text-decoration: none;
			border-bottom: 1px solid #b4ae98;
			font-family: Arial, sans-serif;
			font-size: 14px;
		}
		a::after {
			content: 'View email';
			display: none;
			color: #b4ae98;
			float: right;
		}
		a:hover {
			background: #545454;
		}
		a:hover::after {
			display: block;
		}
	</style>
</head>
<body>
	<div class="site-header">
		<img class="logo" src="http://img2.email2inbox.co.uk/2016/stonegate/templates/sg_logo.jpg"></img>
		<span class="fr">CRM EMAIL TEMPLATES</span>
	</div>



<?php

foreach(glob("client.demo/*/") as $filename){
  $parentFolder = preg_replace('/.*?\/(.*?)\//', '$1', $filename);

  $title = preg_replace('/_/', ' ', $parentFolder);
  $title = ucwords($title);

  print_r('<h1>' . $title . '</h1>');
	print_r('<a href="it2compiled/' . $parentFolder . '/branded/password_reset.html">Forgetten Password</a>');
	print_r('<a href="it2compiled/' . $parentFolder . '/branded/auto_welcome_uk.html">Auto Welcome - Immediate</a>');
	print_r('<a href="it2compiled/' . $parentFolder . '/branded/welcome_1_day_uk.html">Welcome 1 + 1 Day</a>');
	print_r('<a href="it2compiled/' . $parentFolder . '/branded/welcome_7_days_uk.html">Welcome 2 + 7 Days</a>');
	print_r('<a href="it2compiled/' . $parentFolder . '/branded/welcome_21_days_uk.html">Welcome 3 + 21 Days</a>');
	print_r('<a href="it2compiled/' . $parentFolder . '/branded/auto_welcome_scot.html">Auto Welcome - Immediate (Scot)</a>');
	print_r('<a href="it2compiled/' . $parentFolder . '/branded/welcome_1_day_scot.html">Welcome 1 + 1 Day (Scot)</a>');
	print_r('<a href="it2compiled/' . $parentFolder . '/branded/welcome_7_days_scot.html">Welcome 2 + 7 Days (Scot)</a>');
	print_r('<a href="it2compiled/' . $parentFolder . '/branded/welcome_21_days_scot.html">Welcome 3 + 21 Days (Scot)</a>');

	print_r('<a href="it2compiled/' . $parentFolder . '/branded/wifi_1_day.html">WiFi Sign In 1 Plus 1 Day</a>');
	print_r('<a href="it2compiled/' . $parentFolder . '/branded/wifi_7_days.html">WiFi Sign In 2 Plus 7 Days</a>');
	print_r('<a href="it2compiled/' . $parentFolder . '/branded/wifi_21_days.html">WiFi Sign In 3 Plus 21 Days</a>');

	print_r('<a href="it2compiled/' . $parentFolder . '/branded/birthday_1_week.html">Birthday 1 Week</a>');
	print_r('<a href="it2compiled/' . $parentFolder . '/branded/birthday_3_weeks.html">Birthday 3 Weeks</a>');
	print_r('<a href="it2compiled/' . $parentFolder . '/branded/birthday_6_weeks.html">Birthday 6 Weeks</a>');

  print_r('<a href="it2compiled/' . $parentFolder . '/branded/adhoc.html">Adhoc</a>');
  print_r('<a href="it2compiled/' . $parentFolder . '/branded/belly-band.html">Belly Band</a>');
  print_r('<a href="it2compiled/' . $parentFolder . '/branded/newsletter.html">Newsletter</a>');


}

 ?>

</body>

</html>
