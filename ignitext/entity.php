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

abstract class Entity
{
	public function __construct() {
		$this->profiler = \Get::the('\IgniteXT\Profiler');
	}
	
	private function log($event_type, $description)
	{
		$this->profiler->event($event_type, get_class(), __METHOD__, $description);
	}
	
	private function is_logging()
	{
		$this->profiler->is_logging(get_class());
	}
}