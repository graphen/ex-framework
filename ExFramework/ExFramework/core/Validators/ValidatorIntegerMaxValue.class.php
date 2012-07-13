<?php

/**
 * @class ValidatorIntegerMaxValue
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ValidatorIntegerMaxValue extends ValidatorAbstract implements IValidator {

	/**
	 * Komunikat
	 *
	 * @var string
	 * 
	 */		
	protected $_message = 'Integer is too big';
	
	/**
	 * Maksymalna wielkosc liczby
	 *
	 * @var int
	 * 
	 */			
	protected $_maxValue = null;
	
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
	 * Sprawdza czy liczba jest mniejsza od zalozonej
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
		if($this->_maxValue === null) {
			throw new ValidatorException('Obiekt walidatora nie zostal poprawnie skonfigurowany');
		} 		
		if(is_array($value) || is_object($value)) {
			$this->_errors[$varId][] = $this->_message;
			return false;			
		}		
		if((int) $value > $this->_maxValue) {
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
		if(isset($params['maxValue'])) {
			$this->_maxValue = (int) $params['maxValue'];
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
		$this->_maxValue = null;
		$this->_message = 'Integer is too big';
	}	
	
}

?>
