<?php
/**
 * Query Builder
 *
 * Uses methods to build database queries
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

class Query_Builder extends Entity
{
	protected $action = null;
	protected $table = null;
	protected $distinct = false;
	protected $columns = array();
	protected $joins = array();
	protected $wheres = array();
	protected $sets = array();
	protected $group_bys = array();
	protected $havings = array();
	protected $limit = null;
	protected $offset = null;
	protected $arguments = array();

	/**
	 * Resets the Query Builder object
	 */
	public function reset() 
	{
		$this->action = null;
		$this->table = null;
		$this->distinct = false;
		$this->columns = array();
		$this->joins = array();
		$this->wheres = array();
		$this->sets = array();
		$this->group_bys = array();
		$this->havings = array();
		$this->limit = null;
		$this->offset = null;
		$this->arguments = array();
	}

	public function select()
	{
		$this->action = 'SELECT';
		$columns = func_get_args();
		if (!empty($columns)) call_user_func_array(array($this, 'columns'), $columns);
		return $this;
	}

	public function update($table = null)
	{
		$this->action = 'UPDATE';
		if (!empty($table)) $this->table($table);
		return $this;
	}

	public function distinct($distinct = true)
	{
		$this->distinct = $distinct;
		return $this;
	}

	public function from($table)
	{
		return $this->table($table);
	}

	public function table($table)
	{
		$this->table = $table;
		return $this;
	}

	public function join()
	{
		$joins = $this->array_of_arrays(func_get_args());
		if (empty($joins)) return $this;
		
		foreach ($joins as &$join) {
			$join[0] = $this->backtick($join[0]); //table
			$join[1] = $this->backtick($join[1]); //column A
			$join[3] = $this->backtick($join[3]); //column B
		}
		unset($join);

		$this->joins = array_merge($this->joins, $joins);
		return $this;
	}

	public function columns()
	{
		$columns = func_get_args();
		if (empty($columns)) return $this;
		if (is_array($columns[0])) $columns = $columns[0];
		//Can't array map because php 5.4
		foreach ($columns as &$column) {
			$column_parts = explode(' AS ', $column);
			if (count($column_parts) > 1) $column = $this->backtick($column_parts[0]) . ' AS ' . $this->backtick($column_parts[1]);
			else $column = $this->backtick($column);
		}
		unset($column);
		$this->columns = array_merge($this->columns, $columns);
		return $this;
	}

	public function where()
	{
		$wheres = $this->array_of_arrays(func_get_args());
		if (empty($wheres)) return $this;
		$this->wheres = array_merge($this->wheres, $wheres);
		return $this;
	}

	public function group_by()
	{
		$group_bys = func_get_args();
		if (empty($group_bys)) return $this;
		if (is_array($group_bys[0])) $group_bys = $group_bys[0];
		$group_bys = array_map(function($group_by) { return '`' . $group_by . '`'; }, $group_bys);
		$this->group_bys = $group_bys;
		return $this;
	}

	public function compile()
	{
		if (empty($this->table)) throw new \Exception('No table specified.');
		if (empty($this->action)) throw new \Exception('No query action specified.');
		if (empty($this->columns)) throw new \Exception('No columns specified.');

		$this->arguments = array();
		$query = '';

		switch ($this->action)
		{
			case 'SELECT': 
				$query = 'SELECT ';
				if ($this->distinct) $query .= 'DISTINCT ';
				$query .= implode(',', $this->columns) . ' ';
				$query .= 'FROM `' . $this->table . '` ';
				$query .= $this->compile_joins();
				$query .= $this->compile_wheres();
				if (!empty($this->group_bys)) $query .= 'GROUP BY ' . implode(', ', $this->group_bys);
				break;

			case 'UPDATE':
				$query .= 'UPDATE ' . $this->table . ' SET ';
				$last_set_index = count($this->set) - 1;
				foreach ($this->set as $i => $set) {
					$query .= '`' . $set[0] . '` = ?';
					$this->arguments[] = $set[1];
				}
				break;
		}

		return rtrim($query);
	}

	protected function compile_wheres()
	{
		$query = '';
		if (!empty($this->wheres)) $query .= 'WHERE ';
		$last_where_index = count($this->wheres) - 1;
		foreach ($this->wheres as $i => $where) {
			list($column, $operator, $value) = $where;
			if ($value !== null) {
				$query .= '`' . $where[0] . '` ';
				$query .= $where[1] . ' ';
				$query .= '? ';
				$this->arguments[] = $where[2];
			}
			else {
				$query .= '`' . $where[0] . '` IS ';
				if ($operator == '!=' || $operator == '<>' || $operator == 'NOT') $query .= 'NOT ';
				$query .= 'NULL ';
			}
			if ($i < $last_where_index) $query .= 'AND ';
		}
		return $query;
	}

	protected function compile_joins()
	{
		if (empty($this->joins)) return '';
		$query = '';
		foreach ($this->joins as $join) {
			$query .= 'JOIN ' . $join[0] . ' ON ' . $join[1] . $join[2] . $join[3] . ' ';
		}
		return $query;
	}

	protected function backtick($str) 
	{
		return '`' . $str . '`';
	}

	/**
	 * The query builder methods are flexible with their input.  For methods like 'join' and 'where',
	 * there are three ways arguments can be given:
	 *
	 * 1. List of strings
	 * $q->where('a', '=', 'b');
	 * 
	 * 2. List of arrays
	 * $q->where(['a', '=', 'b'], ['c', '=', 'd']);
	 * 
	 * 3. Array of arrays
	 * $wheres = [ ['a', '=', 'b'], ['c', '=', 'd'] ];
	 * $q->where($wheres);
	 *
	 * This method converts each of these to an array of arrays.  The second format is already an
	 * array of arrays when you do a func_get_args.
	 */
	protected function array_of_arrays($args)
	{
		if (empty($args)) return array();
		if (!is_array($args[0])) $args = array($args); //1st format, wrap in array
		elseif (count($args) == 1) $args = $args[0]; //3rd format, unwrap extra array
		return $args;
	}

	public function arguments()
	{
		return $this->arguments;
	}
}