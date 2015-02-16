<?php
/**
 * Model
 * 
 * The base class for a model.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

abstract class Model extends Service
{
	public function created() 
	{		
		if (!isset($this->sess) && isset($this->session)) $this->sess = &$this->session->reference();
		if (!isset($this->conf) && isset($this->config)) $this->conf = &$this->config->reference();
	}
}