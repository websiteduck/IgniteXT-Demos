<?php
/**
 * Service
 * 
 * The base class for a service.
 *
 * @copyright  Copyright 2011-2012, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

abstract class Service
{
	public function __construct() {
		if (get_class($this) !== 'IgniteXT\Profiler')
			$this->profiler = \Get::the('\IgniteXT\Profiler');
	}
	
	protected function log($event_type, $method, $description)
	{
		$this->profiler->event($event_type, get_class($this), $method, $description);
	}
	
	protected function is_logging()
	{
		return $this->profiler->is_class_logging(get_class());
	}
}