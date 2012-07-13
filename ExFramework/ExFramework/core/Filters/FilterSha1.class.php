<?php

/**
 * @class FilterSha1
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FilterSha1 implements IFilter {
	
	/**
	 * Sol
	 *
	 * @var mixed
	 * 
	 */	
	protected $_salt = null;	
	
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
	 * @param mixed Sol
	 * @return void
	 * 
	 */		
	public function setSalt($salt=null) {
		$this->_salt = $salt;
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
		if(isset($options['salt'])) {
			$this->setSalt($options['salt']);
		}
		if(is_array($var)) {
			$tmpArray = array();
			foreach($var as $k => $v) {
				$tmpArray[$k] = $this->filter($v);
			}
			return $tmpArray;
		}
		if($this->_salt !== null) {
			$var = $this->_salt . $var;
		}		
		return sha1($var);
	}
	
}

?>
