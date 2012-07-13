<?php

/**
 * @class ValidatorAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class ValidatorAbstract implements IValidator {
	
	/**
	 * Tabela bledow
	 *
	 * @var array
	 * 
	 */			
	protected $_errors = array();
	
	/**	
	 * Sprawdza istnienie bledow
	 * 
	 * @access public
	 * @return bool
	 * 
	 */		
	public function hasErrors() {
		if(count($this->_errors) > 0) {
			return true;
		}
		return false;
	}
	
	/**	
	 * Zwraca tablice bledow
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getErrors() {
		return $this->_errors;
	}

	/**	
	 * Resetuje tablice bledow
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function reset() {
		return $this->_errors = array();
	}
	
}

?>
