<?php

/**
 * @class IocAppMap
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class IocAppMap {

	/**
	 * Tablica mapowan klas
	 *
	 * @var array
	 * 
	 */
	protected $_classes = array();

	/**
	 * 
	 * Konstruktor
	 * 
	 * @access public
	 * 
	 */			
	public function __construct() {
		//
	}
	
	/**
	 * 
	 * Zwraca obiekt mapowania klasy podanej przez nazwe
	 * 
	 * @access public
	 * @param string Nazwa klasy
	 * @return object
	 * 
	 */		
	public function getClassMap($name) {
		if(isset($this->_classes[$name])) {
			return $this->_classes[$name];
		}
		else {
			throw new IocException('Klasa: ' . $name . ' nie istnieje');
		}
	}
	
	/**
	 * 
	 * Ustawia mapowanie klasy
	 * 
	 * @access public
	 * @param string Klucz bedacy identyfikatorem klasy
	 * @param object Objekt klasy IocClassMap
	 * @return void
	 * 
	 */		
	public function setClassMap($key, IocClassMap $classObject) {
		$this->_classes[$key] = $classObject;
	}
	
	/**
	 * 
	 * Sprawdza czy istnieje mapowanie dla klasy podanej kluczem
	 * 
	 * @access public
	 * @param string Klucz identyfikujacy klase
	 * @return bool
	 * 
	 */		
	public function __isset($key) {
		return isset($this->_classes[$key]);
	}
	
	/**
	 * 
	 * Wyswietla istniejace mapowania klas
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function __toString() {
		$str = "";
		$str .= "<pre>\n";
		$str .= print_r($this->_classes, true);
		$str .= "</pre><br />\n";
		return $str;
	}	
	
}

?>
