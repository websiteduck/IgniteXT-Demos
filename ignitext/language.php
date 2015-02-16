<?php
/**
 * Language
 * 
 * Manages language files that contain translations.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

class Language extends Service
{
	public $language = 'en';
	public $languages = array();

	public function load($type = 'main')
	{
		if (isset($this->languages[$this->language][$type])) return true;
		$file_path = 'languages/' . $this->language . '/' . $type . '.php';
		
		$this->languages[$this->language][$type] = array();
		
		$dirs = array(APP_DIR, SHR_DIR, IXT_DIR);
		$found = false;
		foreach ($dirs as $dir) {
			if (file_exists($dir . $file_path)) {
				$this->languages[$this->language][$type] = array_merge_recursive($this->languages[$this->language][$type], require($dir. $file_path));
				$found = true;
			}
		}
		
		if ($found !== true) { 
			//TODO: do something
		}
		
		return true;
	}

	public function line()
	{
		$arguments = func_get_args();
		$key = array_shift($arguments);
		if (count($arguments)==1 && is_array($arguments[0])) $arguments = $arguments[0];
		$replacements = $arguments;
		
		$line = \array_get($this->languages[$this->language], $key);
		$line = vsprintf($line, $replacements);
		
		return $line;
	}

}