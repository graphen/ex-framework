<?php

/**
 * @class FilterStripSlashes
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FilterStripSlashes implements IFilter {
	
	/**
	 * Wlaczone lub wylaczane magic quotes
	 *
	 * @var bool //domyslnie false
	 * 
	 */		
	protected $_magicQuotesGpc = false;

	/**
	 * Konstruktor
	 *
	 * @access public
	 * 
	 */		
	public function __construct() {
		if(get_magic_quotes_gpc()) {
			$this->_magicQuotesGpc = true;
		}
	}

	/**
	 * Filtruje wartosc zmiennej
	 *
	 * @access public
	 * @param mixed Wartosc poddawana filtrowaniu
	 * @param array Dodatkowe opcje
	 * @return mixed
	 * 
	 */		
	public function filter($var, $options=array()) {
		if(is_array($var)) {
			$tmpArray = array();
			foreach($var as $k => $v) {
				$tmpArray[$k] = $this->filter($v);
			}
			return $tmpArray;
		}		
		if($this->_magicQuotesGpc === true) {
			return stripslashes($var);
		}
		return $var;
	}
	
}

?>
