<?php

/**
 * @class SaAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class SaAbstract implements Countable, ArrayAccess, Iterator {
	
	/**
	 * Tablica z danymi, referencja do tablicy superglobalnej
	 *
	 * @var array
	 * 
	 */		
	protected $_data = array();
	
	/**
	 * Obiekt klasy FilterComposite
	 *
	 * @var object
	 * 
	 */		
	protected $_filterComposite = null;

	/**
	 * Metoda inicjujaca
	 * 
	 * @access protected
	 * @param string Nazwa tablicy superglobalnej
	 * @param object Obiekt implementujacy interfejs IFilterComposite
	 * 
	 */		
	protected function init($superglobalArrayName, IFilterComposite $filterComposite) {
		$gpcsArrayName = strtoupper($superglobalArrayName);
		if($gpcsArrayName == 'GET') {
			$this->_data =& $_GET; 
		}
		elseif($gpcsArrayName == 'POST') {
			$this->_data =& $_POST; 
		}
		elseif($gpcsArrayName == 'SERVER') {
			$this->_data =& $_SERVER; 
		}
		elseif($gpcsArrayName == 'ENV') {
			$this->_data =& $_ENV; 
		}
		elseif($gpcsArrayName == 'COOKIE') {
			$this->_data =& $_COOKIE; 
		}
		else {
			throw new SaException('Tablica ' . $gpcsArrayName . ' nie jest obslugiwana'); 
		}
		$this->_filterComposite = $filterComposite;
	}
	
	/**
	 * Wspomaga filtrowanie tablic funkcja array_walk_recursive
	 *
	 * @access protected
	 * @param mixed element tablicy podany przez referencje
	 * @param mixed Index tablicy
	 * @return void
	 * 
	 */	
	protected function arrayWalkHelper(&$value,$index) {
		if($value != '') {
			$value = $this->_filterComposite->filter($value);
		}
	}
	
	/**
	 * Dodaje filter do tablicy
	 *
	 * @access public
	 * @param object
	 * @return void
	 * 
	 */
	public function addFilter(IFilter $filter) {
		$this->_filterComposite->addFilter($filter);
	}
	
	/**
	 * Zwraca wartosc zadanej zmiennej o ile istnieje w referencji do tablicy superglobalnej
	 * 
	 * @access public
	 * @param string Nazwa zmiennej
	 * @return mixed|null
	 * 
	 */		
	public function __get($var) {
		if(isset($this->_data[$var])) {
			return $this->_filterComposite->filter($this->_data[$var]);
		}
		return null;
	}
	
	/**
	 * Sprawdza czy istnieje dana zmienna w referencji do tablicy superglobalnej
	 * 
	 * @access public
	 * @param string Nazwa zmiennej
	 * @return bool
	 * 
	 */		
	public function __isset($var) {
		return isset($this->_data[$var]);
	}
	
	/* Interface Countable */

	/**
	 * Zwraca liczbe elementow w referencji do tablicy superglobalnej [Impl. int. Countable]
	 * 
	 * @access public
	 * @return integer
	 * 
	 */			
	public function count() {
		return count($this->_data);
	}
	
	/* Interface ArrayAccess */

	/**
	 * Zwraca wartosc danego elementu referencji do tablicy superglobalnej [Impl. int. Array Access]
	 * 
	 * @access public
	 * @param string Nazwa zmiennej
	 * @return mixed|null
	 * 
	 */		
	public function offsetGet($var) {
		if($this->offsetExists($var)) {
			return $this->_filterComposite->filter($this->_data[$var]);
		}
		return null;		
	}

	/**
	 * Sprawdza istnienie danego elementu referencji do tablicy superglobalnej [Impl. int. Array Access]
	 * 
	 * @access public
	 * @param string Nazwa zmiennej
	 * @return bool
	 * 
	 */		
	public function offsetExists($var) {
		return isset($this->_data[$var]);		
	}
	
	/**
	 * Ustawia nowa wartosc danego elementu referencji do tablicy superglobalnej [Impl. int. Array Access]
	 * 
	 * @access public
	 * @param string Nazwa zmiennej
	 * @param mixed Nowa wartosc zmiennej
	 * @return void
	 * 
	 */		
	public function offsetSet($var, $value) {
		$this->_data[$var] = $value;
	}
	
	/**
	 * Kasuje dany element referencji do tablicy superglobalnej [Impl. int. Array Access]
	 * 
	 * @access public
	 * @param string Nazwa zmiennej
	 * @return void
	 * 
	 */		
	public function offsetUnset($var) {
		unset($this->_data[$var]);
	}
		
	/* Interface Iterator */
	
	/**
	 * Ustawia kursor na pierwszym elemencie tablicy
	 * 
	 * @access public
	 * @return void
	 * 
	 */			
	public function rewind() {
		reset($this->_data);
	}
	
	/**
	 * Zwraca aktualny element tablicy
	 * 
	 * @access public
	 * @return mixed
	 * 
	 */			
	public function current() {
		return $this->_filterComposite->filter(current($this->_data));
	}
	
	/**
	 * Przesuwa kursor na nastepny element tablicy
	 * 
	 * @access public
	 * @return void
	 * 
	 */			
	public function next() {
		next($this->_data);
	}
	
	/**
	 * Zwraca aktualny klucz talicy
	 * 
	 * @access public
	 * @return mixed
	 * 
	 */
	public function key() {
		return key($this->_data);
	}
	
	/**
	 * Sprawdza istnienie elementu tablicy
	 * 
	 * @access public
	 * @return bool
	 * 
	 */			
	public function valid() {
		return key($this->_data) !== null;
	}	
	

	/**
	 * Wyswietla zawartosc tablicy obslugiwanej przez dana instancje klasy
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function __toString() {
		$tmpArr = array();
		foreach($this->_data AS $index=>$value) {
			$tmpArr[$index] = htmlspecialchars($value);
		}
		$data = "<pre>\n";
		$data .= print_r($tmpArr, true);
		$data .= "</pre>\n";
		return $data;
	}
	
}

?>
