<?php

/**
 * @class FormElementButtonAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class FormElementButtonAbstract extends FormElementAbstract {
	
	/**
	 * Nazwa kontrolki
	 *
	 * @var string
	 * 
	 */	
	protected $_tag = 'button';	
	
	/**
	 * Typ kontrolki button
	 *
	 * @var string
	 * 
	 */	
	protected $_type = null;
	
	/**
	 * Zawartosc kontrolki button
	 *
	 * @var string
	 * 
	 */		
	protected $_content = '';
	
	/**
	 * Inicjalizuje dodatkowe atrybuty dla elementu html
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function init() {
		parent::init();
		$this->_attributes['type'] = '';
		$this->_attributes['name'] = '';
		$this->_attributes['disabled'] = '';
		$this->_attributes['value'] = '';		
	}
	
	/**
	 * Ustawia atrybut name elementu html
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @return void
	 * 
	 */		
	public function setName($name) {
		$this->setAttrib('name', $name);
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
	 * Ustawia wartosc pola content elementu html
	 * 
	 * @access public
	 * @param string Zawartosc
	 * @return void
	 * 
	 */		
	public function setContent($content='') {
		if($content !== null) {
			$this->_content = $content;
		}
	}	
	
	/**
	 * Zwraca wartosc atrybutu type elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getType() {
		return $this->_attributes['type'];
	}
	
	/**
	 * Zwraca wartosc atrybutu name elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getName() {
		return $this->_attributes['name'];
	}
	
	/**
	 * Zwraca wartosc atrybutu value elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getValue() {
		return $this->_attributes['value'];
	}
	
	/**
	 * Zwraca wartosc atrybutu disabled elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getDisabled() {
		return $this->_attributes['disabled'];
	}
	
	/**
	 * Zwraca wartosc pola content elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getContent() {
		return $this->_content;
	}	
	
	/**
	 * Tworzy i zwraca ciag definiujacy element html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function fetchHtml() {
		$htmlString = "";
		$htmlString .= "<" . $this->_tag . $this->buildAttributesString() . ">\n";
		$htmlString .= $this->_content . "\n";
		$htmlString .= "</" . $this->_tag . ">\n";
		return $htmlString;
	}	
	
}

?>
