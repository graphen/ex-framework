<?php

/**
 * @interface IPaginator
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IPaginator {
	
	/**
	 * Ustawia aktualny numer strony
	 * 
	 * @access public
	 * @param int Numer strony, liczony od zera
	 * @return void
	 * 
	 */
	public function setCurrentPageNumber($currentPageNumber=0);
	
	/**
	 * Ustawia ilosc rekordow
	 * 
	 * @access public
	 * @param int Ilosc rekordow
	 * @return void
	 * 
	 */	
	public function setNumberOfRecords($numberOfRecords);
	
	/**
	 * Ustawia ilosc rekordow na strone
	 * 
	 * @access public
	 * @param int Ilosc rekordow na strone
	 * @return void
	 * 
	 */		
	public function setRecordsPerPage($recordsPerPage=10);
		
	/**
	 * Ustawia maksymalna ilosc wyswietlanych linkow 
	 * 
	 * @access public
	 * @param int Ilosc linkow
	 * @return void
	 * 
	 */		
	public function setMidRange($midRange=10);
	
	/**
	 * Ustawia adres do ktorego beda doklejane zmienne przechowujace numery stron
	 * 
	 * @access public
	 * @param string Adres strony
	 * @return void
	 * 
	 */		
	public function setLink($link);
	
	/**
	 * Ustawia nazwe zmiennej przechowujacej numery stron
	 * 
	 * @access public
	 * @param string Nazwa zmiennej _GET dla numerow stron
	 * @return void
	 * 
	 */		
	public function setGetVarName($getVarName='page');
	
	/**
	 * Ustawia czy generowac linki do pierwszej i ostatniej strony
	 * 
	 * @access public
	 * @param bool Czy generowac linki do krancowych stron
	 * @return void
	 * 
	 */		
	public function setFirstLast($firstLast=true);	
	
	/**
	 * Ustawia delimiter
	 * 
	 * @access public
	 * @param string Delimiter
	 * @return void
	 * 
	 */		
	public function setDelimiter($delimiter='...');
	
	/**
	 * Zwraca limit dla zapytania SQL
	 * 
	 * @access public
	 * @return int
	 * 
	 */			
	public function getLimit();
	
	/**
	 * Zwraca offset dla zapytania SQL
	 * 
	 * @access public
	 * @return int
	 * 
	 */			
	public function getOffset();
	
	/**
	 * Zwraca tablice z numerami stron i informacja o linkach
	 * 
	 * @access public
	 * @return array
	 * 
	 */			
	public function getNavigationArray();
	
	/**
	 * Zwraca obiekt helpera tworzacy linki nawigacyjne
	 * 
	 * @access public
	 * @return object
	 * 
	 */			
	public function getPaginator();	
	
}

?>
