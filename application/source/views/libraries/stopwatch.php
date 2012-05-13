<?php
$tpl['title'] = 'Stopwatch';

$tpl['breadcrumbs'] = array(
	array('Libraries', ''),
	array('Stopwatch', '')
);
?>
<ul>
	<? foreach ($actions as $action): ?>
		<li><?=$action?></li>
	<? endforeach; ?>
</ul>