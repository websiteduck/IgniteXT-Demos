<!DOCTYPE html>
<html>
	<head>
		<title><?=$content_title?></title>
		<link rel="stylesheet" type="text/css" href="<?=BASEURL?>assets/css/ignitext.css" />
		<link rel="shortcut icon" href="<?=BASEURL?>assets/images/favicon.ico" >
	</head>
	<body>
		<div id="header">
			<img src="<?=BASEURL?>assets/images/ignitext.png" />
		</div>
		<div id="header_menu">
			<ul>
				<li><a href="<?=BASEURL?>">Home</a></li>
				<li><a href="http://www.ignitext.com">IgniteXT</a></li>
				<li><a href="https://github.com/websiteduck/">GitHub</a></li>
			</ul>
		</div>
		<div id="content">
			<h1 id="content_title"><?=$content_title?></h1>
			<? \System\Display::view($content_view, $data); ?>
		</div>
		<div id="footer">
			<p style="text-align: center;">&copy; 2011 Website Duck LLC</p>
			<p style="text-align: center;">IgniteXT Demos use icons created by <a href="http://p.yusukekamiyamane.com">Yusuke Kamiyamane</a>. All rights reserved. Licensed under a <a href="http://creativecommons.org/licenses/by/3.0/">Creative Commons Attribution 3.0 License</a>.</p>
		</div>
	</body>
</html>