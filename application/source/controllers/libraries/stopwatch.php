<?php
namespace Controllers\Libraries;

use \Services\System\Display;

class stopwatch extends \Services\System\Controller
{
	function index()
	{
		$actions[] = 'Create IXT_Stopwatch';
		$stopwatch = new \Entities\IXT\Stopwatch();
		
		$actions[] = 'Create mark "one"';
		$stopwatch->mark('one');
		
		$actions[] = 'Wait 0.25 seconds';
		usleep(250000);
		
		$actions[] = 'Create mark "two"';
		$stopwatch->mark('two');
		
		$elapsed = $stopwatch->elapsed_time('one','two');
		$actions[] = 'Elapsed time: ' . number_format($elapsed, 4) . ' seconds';
		
		$data['actions'] = $actions;
		Display::template('libraries/stopwatch', $data);
	}
}