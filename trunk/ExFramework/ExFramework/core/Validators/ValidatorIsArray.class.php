<?php

/**
 * @class ValidatorIsArray
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ValidatorIsArray extends ValidatorAbstract implements IValidator {

	/**
	 * Komunikat
	 *
	 * @var string
	 * 
	 */		
	protected $_message = 'Value must be valid array';
	
	/**
	 * Maksymalna wielkosc tablicy
	 *
	 * @var int
	 * 
	 */			
	protected $_maxLength = null;
	
	/**
	 * Minimalna wielkosc tablicy
	 *
	 * @var int
	 * 
	 */			
	protected $_minLength = null;	
	
	/**
	 * Ilosc niepustych elementow
	 *
	 * @var int
	 * 
	 */			
	protected $_notEmptyCount = null;	
	
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
		if(!is_array($value)) {
			$this->_errors[$varId][] = $this->_message;
			return false;		
		}		
		if($this->_minLength != null) {		
			if(count($value) < $this->_minLength) {
				$this->_errors[$varId][] = $this->_message;
				return false;
			}
		}
		if($this->_maxLength != null) {
			if(count($value) > $this->_maxLength) {
				$this->_errors[$varId][] = $this->_message;
				return false;
			}
		}		
		if($this->_notEmptyCount != null) {
			$count = 0;
			foreach($value AS $v) {
				if($v != '') {
					$count++;
				}
			}
			if($count < $this->_notEmptyCount) {
				$this->_errors[$varId][] = $this->_message;
				return false;
			}
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
		if(isset($params['minLength'])) {
			$this->_minLength = (int) $params['minLength'];
		}
		if(isset($params['maxLength'])) {
			$this->_maxLength = (int) $params['maxLength'];
		}	
		if(isset($params['notEmptyCount'])) {
			$this->_notEmptyCount = (int) $params['notEmptyCount'];
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
		$this->_minLength = null;
		$this->_maxLength = null;
		$this->_notEmptyCount = null;
		$this->_message = 'Value must be valid array';
	}	
	
}

?>
