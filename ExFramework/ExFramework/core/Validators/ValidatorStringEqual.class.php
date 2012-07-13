<?php

/**
 * @class ValidatorStringEqual
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ValidatorStringEqual extends ValidatorAbstract implements IValidator {
	
	/**
	 * Komunikat
	 *
	 * @var string
	 * 
	 */		
	protected $_message = 'Strings are not equal';
	
	
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
	 * Porownuje dwa ciagi, i zwraca prawde jesli sa rowne
	 * 
	 * @access public
	 * @param string Identyfikator zmiennej
	 * @param mixed Wartosc sprawdzanej zmiennej
	 * @param array Tablica opcji/parametrow
	 * @return bool
	 * 
	 */
	public function isValid($varId, $value, $options=array()) {
		$this->reset();		
		if(count($options) > 0) {
			$this->setParams($options);
		}		
		if((!is_array($value)) || (count($value) != 2)) {
			throw new ValidatorException('Argument musi byc dwuelementowa tablica');
		}
		$tmpArr = array();
		foreach($value AS $v) {
			$tmpArr[] = $v;
		}
		if($tmpArr[0] !== $tmpArr[1]) {
			$this->_errors[$varId][] = $this->_message;
			return false;
		}
		return true;
	}
	
	/**	
	 * Ustawia parametry walidatora
	 * 
	 * @access public
	 * @param Tablica parametrow
	 * @return void
	 * 
	 */	
	public function setParams($params=array()) {
		if(isset($params['message'])) {
			$this->_message = (string) $params['message'];
		}
	}
	
	/**	
	 * Resetuje ustawienia walidatora
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function reset() {
		parent::reset();
		$this->_message = 'Strings are not equal';
	}		
	
}

?>
