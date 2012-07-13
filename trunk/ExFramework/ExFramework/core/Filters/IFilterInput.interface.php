<?php

/**
 * @class IFilterInput
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IFilterInput extends IFilter {
	
	/**
	 * Ustawia tablice regul filtrowania
	 *
	 * @access public
	 * @param array Tablica asocjacyjna z filtrowania
	 * @return void
	 * 
	 */		
	public function setRules($rules);
	
	/**
	 * Przygotowuje zestaw filtrow
	 *
	 * @access public
	 * @return void
	 * 
	 */		
	public function prepareFilters();
	
}

?>
