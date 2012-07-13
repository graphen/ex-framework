<?php

/**
 * @interface ICollection
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface ICollection extends ArrayAccess, Countable, Iterator {
			
	/**
	 * Zwraca tablice obiektow
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function getEntities();
	
	/**
	 * Czysci tablice obiektow
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function clear();
	
	/**
	 * Dodaje obiekt do kolekcji
	 * 
	 * @access public
	 * @param object Dodawany obiekt
	 * @param string Klucz identyfikujacy dodawany obiekt
	 * @return void
	 * 
	 */		
	public function add(IEntity $entity, $key=null);
	
	/**
	 * Usuwa obiekt z kolekcji
	 * 
	 * @access public
	 * @param string Klucz identyfikujacy usuwany obiekt
	 * @return void
	 * 
	 */		
	public function remove($key);
	
	/**
	 * Zwraca obiekt z kolekcji
	 * 
	 * @access public
	 * @param string Klucz identyfikujacy obiekt
	 * @return object
	 * 
	 */		
	public function get($key);
}

?>
