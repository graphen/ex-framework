<?php

/**
 * @class ValidatorPesel
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ValidatorPesel extends ValidatorAbstract implements IValidator {
	
	/**
	 * Komunikat
	 *
	 * @var string
	 * 
	 */		
	protected $_message = 'It is not valid Polish PESEL number';

	/**
	 * Plec
	 *
	 * @var string
	 * 
	 */		
	protected $_sex = null;	
	
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
	 * Sprawdza poprawnosc numeru PESEL
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
		if(is_array($value) || is_object($value)) { //obiekty i tablice nie sa przyjmowane
			$this->_errors[$varId][] = $this->_message;
			return false;			
		}	
		if(preg_match('/[^0-9]+/i', $value)) { //pesel sklada sie tylko z cyfr
			$this->_errors[$varId][] = $this->_message;
			return false;			
		}			
		if(strlen($value) < 11) { //pesel to 11 cyfr
			$this->_errors[$varId][] = $this->_message;
			return false;
		}
		if($this->_sex !== null) { //jesli podano plec, to mozna przeprowadzic dodatkowy prosty test
			if($value[9] % 2 AND $this->_sex == "K") { 
				$this->_errors[$varId][] = $this->_message;
				return false;
			}
			if (!$value[9] % 2 AND $this->_sex == "M") {
				$this->_errors[$varId][] = $this->_message;
				return false;
			}
		}
		$w = array(1, 3, 7, 9); //wagi 1379137913
		$s = 0;
		for ($i = 0; $i <= 9; $i++) {
			$s += $value[$i] * $w[$i % 4];
		}
		$result = $s % 10;
		$result = 10 - $result;
		$result = $result % 10;	
		if($value[10] == $result) {
			return true;
		}
		$this->_errors[$varId][] = $this->_message;
		return false;
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
		if(isset($params['sex'])) {
			$this->_sex = (string) $params['sex'];
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
		$this->_sex = null;
		$this->_message = 'It is not valid Polish PESEL number';
	}	
	
}

?>
