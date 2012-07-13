<?php

/**
 * @interface ICache
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface ICache {

	/**
	 * Metoda ustawia czas zycia cache
	 * 
	 * @access public
	 * @param int Czas w sekundach
	 * @return void
	 *  
	 */	
	public function setLifeTime($lifeTime);
	
	/**
	 * Metoda zwraca czas zycia cache
	 * 
	 * @access public
	 * @return int
	 *  
	 */		
	public function getLifeTime();

	/**
	 * Metoda odczytuje z cache dane identyfikowane podanym identyfikatorem i zwraca je. Mozna odczytac jednoczesnie takze cala tablice
	 * 
	 * @access public
	 * @param string Identyfikator cache, lub tablica z identyfikatorami
	 * @return mixed|false
	 *  
	 */	
	public function fetch($id);
	
	/**
	 * Metoda zapisuje dane identyfikowane przez podany identyfikator w cache
	 * 
	 * @access public
	 * @param string Identyfikator cache 
	 * @param mixed Dane do zapisania
	 * @param int Czas zycia cache
	 * @return void
	 *  
	 */	
	public function store($id, $data, $lifeTime=null);
	
	/**
	 * Metoda sprawdza czy cache dla danego identyfikatora istnieje
	 * 
	 * @access public
	 * @param string Identyfikator cache
	 * @return bool
	 * 
	 */	
	public function exists($id);
	
	/**
	 * Metoda usuwa cache zawierajacy dane identyfikowane podanym identyfikatorem 
	 * 
	 * @access public
	 * @param string Identyfikator cache
	 * @return void
	 * 
	 */		
	public function delete($id);
	
	/**
	 * Metoda usuwa caly cache
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function clear();
	
}

?>
