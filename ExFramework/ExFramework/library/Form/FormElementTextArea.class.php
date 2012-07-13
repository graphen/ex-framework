<?php

/**
 * @class FormElementTextArea
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FormElementTextArea extends FormElementAbstract {
	
	/**
	 * Nazwa kontrolki
	 *
	 * @var string
	 * 
	 */		
	protected $_tag = 'textarea';
	
	/**
	 * Wartosc kontrolki
	 *
	 * @var string
	 * 
	 */		
	protected $_value = '';
	
	/**
	 * Inicjalizuje dodatkowe atrybuty dla elementu html
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function init() {
		parent::init();
		$this->_attributes['cols'] = 10;
		$this->_attributes['rows'] = 10;				
		$this->_attributes['disabled'] = '';
		$this->_attributes['name'] = '';
		$this->_attributes['readonly'] = '';
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
	 * Ustawia atrybut cols elementu html
	 * 
	 * @access public
	 * @param string Ilosc kolumn
	 * @return void
	 * 
	 */			
	public function setCols($cols) {
		$this->setAttrib('cols', (int)$cols);
	}
	
	/**
	 * Ustawia atrybut rows elementu html
	 * 
	 * @access public
	 * @param string Ilosc wierszy
	 * @return void
	 * 
	 */			
	public function setRows($rows) {
		$this->setAttrib('rows', (int)$rows);
	}
	
	/**
	 * Ustawia atrybut disabled elementu html
	 * 
	 * @access public
	 * @param bool Kontrolka wlaczona/wylaczona
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
	 * Ustawia atrybut readonly elementu html
	 * 
	 * @access public
	 * @param bool Kontrolka tylko do odczutu/zarazem do odczytu i zapisu
	 * @return void
	 * 
	 */		
	public function setReadOnly($readOnly=true) {
		if($readOnly == true) {
			$this->setAttrib('readonly', 'readonly');
		}
		else {
			$this->setAttrib('readonly', '');
		}
	}
	
	/**
	 * Ustawia wartosc elementu (kontrolki) formularza
	 * 
	 * @access public
	 * @param mixed Wartosc elementu formularza
	 * @return void
	 * 
	 */		
	public function setValue($value) {
		if($value !== null) {
			$this->_value = $value;
		}
	}
	
	/**
	 * Zwraca wartosc atrybutu cols elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getCols() {
		return $this->getAttrib('cols');
	}
	
	/**
	 * Zwraca wartosc atrybutu rows elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getRows() {
		$this->setAttrib('rows');
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
	 * Zwraca wartosc atrybutu readonly elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getReadOnly() {
		return $this->getAttrib('readonly');
	}
	
	/**
	 * Zwraca wartosc elementu (kontrolki) html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getValue() {
		return $this->_value;
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
		$htmlString .= $this->prepareValue($this->_value) . "\n";
		$htmlString .= "</" . $this->_tag . ">\n";
		return $htmlString;
	}
	
}

?>
