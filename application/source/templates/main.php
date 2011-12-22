<!DOCTYPE html>
<html>
	<head>
		<title><?=$content_title?></title>
		<link rel="stylesheet" type="text/css" href="<?=BASEURL?>assets/css/ignitext.css" />
	</head>
	<body>
		<div id="header">
			<img src="<?=BASEURL?>assets/images/ignitext.png" />
		</div>
		<? \System\Display::view($content_view, $data); ?>
		<div id="footer">
			IgniteXT Demos use icons created by <a href="http://p.yusukekamiyamane.com">Yusuke Kamiyamane</a>. All rights reserved. Licensed under a <a href="http://creativecommons.org/licenses/by/3.0/">Creative Commons Attribution 3.0 License</a>.
		</div>
	</body>
</html>