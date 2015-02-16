<?php
/**
 * Profiler
 * 
 * Keeps track of things that happen during execution and how long those things
 * took.  Used for debugging and logging purposes.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

class Profiler extends Service
{
	//Settings
	public $log_everything = false;
	public $output_json = false;
	public $output_html = false;
	public $require_template = false;
	
	protected $classes_logging = array();
	protected $start_time = 0;
	protected $log = array();
	
	public function get_classes_logging() { return $this->classes_logging; }
	public function log_class($class) { $this->classes_logging[] = $class; }
	public function is_class_logging($class) { return ($this->log_everything || in_array($class, $this->classes_logging)); }
	public function get_start_time() { return $this->start_time; }
	public function get_log() { return $this->log; }
	
	public function start()
	{
		$this->start_time = microtime(true);
		register_shutdown_function(array($this, 'finish'));
	}
	
	public function finish()
	{
		if ($this->require_template) {
			$display = \Get::the('IgniteXT.Display');
			if (!$display->template_loaded) return;
		}
		
		if ($this->output_html) $this->output_json = true;
		
		if ($this->output_json) $this->render_json();
		if ($this->output_html) $this->render_html();
	}

	public function event($event_type, $class, $method, $description)
	{
		if ($this->log_everything || in_array($class, $this->classes_logging))
		{
			$event_type = strtoupper($event_type);
			$time = microtime(true) - $this->start_time;
			$this->log[] = array(
				'time' => $time, 
				'nice_time' => number_format($time, 6) . 's',
				'type' => $event_type, 
				'from' => $class, 
				'action' => $method, 
				'description' => $description
			);
		}
	}
	
	public function render_json()
	{
		$html_safe_log = $this->log;
		array_walk_recursive($html_safe_log, function(&$item, $key) { $item = htmlentities($item, ENT_QUOTES); });
		$json_log = json_encode($html_safe_log);
		?>
			<script type="text/javascript">
				var profiler_log = <?=$json_log?>;
			</script>
		<?php
	}
	
	public function render_html()
	{
		?>
		<style type="text/css">
			#ixt_profiler { position: fixed; left: 0; bottom: 0; width: 100%; height: 300px; overflow-y: auto; background-color: #EEE; color: #444; border-top: 1px dashed #444; resize: vertical; }
			#ixt_profiler h1 { font-size: 16px; }
			#ixt_events { border-collapse: collapse; width: 100%; }
			#ixt_events td, #ixt_events th { padding: 5px; }
			#ixt_events th { border: 1px solid #DDD; background-color: #EEE; color: #444; }
			#ixt_events td { border: 1px solid #EEE; background-color: #FFF; color: #333; }
			#ixt_events td.type { width: 32px; }
			#ixt_events td.normal { border: 1px solid #7E6; background-color: #7E6; }
			#ixt_events td.notice { border: 1px solid #6BE; background-color: #6BE; }
			#ixt_events td.warning { border: 1px solid #FF0; background-color: #FF0; }
			#ixt_events td.error { border: 1px solid #F00; background-color: #F00; }
			#ixt_events td.on_fire { border: 1px solid #000; background-color: #000; text-shadow: 0 -2px 1px #FF0, 0 -4px 1px #FD0, 0 -6px 1px #FB0, 0 -8px 2px #F90, 0 -10px 2px #F70; font-weight: bold; font-size: 17px; text-align: center; }
		</style>
		<script type="text/javascript">
			if (!window.jQuery)	{
				document.write('<scr' + 'ipt src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></scr'+'ipt>');
			}
		</script>
		<script type="text/javascript">
			jQuery(function($) {
				$.each(profiler_log, function(index, value) {
					$('#ixt_events tbody').append(
						'<tr>' + 
							'<td class="type ' + value.type.toLowerCase() + '">&nbsp;</td>' +
							'<td>' + value.nice_time + '</td>' +
							'<td>' + value.from + '</td>' +
							'<td>' + value.action + '</td>' + 
							'<td>' + value.description + '</td>' +
						'</tr>'
					);
				});					
			});
		</script>
		
		<div id="ixt_profiler">
			<h1>IgniteXT Profiler</h1>

			<table id="ixt_events">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Time</th>
						<th>From</th>
						<th>Action</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<?php
	}
}