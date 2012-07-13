<?php

/**
 * @class SaGet
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class SaGet extends SaAbstract implements ISaGet {
	
	/**
	 * Wlaczenie/wylaczenie tablicy _GET
	 *
	 * @var bool
	 * 
	 */	
	protected $_allowGet = true;
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt impl. interfejs IFilterComposite
	 * @param object Obiekt filtra FilterStripSlashes
	 * @param object Obiekt filtra FilterStripGetDissallowedChars
	 * @param bool Wskazuje czy czy wlaczono mozliwosc uzywania tablicy s_GET
	 * 
	 */		
	public function __construct(IFilterComposite $filterComposite, IFilter $stripSlashesFilter, IFilter $stripGetDissallowedCharsFilter, $allowGet=false) {
		parent::init('GET', $filterComposite);
		$this->_allowGet = (bool)$allowGet;
		if($this->_allowGet == false) {
			$data = $_GET;
			foreach($_GET AS $i=>$v) {
				$data[$i] = $v;
			};
			$_GET = array();
			$this->_data = & $data;
		}	
		$this->addFilter($stripSlashesFilter);
		$this->addFilter($stripGetDissallowedCharsFilter);
	}
	
	/**
	 * Zwraca wartosc danej zmiennej z tablicy $_GET lub cala tablice
	 * 
	 * @access public
	 * @param string Nazwa zmiennej
	 * @param mixed|null|array
	 * 
	 */		
	public function get($var=null) {	
		if(is_null($var)) {
			$tmpArr = $this->_data;
			array_walk_recursive($tmpArr, array(&$this, 'arrayWalkHelper'));
			return $tmpArr;
		}
		if(isset($this->_data[$var])) {
			return $this->_filterComposite->filter($this->_data[$var]);
		}
		else {
			return null;
		}
	}
	
	/**
	 * Zwraca surowa wartosc podanej zmiennej z tablicy $_GET
	 * 
	 * @access public
	 * @param string Nazwa zmiennej
	 * @return string|null
	 * 
	 */	
	public function getRaw($name=null) {
		if(is_null($name)) {
			return $this->_data;
		}		
		if(isset($this->_data[$name])) {
			return $this->_data[$name];
		}
		else {
			return null;
		}
	}	
	
	/**
	 * Zapisuje wartosci do tablicy $_GET
	 * 
	 * @access public
	 * @param string|array Nazwa zmiennej lub tablica zmiennych
	 * @param mixed Wartosc zmiennej
	 * @return void
	 * 
	 */		
	public function setQuery($var, $value=null) {
		if(($value === null) && is_array($var)) {
			$this->_data = array_merge($this->_data, $var);
		}
		else {
			$this->_data[(string)$var] = $value;
		}
	}
	
}

?>
