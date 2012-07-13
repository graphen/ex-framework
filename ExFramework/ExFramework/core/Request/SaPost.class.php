<?php

/**
 * @class SaPost
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class SaPost extends SaAbstract implements ISa {
	
	/**
	 * Wlaczenie/wylaczenie czyszczenia z niebezpiecznego kodu
	 *
	 * @var bool
	 * 
	 */	
	protected $_cleanXss = false;
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt impl. interfejs IFilterComposite
	 * @param object Obiekt filtra FilterStripSlashes
	 * @param object Obiekt filtra FilterXss
	 * @param bool Globalne czyszczenie przed atakami XSS
	 * 
	 */		
	public function __construct(IFilterComposite $filterComposite, IFilter $stripSlashesFilter, IFilter $xssFilter, $globalXss=false) {
		parent::init('POST', $filterComposite);
		$this->_cleanXss = (bool)$globalXss;
		$this->addFilter($stripSlashesFilter);
		if($this->_cleanXss == true) {
			$this->addFilter($xssFilter);
		}
	}
	
	/**
	 * Zwraca wartosc danej zmiennej z tablicy $_POST lub cala tablice
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
	 * Zwraca surowa wartosc podanej zmiennej z tablicy $_POST
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
