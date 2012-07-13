<?php

/**
 * @class FilterStripGetDissallowedChars
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FilterStripGetDissallowedChars implements IFilter {
	
	/**
	 * Tablica niechianych znakow
	 *
	 * @var array
	 * 
	 */	
	//protected $_disallowedChars = array("`", "@", "#", "$", "%", "^", "&", "=", "+", "/", "\\", "<", ">", ";", ":", "{", "}", "[", "]", "?", "\"");
	protected $_disallowedChars = array("?");

	
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
		return str_replace($this->_disallowedChars, '', $var);
	}
	
}

?>
