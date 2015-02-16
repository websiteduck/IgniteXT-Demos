<?php
/**
 * IXT Stopwatch Library
 * 
 * Used to keep track of certain points in time.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

class Stopwatch extends Entity
{
	private $marks = array();
	public function get_marks() { return $this->marks; }
	
	/**
	 * Use the provided string to mark a certain point in time.
	 * 
	 * @param string $mark_id 
	 */
	public function mark($mark_id)
	{
		$this->marks[$mark_id] = microtime(true);
	}
	
	/**
	 * Get the elapsed time between two marked points.  A floating point number
	 * representing seconds is returned.
	 *
	 * @param string $mark_id1
	 * @param string $mark_id2
	 * @return float $seconds 
	 */
	public function elapsed_time($mark_id1, $mark_id2)
	{
		return $this->marks[$mark_id2] - $this->marks[$mark_id1];
	}
	
}