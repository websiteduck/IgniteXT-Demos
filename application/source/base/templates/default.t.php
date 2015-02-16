<?php
if (empty($tpl->breadcrumbs)) $tpl->breadcrumbs = array();
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$tpl->title?></title>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo ASSETS ?>js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo ASSETS ?>css/bootstrap.min.css" media="all">
		<link rel="stylesheet" type="text/css" href="<?php echo ASSETS ?>css/ignitext.css">
		<link rel="shortcut icon" href="<?php echo ASSETS ?>img/favicon.ico">
	</head>
	<body>
		
		<div id="container">
		
		<div id="header">
			<img src="<?php echo ASSETS ?>img/ignitext.png" style="float: left;">
			<div id="menu">
				<ul>
					<li>
						<div class="btn-group" style="margin-top: 7px;">
							<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
								Demos
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><h3>&nbsp; Libraries</h3></li>
								<li><a href="<?php echo BASE_URL ?>libraries/form-validation">IXT_Form_Validation</a></li>
								<li><a href="<?php echo BASE_URL ?>libraries/stopwatch">IXT_Stopwatch</a></li>
								<li class="divider"></li>
								<li><h3>&nbsp; Demo Apps</h3></li>
								<li><a href="<?php echo BASE_URL ?>user-manager">User Manager</a></li>
							</ul>
						</div>
					</li>
					<li><a href="http://www.ignitext.com">IgniteXT Website <i class="icon-share-alt icon-white"></i></a></li>
					<li><a href="https://github.com/websiteduck/">GitHub <i class="icon-share-alt icon-white"></i></a></li>
				</ul>
			</div>
		</div>
				
		<ul id="breadcrumbs" class="breadcrumb">
			<li><a href="<?php echo BASE_URL ?>">Home</a> <span class="divider">/</span></li>
			<?php foreach ($tpl->breadcrumbs as $breadcrumb): ?>
				<?php list($title, $url) = $breadcrumb; ?>
				<?php if (!empty($url)): ?>
					<li><a href="<?=BASE_URL?><?=$url?>"><?=$title?></a> <span class="divider">/</span></li>
				<?php else: ?>
					<li><?=$title?> <span class="divider">/</span></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		
		<div id="content">
			<div id="page_title" class="page-header"><h1><?=$tpl->title?></h1></div>
			<?=$tpl->content?>
			<br style="clear: both;">
		</div>
		
		<div id="footer">
			<p style="text-align: center;">&copy; <?=date('Y')?> Website Duck LLC</p>
			<p style="text-align: center;">
				<a href="http://twitter.github.com/bootstrap">Twitter Bootstrap</a> &nbsp; // &nbsp; 
				<a href="http://p.yusukekamiyamane.com">Fugue Icons by Yusuke Kamiyamane</a>
				<br>
				For more information, see the <a href="https://github.com/websiteduck/IgniteXT-Demos/blob/master/README">README</a>.
			</p>
		</div>
			
		</div>
		
	</body>
</html>