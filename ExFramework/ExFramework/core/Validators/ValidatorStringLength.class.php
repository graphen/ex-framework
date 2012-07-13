<?php

/**
 * @class ValidatorStringLength
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ValidatorStringLength extends ValidatorAbstract implements IValidator {

	/**
	 * Komunikat
	 *
	 * @var string
	 * 
	 */		
	protected $_message = 'String is too short or too long';
	
	/**
	 * Dlugosc ciagu
	 *
	 * @var integer
	 * 
	 */			
	protected $_length = null;	
	
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
	 * Sprawdza czy ciag jest dluzszy lub krotszy od podanej wartosci
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
		if($this->_length === null) {
			throw new ValidatorException('Obiekt walidatora nie zostal poprawnie skonfigurowany');
		} 
		if(is_array($value) || is_object($value)) {
			$this->_errors[$varId][] = $this->_message;
			return false;			
		}	
		if((strlen($value) != $this->_length)) {
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
		if(isset($params['length'])) {
			$this->_length = (int) $params['length'];
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
		$this->_length = null;
		$this->_message = 'String is too short or too long';
	}	
	
}

?>
