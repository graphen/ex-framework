<?php

/**
 * @class ValidatorPolishPostalCode
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ValidatorPolishPostalCode extends ValidatorAbstract implements IValidator {
	
	/**
	 * Komunikat
	 *
	 * @var string
	 * 
	 */		
	protected $_message = 'It is not valid Polish postal code';
	
	
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
	 * Sprawdza poprawnosc kodu pocztowego w Polsce
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
		if(is_array($value) || is_object($value)) {
			$this->_errors[$varId][] = $this->_message;
			return false;			
		}		
		if(!preg_match("/^([0-9]{2})-([0-9]{3})$/", $value)) {
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
		$this->_message = 'It is not valid Polish postal code';
	}		
	
}

?>
