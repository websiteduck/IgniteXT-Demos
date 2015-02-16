<?php
namespace Controllers\Libraries;

class Stopwatch extends \IgniteXT\Controller
{
	function get_index()
	{
		$actions[] = 'Create IXT_Stopwatch';
		$stopwatch = \Get::a('IgniteXT.Stopwatch');
		
		$actions[] = 'Create mark "one"';
		$stopwatch->mark('one');
		
		$actions[] = 'Wait 0.25 seconds';
		usleep(250000);
		
		$actions[] = 'Create mark "two"';
		$stopwatch->mark('two');
		
		$elapsed = $stopwatch->elapsed_time('one','two');
		$actions[] = 'Elapsed time: ' . number_format($elapsed, 4) . ' seconds';
		
		$this->data['actions'] = $actions;
		$this->display->template('libraries/stopwatch');
	}
}