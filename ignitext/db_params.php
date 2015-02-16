<?php
/**
 * Database Params
 * 
 * Keeps track of parameters used in a query so they can be binded later.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

class DB_Params extends Entity
{
	private $params = array();
    
    public function add_if($true, $sql, $params) 
    {
        if (!is_array($params)) $params = array($params);
        if ($true) {
            $this->params = array_merge($this->params, $params);       
            return $sql;
        }
        return '';
    }
    
    public function add($params)
    {
        if (!is_array($params)) $params = array($params);    
        $this->params = array_merge($this->params, $params);
    }
    
    public function get() { return $this->params; }
}