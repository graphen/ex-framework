<?php

/**
 * @interface IRelationMapper
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IRelationMapper extends Countable, IteratorAggregate {
	
	/**
	 * Ustawia nazwe klasy mappera
	 * 
	 * @access public
	 * @param string Nazwa klasy
	 * @return void
	 * 
	 */		
	public function setEntityMapperClassName($className);
	
	/**
	 * Ustawia nazwe klasy mappera powiazanego
	 * 
	 * @access public
	 * @param string Nazwa klasy
	 * @return void
	 * 
	 */		
	public function setEntityRelatedMapperClassName($className);
	
	/**
	 * Ustawia wartosc klucza gl dla obiektu powiazanego
	 * 
	 * @access public
	 * @param int
	 * @return void
	 * 
	 */		
	public function setEntityRelatedPk($pk);
	
	/**
	 * Zwraca kolekcje
	 * 
	 * @access public
	 * @return object
	 * 
	 */	
	public function getCollection();
	
}

?>
