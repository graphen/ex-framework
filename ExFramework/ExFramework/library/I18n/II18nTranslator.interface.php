<?php

/**
 * @interface II18nTranslator
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface II18nTranslator {
	
	
	
	/**
	 * Dodaje plik z tlumaczeniem
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */
	public function addLanguageFile($fileName);	
	
	/**
	 * Zwraca tlumaczenie dla podanego argumentu
	 * 
	 * @access public
	 * @param string Haslo do tlumaczenia
	 * @return string
	 * 
	 */			
	public function translate($word);
	
	/**
	 * Zwraca tlumaczenie dla podanego argumentu
	 * 
	 * @access public
	 * @param string Haslo do tlumaczenia
	 * @return string
	 * 
	 */			
	public function _($word);
	
	/**
	 * Pobranie tabeli z tlumaczeniami
	 * Jesli indeksy sie powtarzaja zostana nadpisane
	 *
	 * @access public
	 * @return array
	 * 
	 */	
	public function getTranslations();		

}

?>
