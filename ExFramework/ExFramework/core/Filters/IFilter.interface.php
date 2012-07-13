<?php

/**
 * @interface IFilter
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IFilter {

	/**
	 * Przeprowadza filtrowanie danych
	 *
	 * @access public
	 * @param mixed Filtrowane dane
	 * @param array Tablica opcji (tymczasowo nie uzywana)
	 * @return mixed
	 * 
	 */		
	public function filter($var, $options=array());

}

?>
