<?php

/**
 * @class FormElementOption
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class HtmlElementOption extends HtmlElementAbstract {
	
	/**
	 * Nazwa kontrolki
	 *
	 * @var string
	 * 
	 */		
	protected $_tag = 'option';
	
	/**
	 * Wyswietlana wartosc opcji
	 *
	 * @var string
	 * 
	 */		
	protected $_option = '';
	
	/**
	 * Inicjalizuje dostepne atrybuty elementu html
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function init() {
		parent::init();
		$this->_attributes['label'] = '';
		$this->_attributes['value'] = '';
		$this->_attributes['disabled'] = '';
		$this->_attributes['selected'] = '';
	}		
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt fabryczny elementow formularza
	 * @return void
	 * 
	 */	
	public function __construct(IFactory $htmlElementFactory) {
		self::init();
		$this->_htmlElementFactory = $htmlElementFactory;
	}	
	
	
	/**
	 * Ustawia atrybut value elementu html
	 * 
	 * @access public
	 * @param string Wartosc
	 * @return void
	 * 
	 */			
	public function setValue($value) {
		$this->setAttrib('value', $value);
	}	
	
	/**
	 * Ustawia atrybut label elementu html
	 * 
	 * @access public
	 * @param string Etykieta
	 * @return void
	 * 
	 */			
	public function setLabel($label) {
		$this->setAttrib('label', $label);
	}
	
	/**
	 * Ustawia atrybut disabled elementu html
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie kontrolki
	 * @return void
	 * 
	 */			
	public function setDisabled($disabled=true) {
		if($disabled == true) {
			$this->setAttrib('disabled', 'disabled');
		}
		else {
			$this->setAttrib('disabled', '');
		}
	}
	
	/**
	 * Ustawia atrybut selected elementu html
	 * 
	 * @access public
	 * @param bool Wartosc kontrolki zostala wybrana/nie wybrana
	 * @return void
	 * 
	 */			
	public function setSelected($selected=true) {
		if($selected == true) {
			$this->setAttrib('selected', 'selected');
		}
		else {
			$this->setAttrib('selected', '');
		}
	}	
	
	/**
	 * Ustawia wartosc pola option elementu html
	 * 
	 * @access public
	 * @param string Wyswietlana wartosc opcji
	 * @return void
	 * 
	 */			
	public function setOption($option) {
		if($option !== null) {
			$this->_option = $option;
		}
	}	
	
	/**
	 * Zwraca wartosc atrybutu label elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getLabel() {
		return $this->getAttrib('label');
	}
	
	/**
	 * Zwraca wartosc atrybutu selected elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getSelected() {
		return $this->getAttrib('selected');
	}
	
	/**
	 * Zwraca wartosc atrybutu value elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getValue() {
		return $this->getAttrib('value');
	}
	
	/**
	 * Zwraca wartosc atrybutu disabled elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getDisabled() {
		return $this->getAttrib('disabled');
	}	
	
	/**
	 * Zwraca wartosc pola option elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getOption() {
		return $this->_option;
	}
	
	/**
	 * Tworzy i zwraca ciag definiujacy element html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function fetchHtml() {
		if($this->_option == '') {
			$this->_option = $this->getAttrib('value');
		}
		$htmlString = "";
		$htmlString .= "<" . $this->_tag . $this->buildAttributesString() . ">";
		$htmlString .= $this->prepareValue($this->_option);
		$htmlString .= "</" . $this->_tag . ">\n";
		return $htmlString;
	}
	
}

?>
