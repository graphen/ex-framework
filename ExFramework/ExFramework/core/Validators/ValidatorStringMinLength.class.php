<?php

/**
 * @class ValidatorStringMinLength
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ValidatorStringMinLength extends ValidatorAbstract implements IValidator {

	/**
	 * Komunikat
	 *
	 * @var string
	 * 
	 */		
	protected $_message = 'String is too short';
	
	/**
	 * Maksymalna dlugosc ciagu
	 *
	 * @var integer
	 * 
	 */			
	protected $_minLength = null;	
	
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
	 * Sprawdza czy ciag jest dluzszy od podanej wartosci
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
		if($this->_minLength === null) {
			throw new ValidatorException('Obiekt walidatora nie zostal poprawnie skonfigurowany');
		} 		
		if(is_array($value) || is_object($value)) {
			$this->_errors[$varId][] = $this->_message;
			return false;			
		}		
		if(strlen($value) < $this->_minLength) {
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
		if(isset($params['minLength'])) {
			$this->_minLength = (int) $params['minLength'];
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
		$this->_message = 'String is too short';
		
	}	
	
}

?>
