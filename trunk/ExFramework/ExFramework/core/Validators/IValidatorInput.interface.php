<?php

/**
 * @interface IValidatorInput
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IValidatorInput extends IValidator {
		
	/**
	 * Ustawia tablice regul walidacji
	 *
	 * @access public
	 * @param array Tablica asocjacyjna z regulami walidacji
	 * @return void
	 * 
	 */		
	public function setRules(Array $rules);
	
	/**
	 * Ustawia domyslne parametry
	 *
	 * @access public
	 * @param array Tablica asocjacyjna z domyslnymi parametrami
	 * @return void
	 * 
	 */		
	public function setParams($options);
	
	/**
	 * Przygotowuje zestaw walidatorow
	 *
	 * @access public
	 * @return void
	 * 
	 */		
	public function prepareValidators();
	
}

?>
