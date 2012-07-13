<?php

/**
 * @class FormElementInputAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class FormElementInputAbstract extends FormElementAbstract {
	
	/**
	 * Nazwa kontrolki
	 *
	 * @var string
	 * 
	 */		
	protected $_tag = 'input';
	
	/**
	 * Typ kontrolki input
	 *
	 * @var string
	 * 
	 */	
	protected $_type = '';
	
	/**
	 * Inicjalizuje dostepne atrybuty elementu html
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function init() {
		parent::init();
		$this->_attributes['type'] = '';
		$this->_attributes['name'] = '';
		$this->_attributes['value'] = '';
		$this->_attributes['size'] = '';
		$this->_attributes['disabled'] = '';		
	}
	
	/**
	 * Ustawia atrybut name elementu html
	 * 
	 * @access public
	 * @param string Nazwa elementu formularza (kontrolki)
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
	 * Ustawia atrybut size elementu html
	 * 
	 * @access public
	 * @param string Dlugosc elementu
	 * @return void
	 * 
	 */		
	public function setSize($size) {
		$this->setAttrib('size', $size);
	}
	
	/**
	 * Ustawia atrybut disabled elementu html
	 * 
	 * @access public
	 * @param bool Element wlaczony/wylaczony
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
	 * Zwraca wartosc atrybutu type elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getType() {
		return $this->getAttrib('type');
	}
	
	/**
	 * Zwraca wartosc atrybutu name elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getName() {
		return $this->getAttrib('name');
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
	 * Zwraca wartosc atrybutu size elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getSize() {
		return $this->getAttrib('size');
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
	 * Tworzy i zwraca ciag definiujacy element html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function fetchHtml() {
		$htmlString = "";
		$htmlString .= "<" . $this->_tag . $this->buildAttributesString() . " />\n";
		return $htmlString;
	}	
	
}

?>
