<?php

/**
 * @class FormElementOptgroup
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class HtmlElementOptgroup extends HtmlElementAbstract {
	
	/**
	 * Nazwa kontrolki
	 *
	 * @var string
	 * 
	 */			
	protected $_tag = 'optgroup';
	
	/**
	 * Obiekty kontrolek opcji
	 *
	 * @var array
	 * 
	 */			
	protected $_options = array();
	
	/**
	 * Inicjalizuje dodatkowe atrybuty dla elementu html
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function init() {
		parent::init();
		$this->_attributes['label'] = '';
		$this->_attributes['disabled'] = '';
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
		$htmlString .= "<" . $this->_tag . $this->buildAttributesString() . ">\n";
		foreach($this->_options AS $option) {
			$htmlString .= $option->fetchHtml();			
		}
		$htmlString .= "</" . $this->_tag . ">\n";
		return $htmlString;
	}
	
	/**
	 * Dodaje obiekt option (kontrolke) do listy opcji
	 * 
	 * @access public
	 * @param object Obiekt opcji
	 * @return void
	 * 
	 */	
	public function addElement($optionElement) {
		if(is_array($optionElement)) {
			foreach($optionElement AS $option) {
				$this->addElement($option);
			}
		}
		else {
			if(is_object($optionElement)) {
				if($optionElement instanceof HtmlElementOption) {
					$this->_options[] = $optionElement;
				}
			}
		}
	}
	
	/**
	 * Tworzy i dodaje obiekt option (kontrolke) do listy opcji
	 * 
	 * @access public
	 * @param string Wartosc kontrolki option
	 * @param string Wartosc wyswietlanej opcji
	 * @param array Pozostale atrybuty
	 * @return void
	 * 
	 */	
	public function addOption($value, $option=null, $attributes=null) {
		if(is_array($attributes) && isset($attributes['value'])) {
			unset($attributes['value']);
		}
		$optionObject  = $this->_htmlElementFactory->create('HtmlElementOption');
		$optionObject->setValue($value);
		if($option !== null) {
			$optionObject->setOption($option);
		}
		if($attributes !== null) {
			$optionObject->setAttributes($attributes);
		}
		$this->_options[] = $optionObject;
	}
	
	/**
	 * Tworzy i dodaje zestaw obiektow option (kontrolke) do listy opcji
	 * 
	 * @access public
	 * @param array Zestawy Wartosc=Opcja kontrolek option
	 * @param array Tablica wartosci wybranych opcji
	 * @param array Pozostale atrybuty
	 * @return void
	 * 
	 */		
	public function addOptions($options, $selected=null, $attributes=null) {
		if(is_array($attributes) && isset($attributes['value'])) {
			unset($attributes['value']);
		}
		if($selected !== null) {
			if(!is_array($selected)) {
				$selected = array($selected);
			}		
		}
		else {
			$selected = array();
		}
		foreach($options AS $value => $option) {
			$optionObject  = $this->_htmlElementFactory->create('HtmlElementOption');
			$optionObject->setValue($value);
			$optionObject->setOption($option);
			if(in_array($value, $selected)) {
				$optionObject->setSelected(true);
			}
			if($attributes !== null) {
				$optionObject->setAttributes($attributes);
			}
			$this->_options[] = $optionObject;
		}
	}
	
	/**
	 * Resetuje wartosci przed ich ponownym ustawieniem
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function resetValues() {
		foreach($this->_options AS $option) {
			if($option instanceof HtmlElementOption) {
				$option->setSelected(false);
			}
		}
	}	
	
	/**
	 * Ustawia wybrane wartosci dla calego elementu
	 * 
	 * @access public
	 * @param string|array Tablica wartosci jakie maja byc przypisane opcjom elementu
	 * @return void
	 * 
	 */		
	public function setValue($value) {
		if($value === null) {
			return;
		}
		$this->resetValues();
		if(!is_array($value)) {
			$value = array($value);
		}
		foreach($this->_options AS $option) {
			if($option instanceof HtmlElementOption) {
				if(in_array($option->getValue(), $value)) {
					$option->setSelected(true);
				}
			}
		}	
	}
		
}

?>
