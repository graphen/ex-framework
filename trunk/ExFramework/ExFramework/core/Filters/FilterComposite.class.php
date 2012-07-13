<?php

/**
 * @class FilterComposite
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FilterComposite implements IFilterComposite {

	/**
	 * Tablica z obiektami typu IFilter; kolejka filtrow
	 *
	 * @var array
	 * 
	 */		
	private $_filters = array();

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
	 * Dolacza obiekt filtra na poczatek tablicy filtrow; na poczatek kolejki
	 * 
	 * @access public
	 * @param object|array Obiekt filtra lub tablica filtrow
	 * @return void
	 * 
	 */			
	public function prependFilter($filter) {
		$this->addFilter($filter, 'prepend');
	}
	
	/**
	 * Dolacza obiekt filtra na koniec tablicy filtrow; na koniec kolejki
	 * 
	 * @access public
	 * @param object|array Obiekt filtra lub tablica filtrow
	 * @return void
	 * 
	 */		
	public function appendFilter($filter) {
		$this->addFilter($filter, 'append');
	}	
	
	/**
	 * Dolacza obiekt filtra do tablicy; do kolejki filtrow
	 * 
	 * @access public
	 * @param object|array Obiekt filtra lub tablica filtrow
	 * @param string Sposob dodania filtra; na koniec lub poczatek kolejki
	 * @return void
	 * 
	 */		
	public function addFilter($filter, $where=null) {
		if(is_object($filter)) {
			if(!$filter instanceof IFilter) {
				throw new FilterException('Obiekt filtra musi implementowac interfejs IFilter');
			}
			else {
				$options = array();
				if($where === 'prepend') {
					array_unshift($this->_filters, array($filter, $options));
				}
				else {
					$this->_filters[] = array($filter, $options);
				}				
			}
		}
		elseif(is_array($filter)) {
			if(count($filter) == 2) {
				if(!$filter[0] instanceof IFilter) {
					throw new FilterException('Pierwszy element tablicy musi byc filtrem iml. interfejs IFilter');
				}
				else {
					if(isset($filter[1])) {
						$options = $filter[1];
						if(!is_array($options)) {
							$options = array($options);
						}
					}
					else {
						$options = array();
					}
					
					if($where === 'prepend') {
						array_unshift($this->_filters, array($filter[0], $options));
					}
					else {
						$this->_filters[] = array($filter[0], $options);
					}					
				}	
			}
			elseif(count($filter > 2)) {
				foreach($filter AS $filterEl) {
					$this->addFilter($filterEl);
				}
			}
			else {
				throw new FilterException('Tablica nie zawiera filtrow');
			}
		}
		else {
			throw new FilterException('Argument musi byc obiektem, tablica obiektow implementujacych interfejs IFilter lub tablica dwuelementowa zawierajaca obiekt filtra i tablice opcji');
		}
	}
	
	/**
	 * Usuwa wszystkie filtry z kolejki filtrow
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function removeFilters() {
		$this->_filters = array();
	}
	
	/**
	 * Dodaje grupe filtrow do kolejki, wczesniej usuwa z niej wszystkie
	 * 
	 * @access public
	 * @param array Tablica filtrow
	 * @return void
	 * 
	 */	
	public function setFilters(Array $filters) {
		$this->removeFilters();
		$this->addFilter($filters);
	}
	
	/**
	 * Zaraca tablice filtrow; kolejke filtrow
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getFilters() {
		return $this->_filters;
	}
	
	/**
	 * Wykonuje filtrowanie iterujac przez wszystkie filtry z kolejki
	 * 
	 * @access public
	 * @param mixed Wartosc poddawana filtrowaniu
	 * @param array Dodatkowe opcje
	 * @return mixed
	 * 
	 */	
	public function filter($var, $options=array()) {
		$filteredVar = $var;		
		if(count($this->_filters) > 0) {
			foreach($this->_filters AS $filter) {
				$filteredVar = $filter[0]->filter($filteredVar, $filter[1]);
			}
		}
		return $filteredVar;
	}
	
}

?>
