<?php

/**
 * @class RelationMapper
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class RelationMapper implements IRelationMapper {
	
	/**
	 * Obiekt kolekcji
	 *
	 * @var object
	 * 
	 */			
	protected $_collection = null;
	
	/**
	 * Fabryka obiektow mapperow
	 *
	 * @var object
	 * 
	 */			
	protected $_mapperFactory = null;
	
	/**
	 * Nazwa klasy obiektu mappera obketow biznesowych
	 *
	 * @var string
	 * 
	 */
	protected $_entityMapperClassName = null;
	
	/**
	 * Nazwa klasy mappera obiektow biznesowych powiazanych z poprzednim
	 *
	 * @var string
	 * 
	 */			
	protected $_entityRelatedMapperClassName = null;
	
	/**
	 * Wartosc klucza glownego obiektu powiazanego
	 *
	 * @var int
	 * 
	 */			
	protected $_entityRelatedPk = null;
	
	/**
	 * Obiekt mappera
	 *
	 * @var object
	 * 
	 */			
	protected $_entityMapper = null;
	
	/**
	 * Obiekt mappera dla obiektow powiazanych
	 *
	 * @var object
	 * 
	 */	
	protected $_entityRelatedMapper = null;
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Fabryka obiektow mapperow
	 * 
	 */		
	public function __construct(IFactory $mapperFactory) {
		$this->_mapperFactory = $mapperFactory;
	}
	
	/**
	 * Ustawia nazwe klasy mappera
	 * 
	 * @access public
	 * @param string Nazwa klasy
	 * @return void
	 * 
	 */		
	public function setEntityMapperClassName($className) {
		$this->_entityMapperClassName = $className;
	}	
	
	/**
	 * Ustawia nazwe klasy mappera powiazanego
	 * 
	 * @access public
	 * @param string Nazwa klasy
	 * @return void
	 * 
	 */		
	public function setEntityRelatedMapperClassName($className) {
		$this->_entityRelatedMapperClassName = $className;
	}
	
	/**
	 * Ustawia wartosc klucza gl dla obiektu powiazanego
	 * 
	 * @access public
	 * @param int
	 * @return void
	 * 
	 */		
	public function setEntityRelatedPk($pk) {
		$this->_entityRelatedPk = $pk;
	}
	
	//Countable
	
	/**
	 * Zwraca iosc obiektow biznesowych w kolekcji
	 * 
	 * @access public
	 * @return int
	 * 
	 */		
	public function count() {
		return count($this->getIterator());
	}
	
	//IteratorAggregate
	
	/**
	 * Zwraca obiekty biznesowe kolekcji podczas wywolania w petli
	 * 
	 * @access public
	 * @return object
	 * 
	 */		
	public function getIterator() {
		if($this->_collection == null) {
			if($this->_entityRelatedMapperClassName == null || $this->_entityMapperClassName == null || $this->_entityRelatedPk == null) {
				throw new DataMapperException('Nie podano nazwy mappera obiektu dla ktorego szukane sa obiekty powiazane, wartosci klucza glownego tego obiektu lub nazwy klasy mappera dla szukanych obiektow');
			}
			$this->_entityMapper = $this->_mapperFactory->create($this->_entityMapperClassName);
			$this->_entityRelatedMapper = $this->_mapperFactory->create($this->_entityRelatedMapperClassName);
			$this->_collection = $this->_entityMapper->getRelated($this->_entityRelatedMapper, $this->_entityRelatedPk);
		}
		return $this->_collection;
	}
	
	/**
	 * Zwraca kolekcje
	 * 
	 * @access public
	 * @return object
	 * 
	 */	
	public function getCollection() {
		$collection = $this->getIterator();
		return clone $collection; //2011.08.04
	}
	
	
	/**
	 * Zwraca informacje w formie ciagu znakow
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function __toString() {
		$str = "";
		$str .= "EntityMapperClassName: " . $this->_entityMapperClassName . "<br />";
		$str .= "EntityRelatedMapperClassName: " . $this->_entityRelatedMapperClassName . "<br />";
		$str .= "EntityRelatedPk: " . $this->_entityRelatedPk. "<br />";			
		return $str;			
	}
	
}

?>
