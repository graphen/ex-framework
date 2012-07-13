<?php

/**
 * @class Collection
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Collection implements ICollection {
	
	/**
	 * Tablica obiektow
	 *
	 * @var array
	 * 
	 */		
	protected $_entities = array();
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * 
	 */			
	public function __construct() {
		//
	}
		
	/**
	 * Zwraca tablice obiektow
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function getEntities() {
		return $this->_entities;
	}
	
	/**
	 * Czysci tablice obiektow
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function clear() {
		$this->_entities = array();
	}
	
	/**
	 * Dodaje obiekt do kolekcji
	 * 
	 * @access public
	 * @param object Dodawany obiekt
	 * @param string Klucz identyfikujacy dodawany obiekt
	 * @return void
	 * 
	 */		
	public function add(IEntity $entity, $key=null) {
		$this->offsetSet($entity, $key);
	}
	
	/**
	 * Usuwa obiekt z kolekcji
	 * 
	 * @access public
	 * @param string Klucz identyfikujacy usuwany obiekt
	 * @return void
	 * 
	 */		
	public function remove($key) {
		$this->offsetUnset($key);
	}
	
	/**
	 * Zwraca obiekt z kolekcji
	 * 
	 * @access public
	 * @param string Klucz identyfikujacy obiekt
	 * @return object
	 * 
	 */		
	public function get($key) {
		$this->offsetGet($key);
	}	
	
	/**ArrayAccess**/
	
	/**
	 * Zwraca obiekt identyfikowany przez klucz
	 * 
	 * @access public
	 * @param string Klucz identyfikujacy obiekt
	 * @return object
	 * 
	 */		
	public function offsetGet($key) {
		if(array_key_exists($key, $this->_entities)) {
			return $this->_entities[$key];
		}
	}
	
	/**
	 * Dodaje obiekt do kolekcji
	 * 
	 * @access public
	 * @param object Dodawany obiekt  
	 * @param string Klucz identyfikujacy dodawany obiekt
	 * @return void
	 * 
	 */		
	public function offsetSet($entity, $key=null) {
		if(!$entity instanceof EntityAbstract) {
			return;
		}
		if($key === null) {
			$this->_entities[] = $entity;
		}
		else {
			if(!array_key_exists($key, $this->_entities)) {
				$this->_entities[$key] = $entity;
			}
		}
	}
	
	/**
	 * Usuwa obiekt z kolekcji
	 * 
	 * @access public
	 * @param string Klucz identyfikujacy usuwany obiekt
	 * @return void
	 * 
	 */			
	public function offsetUnset($key) {
		if($key instanceof EntityAbstract) {
			$entities = array();
			foreach($this->_entities AS $en) {
				if($en !== $key) {
					$entities[] = $en;
				}
			}
			$this->_entities = $entities;
		}
		else {
			if(array_key_exists($key, $this->_entities)) {
				unset($this->_entities[$key]);
			}	
		}
	}
	
	/**
	 * Sprawdza istnienie obiektu w kolekcji
	 * 
	 * @access public
	 * @param string Klucz identyfikujacy poszukiwany obiekt
	 * @return void
	 * 
	 */			
	public function offsetExists($key) {
		return array_key_exists($key, $this->_entities);
	}
	
	/**Countable**/
	
	/**
	 * Zwraca liczbe obiektow w kolekcji
	 * 
	 * @access public
	 * @return int
	 * 
	 */			
	public function count() {
		return count($this->_entities);
	}
	
	/**Iterator**/
	
	/**
	 * Ustawia kursor na pierwszym elemencie tablicy
	 * 
	 * @access public
	 * @return void
	 * 
	 */			
	public function rewind() {
		reset($this->_entities);
	}
	
	/**
	 * Zwraca aktualny element tablicy
	 * 
	 * @access public
	 * @return object
	 * 
	 */			
	public function current() {
		return current($this->_entities);
	}
	
	/**
	 * Przesuwa kursor na nastepny element tablicy
	 * 
	 * @access public
	 * @return void
	 * 
	 */			
	public function next() {
		next($this->_entities);
	}
	
	/**
	 * Zwraca aktualny klucz talicy
	 * 
	 * @access public
	 * @return mixed
	 * 
	 */
	public function key() {
		return key($this->_entities);
	}
	
	/**
	 * Sprawdza istnienie elementu tablicy
	 * 
	 * @access public
	 * @return bool
	 * 
	 */			
	public function valid() {
		return key($this->_entities) !== null;
	}
	
	/**
	 * Zwraca zawartosc kolekcji w formie informacyjnej
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function __toString() {
		$str = "";
		$str .= "<pre>" . print_r($this->_entities, true) . "</pre><br />";
		return $str;
	}
	
}

?>
