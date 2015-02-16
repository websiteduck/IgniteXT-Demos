<?php
/**
 * Display
 * 
 * Shows views and templates.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

class Display extends Service
{
	public $default_template = 'default';
	public $template_loaded = false;
	public $data = array();

	public function created() 
	{		
		if (!isset($this->sess) && isset($this->session)) $this->sess = &$this->session->reference();
		if (!isset($this->conf) && isset($this->config)) $this->conf = &$this->config->reference();
	}
	
	public function view($view, $ixt_data = null)
	{		
		$ixt_location = $this->find_file($view, 'v');
		unset($view);
		
		if (is_array($ixt_data)) extract($ixt_data, EXTR_SKIP);
		else extract($this->data, EXTR_SKIP);
		unset($ixt_data);

		if (!isset($tpl)) $tpl = new \stdClass;
		require($ixt_location); 
		$this->data['tpl'] = $tpl;
		return true; 
	}

	public function return_view($view, $data = null)
	{
		ob_start();
		$this->view($view, $data);
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	public function template_view($view, $ixt_data = null)
	{		
		$ixt_location = $this->find_file($view, 't');
		unset($view);
		
		if (is_array($ixt_data)) extract($ixt_data, EXTR_SKIP);
		else extract($this->data, EXTR_SKIP);
		unset($ixt_data);

		if (!isset($tpl)) $tpl = new \stdClass;
		require($ixt_location); 
		$this->data['tpl'] = $tpl;
		return true; 
	}

	public function template($files, $data = null, $template = null)
	{
		if ($template === null) $template = $this->default_template;
		
		$this->template_loaded = true;
		if (!isset($this->data['tpl'])) $this->data['tpl'] = new \stdClass;
		$this->data['tpl']->template = $template;
		
		$content = '';
		if (!is_array($files)) $files = array($files);
		foreach ($files as $file) $content .= $this->return_view($file, $data);
		$this->data['tpl']->content = $content;
		
		$this->template_view($template, $data);
	}

	public function template_wrap_html($html, $data = null, $template = null)
	{
		if ($template === null) $template = $this->default_template;
		
		$this->template_loaded = true;
		if (!isset($this->data['tpl'])) $this->data['tpl'] = new \stdClass;
		$this->data['tpl']->template = $template;
		$this->data['tpl']->content = $html;
		
		$this->template_view($template, $data);	
	}

	protected function find_file($view, $type = 'v')
	{
		$check_dirs = array(
			APP_DIR . 'source/', 
			SHR_DIR . 'source/',
		);

		if ($type === 'v') {
			$type_text = 'View';
			$folder_name = 'views';
		}
		elseif ($type === 't') {
			$type_text = 'Template';
			$folder_name = 'templates';
		}
		else {
			throw new \Exception('Invalid view type: ' . $type);
		}

		$parts = explode('/', $view);
		$filename = array_pop($parts);
		
		foreach ($check_dirs as $dir)
		{
			if (count($parts) == 0 || !is_dir($dir . $parts[0])) $base = 'base';
			else $base = array_shift($parts);

			$location = $dir . $base . '/' . $folder_name . '/' . implode($parts, '/') . '/' . $filename . '.' . $type . '.php';
			if (file_exists($location)) break;
		}
				
		if (!file_exists($location))
		{
			ob_end_clean();
			throw new \Exception($type_text . ' Not Found: ' . $view);
		}

		return $location;
	}
	
}