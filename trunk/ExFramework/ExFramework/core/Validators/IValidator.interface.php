<?php

/**
 * @interface IValidator
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IValidator {
	
	/**
	 * Sprawda czy wysapily bledy walidacji
	 *
	 * @access public
	 * @return bool
	 * 
	 */				
	public function hasErrors();
	
	/**
	 * Zwraca tablice bledow walidacji
	 *
	 * @access public
	 * @return array
	 * 
	 */				
	public function getErrors();
	
	/**
	 * Resetuje obiekt walidatora
	 *
	 * @access public
	 * @return void
	 * 
	 */				
	public function reset();
	
	/**
	 * Przeprowadza weryfikacje danych przekazanych w formie tablicy asocjacyjnej
	 *
	 * @access public
	 * @param string identyfikator zmiennej, w ValidatorInput, tylko dla zachowania interfejsu
	 * @param mixed Dane do walidacji
	 * @param array Tablica opcji/parametrow
	 * @return void
	 * 
	 */			
	public function isValid($varName, $value, $options=array());
	
}

?>
