<?php

/**
 * @class ValidatorStringMaxLength
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ValidatorStringMaxLength extends ValidatorAbstract implements IValidator {

	/**
	 * Komunikat
	 *
	 * @var string
	 * 
	 */		
	protected $_message = 'String is too long';
	
	/**
	 * Maksymalna dlugosc ciagu
	 *
	 * @var integer
	 * 
	 */			
	protected $_maxLength = null;	
	
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
	 * Sprawdza czy ciag jest krotszy od podanej wartosci
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
		if($this->_maxLength === null) {
			throw new ValidatorException('Obiekt walidatora nie zostal poprawnie skonfigurowany');
		} 
		if(is_array($value) || is_object($value)) {
			$this->_errors[$varId][] = $this->_message;
			return false;			
		}		
		if(strlen($value) > $this->_maxLength) {
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
		if(isset($params['maxLength'])) {
			$this->_maxLength = (int) $params['maxLength'];
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
		$this->_maxLength = null;
		$this->_message = 'String is too long';
	}	
	
}

?>
