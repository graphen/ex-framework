<?php

/**
 * @class SaEnv
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class SaEnv extends SaAbstract implements ISa {
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt impl. interfejs IFilterComposite
	 * @param object Obiekt filtra FilterStripSlashes
	 * 
	 */		
	public function __construct(IFilterComposite $filterComposite, IFilter $stripSlashesFilter) {
		parent::init('ENV', $filterComposite);
		$this->addFilter($stripSlashesFilter);		
	}
	
	/**
	 * Zwraca wartosc danej zmiennej z tablicy $_ENV lub cala tablice
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
	 * Zwraca surowa wartosc podanej zmiennej z tablicy $_ENV
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
	
}

?>
