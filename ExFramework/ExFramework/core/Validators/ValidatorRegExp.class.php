<?php

/**
 * @class ValidatorRegExp
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ValidatorRegExp extends ValidatorAbstract implements IValidator {

	/**
	 * Komunikat
	 *
	 * @var string
	 * 
	 */		
	protected $_message = 'String is invalid and doesnt match regular expression';
	
	/**
	 * Wyrazenie regularne
	 *
	 * @var string|array
	 * 
	 */			
	protected $_regExp = null;	
	
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
	 * Sprawdza czy ciag pasuje do wyrazenia / wyrazen regularnych
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
		if($this->_regExp === null) {
			throw new ValidatorException('Obiekt walidatora nie zostal poprawnie skonfigurowany');
		} 		
		if(is_array($value) || is_object($value)) {
			$this->_errors[$varId][] = $this->_message;
			return false;			
		}
		if(is_array($this->_regExp)) {
			foreach($this->_regExp as $ex) {
				if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $ex))) === false) {
					$this->_errors[$varId][] = $this->_message;
					return false;				
				}
			}
		}
		else {
			if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $this->_regExp))) === false) {
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
		if(isset($params['regExp'])) {
			$this->_regExp = (string) $params['regExp'];
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
		$this->_regExp = null;
		$this->_message = 'String is invalid and doesnt match regular expression';
	}	
	
}

?>
