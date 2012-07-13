<?php

/**
 * @class HtmlElementAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class HtmlElementAbstract {
	
	/**
	 * Atrybuty standardowe elementu html
	 *
	 * @var array
	 * 
	 */
	protected $_stdAttributes = array();
	
	/**
	 * Atrybuty zdarzeniowe elementu html
	 *
	 * @var array
	 * 
	 */
	protected $_eventAttributes = array();
	
	/**
	 * Atrybuty charakterystyczne dla elementu html
	 *
	 * @var array
	 * 
	 */		
	protected $_attributes = array();
	
	/**
	 * Nazwa elementu html - tag html
	 *
	 * @var string
	 * 
	 */		
	protected $_tag = null;
	
	/**
	 * Obiekt fabryczny tworzacy kontrolki html
	 *
	 * @var object
	 * 
	 */	
	protected $_htmlElementFactory = null;
	
	/**
	 * Inicjalizuje dostepne atrybuty elementu html
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function init() {
		$this->_stdAttributes['class'] = '';
		$this->_stdAttributes['dir'] = '';
		$this->_stdAttributes['id'] = '';
		$this->_stdAttributes['lang'] = '';
		$this->_stdAttributes['style'] = '';
		$this->_stdAttributes['title'] = '';
		$this->_eventAttributes['onclick'] = '';
		$this->_eventAttributes['ondblclick'] = '';
		$this->_eventAttributes['onmousedown'] = '';
		$this->_eventAttributes['onmousemove'] = '';
		$this->_eventAttributes['onmouseout'] = '';		
		$this->_eventAttributes['onmouseover'] = '';
		$this->_eventAttributes['onmouseup'] = '';
		$this->_eventAttributes['onkeydown'] = '';
		$this->_eventAttributes['onkeypress'] = '';
		$this->_eventAttributes['onkeyup'] = '';						
	}
	
	/**
	 * Ustawia atrybut class elementu html
	 * 
	 * @access public
	 * @param string Nazwa klasy
	 * @return void
	 * 
	 */	
	public function setClass($class) {
		$this->setStdAttrib('class', (string)$class);
	}
	
	/**
	 * Ustawia atrybut dir elementu html
	 * 
	 * @access public
	 * @param string Kierunek tekstu
	 * @return void
	 * 
	 */	
	public function setDir($dir) {
		$this->setStdAttrib('dir', (string)$dir);
	}
	
	/**
	 * Ustawia atrybut id elementu html
	 * 
	 * @access public
	 * @param string Id
	 * @return void
	 * 
	 */	
	public function setId($id) {
		$this->setStdAttrib('id', (string)$id);
	}
	
	/**
	 * Ustawia atrybut lang elementu html
	 * 
	 * @access public
	 * @param string Jezyk dla elementu
	 * @return void
	 * 
	 */	
	public function setLang($lang) {
		$this->setStdAttrib('lang', (string)$lang);
	}
	
	/**
	 * Ustawia atrybut style elementu html
	 * 
	 * @access public
	 * @param string Styl elementu html
	 * @return void
	 * 
	 */	
	public function setStyle($style) {
		$this->setStdAttrib('style', (string)$style);
	}
	
	/**
	 * Ustawia atrybut title elementu html
	 * 
	 * @access public
	 * @param string Tutul dla elementu
	 * @return void
	 * 
	 */	
	public function setTitle($title) {
		$this->setStdAttrib('title', (string)$title);
	}
	
	/**
	 * Zwraca wartosc atrybutu class elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClass() {
		return $this->getStdAttrib('class');
	}
	
	/**
	 * Zwraca wartosc atrybutu dir elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getDir() {
		return $this->getStdAttrib('dir');
	}
	
	/**
	 * Zwraca wartosc atrybutu id elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getId() {
		return $this->getStdAttrib('id');
	}	
	
	/**
	 * Zwraca wartosc atrybutu lang elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getLang() {
		return $this->getStdAttrib('lang');
	}
	
	/**
	 * Zwraca wartosc atrybutu style elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getStyle() {
		return $this->getStdAttrib('style');
	}
	
	/**
	 * Zwraca wartosc atrybutu title elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getTitle() {
		return $this->getStdAttrib('title');
	}
	
	/**
	 * Ustawia wartosc atrybutu charakterystycznego dla danego elementu elementu html
	 * 
	 * @access public
	 * @param string Nazwa atrybutu
	 * @param string Wartosc atrybutu
	 * @return void
	 * 
	 */
	public function setAttrib($name, $value) {
		if($value === null) {
			return;
		}		
		if(isset($this->_attributes[$name])) {
			$this->_attributes[$name] = (string)$value;
		}		
	}
	
	/**
	 * Ustawia wartosc atrybutu standardowego dla danego elementu elementu html
	 * 
	 * @access public
	 * @param string Nazwa atrybutu
	 * @param string Wartosc atrybutu
	 * @return void
	 * 
	 */
	public function setStdAttrib($name, $value) {
		if($value === null) {
			return;
		}		
		if(isset($this->_stdAttributes[$name])) {
			$this->_stdAttributes[$name] = (string)$value;
		}		
	}
	
	/**
	 * Ustawia wartosc atrybutu zdarzeniowego dla danego elementu elementu html
	 * 
	 * @access public
	 * @param string Nazwa atrybutu
	 * @param string Wartosc atrybutu
	 * @return void
	 * 
	 */	
	public function setEventAttrib($name, $value) {
		if($value === null) {
			return;
		}		
		if(isset($this->_eventAttributes[$name])) {
			$this->_eventAttributes[$name] = (string)$value;
		}		
	}
	
	/**
	 * Ustawia wartosci atrybutow dla danego elementu elementu html
	 * 
	 * @access public
	 * @param Tablica atrybutow
	 * @return void
	 * 
	 */	
	public function setAttributes($attributes) {
		if($attributes === null) {
			return;
		}

		foreach($attributes AS $index => $value) {
			if($value === null) {
				continue;
			}			
			if(isset($this->_attributes[$index])) {
				$this->_attributes[$index] = (string)$value;
			}
			elseif(isset($this->_stdAttributes[$index])) {
				$this->_attributes[$index] = (string)$value;
			}
			elseif(isset($this->_eventAttributes[$index])) {
				$this->_attributes[$index] = (string)$value;
			}
		}
	}
	
	/**
	 * Zwraca wartosc atrybutu charakterystycznego dla danego elementu html
	 * 
	 * @access public
	 * @param string
	 * @return string
	 * 
	 */		
	public function getAttrib($name) {
		return (isset($this->_attributes[$name])) ? $this->_attributes[$name] : null; 
	}
	
	/**
	 * Zwraca wartosc standardowego atrybutu danego elementu html
	 * 
	 * @access public
	 * @param string
	 * @return string
	 * 
	 */		
	public function getStdAttrib($name) {
		return (isset($this->_stdAttributes[$name])) ? $this->_stdAttributes[$name] : null; 
	}
	
	/**
	 * Zwraca wartosc zdarzeniowego atrybutu danego elementu html
	 * 
	 * @access public
	 * @param string
	 * @return string
	 * 
	 */	
	public function getEventAttrib($name) {
		return (isset($this->_eventAttributes[$name])) ? $this->_eventAttributes[$name] : null; 
	}
	
	/**
	 * Tworzy i zwraca wartosc ciag zdefiniowanych atrybutow dla danego elementu html
	 * 
	 * @access protected
	 * @return string
	 * 
	 */	
	protected function buildAttributesString() {
		$attributesString  = '';
		foreach($this->_attributes AS $index=>$value) {
			if($value != '') {
				$attributesString .= ' ' . $index.'="'. $this->prepareValue($value) . '"';
			}
		}
		foreach($this->_stdAttributes AS $index=>$value) {
			if($value != '') {
				$attributesString .= ' ' . $index.'="'. $this->prepareValue($value) . '"';
			}
		}
		foreach($this->_eventAttributes AS $index=>$value) {
			if($value != '') {
				$attributesString .= ' ' . $index.'="'. $this->prepareValue($value) . '"';
			}
		}
		return $attributesString;
	}
	
	/**
	 * Przygotowuje wartosc kontrolki do wyswietlenia na stronie
	 * 
	 * @access protected
	 * @param mixed Ciag znakow do przetworzenia
	 * @return string
	 * 
	 * 
	 */	
	protected function prepareValue($value) {
		return htmlspecialchars((string)$value);
	}	
	
	/**
	 * Metoda abstrakcyjna
	 * Tworzy ciag definiujacy dany element html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	abstract public function fetchHtml(); 
	
}

?>
