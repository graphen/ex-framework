<?php

/**
 * @class Query
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Query implements IQuery {
	
	/**
	 * Tablica elementow zapytania
	 *
	 * @var array
	 * 
	 */		
	protected $_selectQuery = array('select' => array(),
									'from' => array(),
									'join' => array(),
									'where' => array(),
									'group' => array(),
									'order' => array(),
									'having' => array(),
									'limit' => null,
									'offset' => null
									);	

	/**
	 * Konstruktor
	 * 
	 * @access public
	 * 
	 */			
	public function __construct() {
		//
	}	
	
	/**
	 * Dodaje nazwe wybieranego pola lub tablice nazw pol do grupy select
	 * 
	 * @access public
	 * @param string|array Wybierane pole lub tablica pol
	 * @return object
	 * 
	 */		
	public function select($arg) {
		if(is_array($arg)) {
			$this->_selectQuery['select'] = array_merge($this->_selectQuery['select'], $arg);
		}
		else {
			$this->_selectQuery['select'][] = $arg;
		}		
		return $this;		 
	} 	
	
	/**
	 * Dodaje nazwe tabeli lub tablice nazw tabel do grupy from
	 * 
	 * @access public
	 * @param string|array Nazwa tabeli lub tablica nazw tabel
	 * @return object
	 * 
	 */			
	public function from($arg) {
		if(is_array($arg)) {
			foreach($arg AS $index=>$a) {
				if(in_array($a, $this->_selectQuery['from'])) {
					unset($arg[$index]);
				}
			}
			$this->_selectQuery['from'] = array_merge($this->_selectQuery['from'], $arg);
		}
		else {
			if(!in_array($arg, $this->_selectQuery['from'])) {
				$this->_selectQuery['from'][] = $arg;
			}
		}		
		return $this;		 
	} 	
	
	/**
	 * Dodaje nazwe pola lub tablice nazw pol po ktorych nastapi grupowanie, do grupy group
	 * 
	 * @access public
	 * @param string|array Wybierane pole lub tablica pol
	 * @return object
	 * 
	 */			
	public function group($arg) {
		if(is_array($arg)) {
			$this->_selectQuery['group'] = array_merge($this->_selectQuery['group'], $arg);
		}
		else {
			$this->_selectQuery['group'][] = $arg;
		}		
		return $this;		 
	} 	
	
	/**
	 * Dodaje nazwe pola lub tablice nazw pol po ktorych nastapi sortowanie, do grupy order
	 * 
	 * @access public
	 * @param string|array Wybierane pole lub tablica pol
	 * @return object
	 * 
	 */				
	public function order($arg) {
		if(is_array($arg)) {
			$this->_selectQuery['order'] = array_merge($this->_selectQuery['order'], $arg);
		}
		else {
			$this->_selectQuery['order'][] = $arg;
		}		
		return $this;		 
	} 	
	
	/**
	 * Dodaje nazwe fragmenty zapytania zawierajace nazwy dolaczanych tabel i pol do grupy join
	 * 
	 * @access public
	 * @param string|array Fragment zapytania
	 * @return object
	 * 
	 */		
	public function join($arg) {
		if(is_array($arg)) {
			$this->_selectQuery['join'] = array_merge($this->_selectQuery['join'], $arg);
		}
		else {
			$this->_selectQuery['join'][] = $arg;
		}		
		return $this;		 
	} 	

	/**
	 * Dodaje fragmenty zapytania zawierajace warunki wyboru rekordow lub tablice warunkow laczonyc pozniej operatorem AND do grupy where
	 * 
	 * @access public
	 * @param string|array Fragment zapytania z warunkami
	 * @return object
	 * 
	 */	
	public function where() {
		$args = func_get_args();
		if(is_array($args[0])) {
			$this->_selectQuery['where'] = array_merge($this->_selectQuery['where'], $args[0]);
		}
		else {			
			$this->_selectQuery['where'][] = $args[0];
		}
		return $this;	 
	} 
	
	/**
	 * Dodaje fragmenty zapytania zawierajace warunki wyboru rekordow lub tablice warunkow laczonyc pozniej operatorem AND do grupy having
	 * 
	 * @access public
	 * @param string|array Fragment zapytania z warunkami
	 * @return object
	 * 
	 */		
	public function having() {
		$args = func_get_args();
		if(is_array($args[0])) {
			$this->_selectQuery['having'] = array_merge($this->_selectQuery['having'], $args[0]);
		}
		else {			
			$this->_selectQuery['having'][] = $args[0];
		}
		return $this;	 
	}
	
	/**
	 * Dodaje liczbe rekordow do wybrania do pola limit
	 * 
	 * @access public
	 * @param int Liczba rekordow
	 * @return object
	 * 
	 */		
	public function limit($arg) {
		$this->_selectQuery['limit'] = $arg;
		return $this;
	}
	
	/**
	 * Dodaje offset od ktorego zacznie sie pobieranie rekordow do pola offset
	 * 
	 * @access public
	 * @param int Offset
	 * @return object
	 * 
	 */	
	public function offset($arg) {
		$this->_selectQuery['offset'] = $arg;
		return $this;
	}
	
	/**
	 * Zwraca zbudowane z fragmentow zapytanie
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function query() {
		$query = $this->buildSelectQuery();
		$this->cleanQuery();
		return $query;
	}
	
	/**
	 * Buduje zapytanie
	 * 
	 * @access protected
	 * @return string
	 * 
	 */	
	protected function buildSelectQuery() {
		$query = "";
		$query .= "SELECT";
		if(count($this->_selectQuery['select']) == 0) {
			$query .= " *";
		}
		else {
			$select = implode(', ', $this->_selectQuery['select']);
			$query .= " $select";
		}
		if(count($this->_selectQuery['from']) < 1) {
			throw new QueryException('Nie podano nazwy tabeli. Nie mozna utworzyc zapytania');
		}
		else {
			$from = implode(', ', $this->_selectQuery['from']);
			$query .= " FROM $from";
		}
		if(count($this->_selectQuery['join']) != 0) {
			$join = implode(' ', $this->_selectQuery['join']);
			$query .= " $join";
		}
		if(count($this->_selectQuery['where']) != 0) {
			$where = implode(' AND ', $this->_selectQuery['where']);
			$query .= " WHERE $where";
		}
		if(count($this->_selectQuery['group']) != 0) {
			$group = implode(', ', $this->_selectQuery['group']);
			$query .= " GROUP BY $group";
		}
		if(count($this->_selectQuery['order']) != 0) {
			$order = implode(', ', $this->_selectQuery['order']);
			$query .= " ORDER BY $order";
		}
		if(count($this->_selectQuery['having']) != 0) {
			$having = implode(' AND ', $this->_selectQuery['having']);
			$query .= " HAVING $having";
		}
		if(isset($this->_selectQuery['limit'])) {
			if(isset($this->_selectQuery['offset'])) {
				$query .= " LIMIT " . $this->_selectQuery['limit'] . "," . $this->_selectQuery['offset'];
			}
			else {	 
				$query .= " LIMIT " . $this->_selectQuery['limit'];
			}
		}
		return $query;
	}
	
	/**
	 * Resetuje tablice elementow zapytania
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function cleanQuery() {
		$this->_selectQuery = array('select' => array(),
									'from' => array(),
									'join' => array(),
									'where' => array(),
									'group' => array(),
									'order' => array(),
									'having' => array(),
									'limit' => null,
									'offset' => null,
									'params' => array()
									);			
	}
	
}

?>
