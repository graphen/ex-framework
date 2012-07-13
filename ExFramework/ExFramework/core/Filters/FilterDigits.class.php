<?php

/**
 * @class FilterDigits
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FilterDigits implements IFilter {

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
		return preg_replace('/[^0-9]*/', '', $var);
	}
	
}

?>
