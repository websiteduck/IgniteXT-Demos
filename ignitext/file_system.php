<?php
/**
 * File System
 * 
 * A wrapper class for PHP file system functions
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

abstract class File_System extends Service
{
	public static function file_exists($filename) { return file_exists($filename); }
	public static function is_dir($filename) { return is_dir($filename); }
}