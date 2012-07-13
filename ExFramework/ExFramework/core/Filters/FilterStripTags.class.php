<?php

/**
 * @class FilterStripTags
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FilterStripTags implements IFilter {
	
	/**
	 * Lista tagow
	 *
	 * @var string
	 * 
	 */	
	protected $_allowedTags = '';
	
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
	 * Ustawienie dopuszczonej listy tagow
	 *
	 * @access public
	 * @param string Dopuszczona lista tagow
	 * @return void
	 * 
	 */		
	public function setAllowedTags($allowedTags='') {
		$this->_allowedTags = $allowedTags;
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
		if(isset($options['allowedTags'])) {
			$this->setAllowedTags($options['allowedTags']);
		}
		if(is_array($var)) {
			$tmpArray = array();
			foreach($var as $k => $v) {
				$tmpArray[$k] = $this->filter($v);
			}
			return $tmpArray;
		}
		return strip_tags($var, $this->_allowedTags);
	}
	
}

?>
