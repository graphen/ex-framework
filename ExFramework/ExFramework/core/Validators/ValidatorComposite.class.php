<?php

/**
 * @class ValidatorComposite
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ValidatorComposite extends ValidatorAbstract implements IValidatorComposite {
	
	/**
	 * Tabela walidatorow
	 *
	 * @var array
	 * 
	 */				
	protected $_validators = array();

	/**
	 * Tabela dodatkowych opcji
	 *
	 * @var array
	 * 
	 */				
	protected $_options = array();
	
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
	 * Dodaje walidator do tablicy walidatorow
	 * 
	 * @access public
	 * @param array|object (IValiator Obiekt walidatora, array Tablica opcji dla walidatora, bool Czy przerwac lancuch walidacji po napotkaniu bledu)
	 * @return void
	 * 
	 */		
	public function addValidator($validator) {
		if(!is_array($validator)) {
			if(!$validator instanceof IValidator) {
				throw new ValidatorException('Obiekt walidatora musi imlementowac interfejs IValidator');
			}
			$validator = array($validator); //obiekt walidatora
			$validator[1] = array(); //opcje
			$validator[2] = false; //nie bedzie przerywania wykonywania lancucha walidatorow
		}
		else {
			if(!$validator[0] instanceof IValidator) { //obiekt walidatora
				throw new ValidatorException('Obiekt walidatora musi imlementowac interfejs IValidator');
			}
			if((isset($validator[1]) && (!is_array($validator[1]))) || (!isset($validator[1]))) { //jesli drugi arg. nie jest tablica to nie ma dodatkowych opcji
				$validator[1] = array();
			}
			if(isset($validator[2])) {
				$validator[2] = (bool)$validator[2];
			}
			else {
				$validator[2] = false;
			}
		}
		$validator[0]->reset();
		$this->_validators[] = $validator;
	}
	
	/**	
	 * Czysci tablice walidatorow
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function removeValidators() {
		$this->_validators = array();
	}
	
	/**	
	 * Ustawia tablice walidatorow
	 * 
	 * @access public
	 * @param Tablica walidatorow
	 * @return void
	 * 
	 */		
	public function setValidators(Array $validators) {
		$this->removeValidators();
		foreach($validators AS $validator) {
			$this->addValidator($validator);
		}
	}
	
	/**	
	 * Zwraca tablice walidatorow
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function getValidators() {
		return $this->_validators;
	}
	
	/**	
	 * Sprawdza poprawnosc przeslanej w parametrze wartosci
	 * 
	 * @access public
	 * @param string Identyfikator sprawdzanej zmiennej
	 * @param mixed Sprawdzana zmienna
	 * @param array Tablica opcji/parametrow
	 * @return bool
	 * 
	 */		
	public function isValid($varName, $value, $options=array()) {
		$this->_errors = array(); //resetowanie tablicy bledow
		$this->_options = $options;
		foreach($this->_validators AS $validator) { //testowanie walidatorow
			if($validator[0]->isValid($varName, $value, $validator[1])) { //jesli brak bledow
				continue; //do nastepnego walidatora
			}
			else { //w innym przypadku
				$errorsFromValidator = $validator[0]->getErrors(); //pobranie bledow z obiektu walidatora
				foreach($errorsFromValidator[$varName] AS $error) { //i wpisanie ich do tablicy bledow obiektu walidatora kompozytu  
					$this->_errors[$varName][] = $error;
				}
				if($validator[2] === true) { //jesli ustawiono mozliwosc przerwania lancucha walidatorow to tutaj zostanie on przerwany
					break;
				}
			}
		}
		if($this->hasErrors() === false) {
			return true;
		}
		return false;
	}
		
}

?>
