<?php

/**
 * @class FilterInput
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FilterInput implements IFilterInput {
	
	/**
	 * Fabryka filtrow
	 *
	 * @var object
	 * 
	 */	
	protected $_filterFactory = null;
	
	/**
	 * Reguly filtrowania
	 *
	 * @var array
	 * 
	 */	
	protected $_rules = array();
	
	/**
	 * Tabela przygotowanych zestawow filtrow dla poszczegolnych pol filtrowanej tablicy
	 *
	 * @var array
	 * 
	 */	
	protected $_preparedFilters = array();
		
	/**
	 * Konstruktor
	 *
	 * @access public
	 * @param object Fabryka filtrow
	 * @return void
	 * 
	 */		
	public function __construct(IFactory $filterFactory) {
		$this->_filterFactory = $filterFactory;
	}
	
	/**
	 * Przeprowadza filtrowanie danych przekazanych w formie tablicy asocjacyjnej
	 *
	 * @access public
	 * @param array Tablica asocjacyjna z danymi do filtrowania
	 * @param array Tablica opcji (tymczasowo nie uzywana)
	 * @return mixed
	 * 
	 */		
	public function filter($data, $options=array()) {
		if($data === null) {
			return $data;
		}
		if(!is_array($data)) {
			$data = array($data);
		}
		if(count($this->_preparedFilters) == 0) {
			$this->prepareFilters();
			if(count($this->_preparedFilters) == 0) {
				return $data;
			}
		}
		foreach($this->_preparedFilters AS $fieldFilterName => $fieldFilters) {
			if(is_string($fieldFilters['fields'])) {
				if($fieldFilters['fields'] == '*') {
					foreach($data AS $index=>$field) {
							$data[$index] = $fieldFilters['object']->filter($field);
					}	
				}
				else {
					if((!isset($data[$fieldFilters['fields']])) || (isset($data[$fieldFilters['fields']]) && ($data[$fieldFilters['fields']]==''))) {
						continue;
					}
					else {
						$data[$fieldFilters['fields']] = $fieldFilters['object']->filter($data[$fieldFilters['fields']]);
					}
				}
			}
			if(is_array($fieldFilters['fields'])) {
				foreach($fieldFilters['fields'] AS $field) {
					if((!isset($data[$field])) || (isset($data[$field]) && ($data[$field]==''))) {
						continue;
					}
					else {
						$data[$field] = $fieldFilters['object']->filter($data[$field]);
					}					
				}
			}
		}
		return $data;
	}
	
	/**
	 * Ustawia tablice regul filtrowania
	 *
	 * @access public
	 * @param array Tablica asocjacyjna z filtrowania
	 * @return void
	 * 
	 */		
	public function setRules($rules) {
		$this->_preparedFilters = array();
		$this->_rules = $rules;
	}	
	
	/**
	 * Przygotowuje zestaw filtrow
	 *
	 * @access public
	 * @return void
	 * 
	 */		
	public function prepareFilters() {
		if(count($this->_rules) == 0) { 
			return;
		}
		foreach($this->_rules AS $ruleSetName => $ruleSet) {
			 //najpierw obsluga metapolecen
				//obsluga nazwy pola
			if(isset($ruleSet['fields'])) { //jesli zdefiniowano metapolecenie fields w zestawie regul dla pola to zestaw bedzie dotyczyc pola/pol oznaczonego/ych ta/tymi nazwa/nazwami, a nie nazwa zestawu regul
				$this->_preparedFilters[$ruleSetName]['fields'] = $ruleSet['fields']; //ta wartosc zostanie zapamietana do czasu wykonania regul filtrowania
				unset($ruleSet['fields']);
			}
			else {
				$this->_preparedFilters[$ruleSetName]['fields'] = $ruleSetName; //jesli brak takiej definicji regula bedzie wykonana dla pola o nazwie zestawu regul
			}

			//reguly zdefiniowane przez metalopolecenia zostaly przeanalizowane i usuniete
			reset($ruleSet); //powrot na poczatek tablicy zestawu regul
			$tmpFilterArray = array(); //tablica filtrow, jesli bedzie ich wiecej niz jeden stworzy sie z nich kompozyt
			//echo "<pre>".print_r($ruleSet,true)."</pre><br />";
			
			foreach($ruleSet AS $filterInd => $filterDef) { //po usunieciu metapolecen pozostaly tylko definicje filtrow
				if(is_string($filterDef)) { //jesli filtr zdefiniowano jako string, trzeba utworzyc odpowiedni obiekt
					$filterObj = $this->_filterFactory->create($filterDef); //utworzenie filtra
					$tmpFilterArray[$filterInd] = $filterObj; //zapamietanie obiektu filtra w tymczasowej tablicy filtrow
				}
				if(is_array($filterDef)) { //jesli filtr zdefiniowano jako tablice, to jej pierwszy el. jest nazwa klasy filtra, a drugi to tablica zawierajaca dane konfiguracyjne filtra
					if(is_string($filterDef[0])) { //jesli pierwszy el. tablicy to string wiec musi byc nazwa filtra
						$filterObj = $this->_filterFactory->create($filterDef[0]); //utworzenie filtra
						$params = array();
						if(isset($filterDef[1])) { //jesli jest drugi el. tablicy, wiec sa to parametry konf. filtra
							$params = (is_array($filterDef[1])) ? $filterDef[1] : array($filterDef[1]); 
						}
						$tmpFilterArray[$filterInd] = array($filterObj, $params); //zapamietanie obiektu filtra razem z jego opcjami jako tablicy w tymczasowej tablicy filtrow
					}
					else {
						throw new FilterException('Pierwszy element tablicy musi byc nazwa klasy filtra');
					}
				}
			}
			if(count($tmpFilterArray) > 0) { //jesli obiektow filtrow jest wiecej to utworzenie kompozytu filtrow
				$filterComp = $this->_filterFactory->create('FilterComposite');
				foreach($tmpFilterArray AS $filter) {
					$filterComp->addFilter($filter);
				}
				$this->_preparedFilters[$ruleSetName]['object'] = $filterComp; //i zapamietanie kompozytu filtrow
			}
		}
	}
	
}

?>
