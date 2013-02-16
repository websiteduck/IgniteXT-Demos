<?php
/**
 * Model
 * 
 * The base class for a model.
 *
 * @copyright  Copyright 2011-2012, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

abstract class Model extends Service
{
	public function __construct() 
	{
		$this->db = \Get::the('\IgniteXT\Database');
	}
}