<?php

/**
 * @class FilterStandarizeEndLines
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FilterStandarizeEndLines implements IFilter {

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
		if(strpos($var, "\r")) {
			$var = str_replace("\r\n", "\r", $var);
			$var = str_replace("\r", "\n", $var);
		}
		return $var;
	}
	
}

?>
