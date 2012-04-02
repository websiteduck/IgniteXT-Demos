<!DOCTYPE html>
<html>
	<head>
		<title><?=$tpl['title']?></title>
		<link rel="stylesheet" type="text/css" href="<?=ASSETS?>css/kickstart.css" media="all" />
		<link rel="stylesheet" type="text/css" href="<?=ASSETS?>css/ignitext.css" />
		<link rel="shortcut icon" href="<?=ASSETS?>img/favicon.ico" >
	</head>
	<body>
		
		<div id="header">
			<img src="<?=ASSETS?>img/ignitext.png" />
		</div>

		<ul class="menu">
			<li>
				<a href="#" onclick="return false">Demos</a>
				<ul>
					<li>
						<a href="#" onclick="return false">Libraries</a>
						<ul>
							<li><a href="<?=BASEURL?>libraries/form_validation">\Libraries\IXT_Form_Validation</a></li>
							<li><a href="<?=BASEURL?>libraries/stopwatch">\Libraries\IXT_Stopwatch</a></li>
						</ul>
					</li>
					<li>
						<a href="#" onclick="return false">Demo Apps</a>
						<ul>
							<li><a href="<?=BASEURL?>user_manager/">User Manager</a></li>
						</ul>
					</li>
				</ul>
			</li>
			<li style="border-right: 1px solid #AAA;"><a href="#" onclick="return false" style="padding: 15px 0;">&nbsp;</a></li>
			<li><a href="http://www.ignitext.com"><span class="icon gray small" data-icon="_"></span> IgniteXT Website</a></li>
			<li><a href="https://github.com/websiteduck/"><span class="icon gray small" data-icon="_"></span> GitHub</a></li>
		</ul>
		
		<ul class="breadcrumbs alt1" style="margin-top: 0px; border: none;">
			<li><a href="<?=BASEURL?>">Home</a></li>
			<? if (is_array($tpl['breadcrumbs']) && count($tpl['breadcrumbs']) > 0): ?>
			<? foreach ($tpl['breadcrumbs'] as $breadcrumb): ?>
				<li><a href="<?=BASEURL . $breadcrumb[0]?>"><?=$breadcrumb[1]?></a></li>
			<? endforeach; ?>
			<? endif; ?>
		</ul>
		
		<div id="content">
			<h1 id="content_title"><?=$tpl['title']?></h1>
			<?=$content?>
			<br style="clear: both;" />
		</div>
		
		<div id="footer">
			<p style="text-align: center;">&copy; 2011 Website Duck LLC</p>
			<p style="text-align: center;">
				<a href="http://www.99lime.com">99Lime HTML KickStart</a> /  
				<a href="http://p.yusukekamiyamane.com">Fugue Icons by Yusuke Kamiyamane</a>
				<br />
				For more information, see the <a href="https://github.com/websiteduck/IgniteXT-Demos/blob/master/README">README</a>.
			</p>
		</div>
		
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="<?=ASSETS?>js/prettify.js"></script>
		<script type="text/javascript" src="<?=ASSETS?>js/kickstart.js"></script>
		
	</body>
</html>