<?php
namespace Controllers\Libraries;
class stopwatch extends \System\Controller
{
	function index()
	{
		$actions[] = 'Create IXT_Stopwatch';
		$stopwatch = new \Libraries\IXT_Stopwatch();
		
		$actions[] = 'Create mark "one"';
		$stopwatch->mark('one');
		
		$actions[] = 'Wait 0.25 seconds';
		usleep(250000);
		
		$actions[] = 'Create mark "two"';
		$stopwatch->mark('two');
		
		$elapsed = $stopwatch->elapsed_time('one','two');
		$actions[] = 'Elapsed time: ' . number_format($elapsed, 4) . ' seconds';
		
		$data['actions'] = $actions;
		\System\Display::template('Stopwatch', 'libraries/stopwatch.index', $data);
	}
}