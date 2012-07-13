<?php

/**
 * @class IocArgMap
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class IocArgMap {

	/**
	 * Nazwa argumentu
	 *
	 * @var string
	 * 
	 */	
	protected $_argName = null;
	
	/**
	 * Wartosc argumentu
	 *
	 * @var mixed
	 * 
	 */		
	protected $_argValue = null;
	
	/**
	 * Typ argumentu
	 *
	 * @var string
	 * 
	 */		
	protected $_argType = null;

	/**
	 * 
	 * Konstruktor
	 * 
	 * @param mixed Wartosc argumentu
	 * @param string Typ argumentu
	 * @param string default null Nazwa argumentu 
	 * @access public
	 * 
	 */	
	public function __construct($value, $type, $name=null) {
		$this->_argName = $name;
		$this->_argValue = $value;
		$this->_argType = $type;
	}

	/**
	 * 
	 * Zwraca nazwe argumentu
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getArgName() {
		return $this->_argName;
	}

	/**
	 * 
	 * Zwraca wartosc argumentu
	 * 
	 * @access public
	 * @return mixed
	 * 
	 */		
	public function getArgValue() {
		return $this->_argValue;
	}
	
	/**
	 * 
	 * Zwraca typ argumentu
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getArgType() {
		return $this->_argType;
	}
	
	/**
	 * 
	 * Ustawia nazwe argumentu
	 * 
	 * @access protected
	 * @param string Nazwa argumentu
	 * @return void
	 * 
	 */		
	protected function setArgName($name) {
		$this->_argName = $name;
	}

	/**
	 * 
	 * Ustawia wartosc argumentu
	 * 
	 * @access protected
	 * @param mixed Wartosc argumentu
	 * @return void
	 * 
	 */			
	protected function setArgValue($value) {
		$this->_argValue = $value;
	}
	
	/**
	 * 
	 * Ustawia typ argumentu
	 * 
	 * @access protected
	 * @param string Nazwa argumentu
	 * @return void
	 * 
	 */			
	protected function setArgType($type) {
		$this->_argType = $type;
	}
	
}

?>
