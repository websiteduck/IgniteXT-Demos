<?php
/**
 * Service_Entity
 * 
 * The base class for services and entities.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

abstract class Service_Entity
{
	protected function log($event_type, $method, $description)
	{
		if (!isset($this->profiler)) $this->profiler = \Get::the('IgniteXT.Profiler');
		$this->profiler->event($event_type, get_class(), $method, $description);
	}
	
	protected function is_logging()
	{
		if (!isset($this->profiler)) $this->profiler = \Get::the('IgniteXT.Profiler');
		return $this->profiler->is_class_logging(get_class());
	}
}