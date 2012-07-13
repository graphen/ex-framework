<?php

/**
 * @interface II18n
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface II18n {
	
	/**
	 * Testuje kod jezyka
	 * 
	 * @access public
	 * @param string Kod jezyka
	 * @return bool
	 * 
	 */	
	public function isValidLanguageCode($languageCode);	
	
	/**
	 * Testuje kod kraju
	 * 
	 * @access public
	 * @param string Kod kraju
	 * @return bool
	 * 
	 */			
	public function isValidCountryCode($countryCode);	
	
	/**
	 * Laduje plik tlumaczenia
	 * 
	 * @access public
	 * @param string Sciezka do pliku. Domyslnie null
	 * @return void
	 * 
	 */			
    public function addLanguageFile($fileName=null);	
	
	/**
	 * Ustawia zmienna LOCALE
	 * 
	 * @access public
	 * @param string Wartosc zmiennej LOCALE
	 * @return void
	 * 
	 */		
	public function setLocale($locale);	
	
	/**
	 * Zwraca wartosc zmiennej LOCALE
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getLocale();	
	
	/**
	 * Zwraca kod jezyka
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getLanguageCode();	
	
	/**
	 * Zwraca kod kraju
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getCountryCode();	
	
	/**
	 * Zwraca nazwe uzywanego jezyka
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getLanguageName($languageCode='');	
	
	/**
	 * Zwraca nazwe kraju
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getCountryName($countryCode='');	
	
	/**
	 * Zwraca obiekt translatora
	 * 
	 * @access public
	 * @return object
	 * 
	 */		
	public function getTranslator();	
	
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

}

?>
