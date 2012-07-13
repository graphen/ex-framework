<?php

/**
 * @interface IQuery
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IQuery {
	
	/**
	 * Dodaje nazwe wybieranego pola lub tablice nazw pol do grupy select
	 * 
	 * @access public
	 * @param string|array Wybierane pole lub tablica pol
	 * @return object
	 * 
	 */		
	public function select($arg);
	
	/**
	 * Dodaje nazwe tabeli lub tablice nazw tabel do grupy from
	 * 
	 * @access public
	 * @param string|array Nazwa tabeli lub tablica nazw tabel
	 * @return object
	 * 
	 */			
	public function from($arg);
	
	/**
	 * Dodaje nazwe pola lub tablice nazw pol po ktorych nastapi grupowanie, do grupy group
	 * 
	 * @access public
	 * @param string|array Wybierane pole lub tablica pol
	 * @return object
	 * 
	 */			
	public function group($arg);
	
	/**
	 * Dodaje nazwe pola lub tablice nazw pol po ktorych nastapi sortowanie, do grupy order
	 * 
	 * @access public
	 * @param string|array Wybierane pole lub tablica pol
	 * @return object
	 * 
	 */				
	public function order($arg);
	
	/**
	 * Dodaje nazwe fragmenty zapytania zawierajace nazwy dolaczanych tabel i pol do grupy join
	 * 
	 * @access public
	 * @param string|array Fragment zapytania
	 * @return object
	 * 
	 */		
	public function join($arg);

	/**
	 * Dodaje fragmenty zapytania zawierajace warunki wyboru rekordow lub tablice warunkow laczonyc pozniej operatorem AND do grupy where
	 * 
	 * @access public
	 * @param string|array Fragment zapytania z warunkami
	 * @return object
	 * 
	 */	
	public function where();
	
	/**
	 * Dodaje fragmenty zapytania zawierajace warunki wyboru rekordow lub tablice warunkow laczonyc pozniej operatorem AND do grupy having
	 * 
	 * @access public
	 * @param string|array Fragment zapytania z warunkami
	 * @return object
	 * 
	 */		
	public function having();
	
	/**
	 * Dodaje liczbe rekordow do wybrania do pola limit
	 * 
	 * @access public
	 * @param int Liczba rekordow
	 * @return object
	 * 
	 */		
	public function limit($arg);
	
	/**
	 * Dodaje offset od ktorego zacznie sie pobieranie rekordow do pola offset
	 * 
	 * @access public
	 * @param int Offset
	 * @return object
	 * 
	 */	
	public function offset($arg);
	
	/**
	 * Zwraca zbudowane z fragmentow zapytanie
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function query();
	
}

?>
