<?php
$data['tpl']['title'] = 'Stopwatch';
$data['tpl']['breadcrumbs'] = array( 
	array('', 'Libraries'),
	array('libraries/stopwatch', 'Stopwatch')
);
?>
<ul>
	<? foreach ($actions as $action): ?>
		<li><?=$action?></li>
	<? endforeach; ?>
</ul>