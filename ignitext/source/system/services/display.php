<?php
/**
 * Display
 * 
 * Shows views and templates.
 *
 * @copyright  Copyright 2011-2012, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace Services\System;

class Display extends \Services\System\Service
{
	public function __construct()
	{
		$this->input = \Get::the('\Services\System\Input');
	}
	
	public function view($view, &$data = array())
	{
		$requested_view = $view;
		
		$parts = explode('/', $view);
		$filename = array_pop($parts);
		
		$check_dirs = array(APPDIR, SHRDIR);
		foreach ($check_dirs as $dir)
		{
			$dir .= 'source/';
			if (count($parts) == 0 || !is_dir($dir . $parts[0])) $dir .= 'base/';
			for ($i = 0; $i <= count($parts); $i++)
			{
				$location = $dir;
				if ($i > 0) $location .= implode(array_slice($parts, 0, $i),'/') . '/';
				if ($i > 0 && !is_dir($location)) continue 2; //If this isn't a directory, none of the others will be either
				$location .= 'views' . '/';
				if ($i < count($parts)) $location .= implode(array_slice($parts, -(count($parts)-$i)),'/') . '/';
				$location .= $filename . '.php';
				if (file_exists($location)) break 2;
			}
		}
		$ixt = array();
		$ixt['location'] = $location;
				
		if (!file_exists($location))
		{
			throw new \Exception('View Not Found: ' . $requested_view);
		}
		
		unset($view, $requested_view, $parts, $check_dirs, $dir, $location, $filename, $i);
		
		if (is_array($data)) extract($data, EXTR_SKIP);
		if (!isset($tpl)) $tpl = new \stdClass;
		require($ixt['location']); 
		$data['tpl'] = $tpl;
		return true; 
	}

	public function return_view($view, &$data = array())
	{
		ob_start();
		$this->view($view, $data);
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	public function template_view($view, &$data = array())
	{
		$requested_view = $view;
		
		$path = APPDIR . 'templates/' . $view . '.php';
		if (!file_exists($path)) {
			throw new \Exception('Template View Not Found: ' . $requested_view);
		}
		
		$ixt = array();
		$ixt['path'] = $path;
		
		unset($view, $requested_view, $path);
		
		if (is_array($data)) extract($data, EXTR_SKIP);
		if (!isset($tpl)) $tpl = new \stdClass;
		require($ixt['path']); 
		$data['tpl'] = $tpl;
		return true; 
	}

	public function template($files, &$data = array(), $template = 'default')
	{
		if (!isset($data['tpl'])) $data['tpl'] = new \stdClass;
		$data['tpl']->template = $template;
		
		$content = '';
		if (!is_array($files)) $files = array($files);
		foreach ($files as $file) $content .= $this->return_view($file, $data);
		$data['tpl']->content = $content;
		
		$this->template_view($template, $data);
	}
	
}