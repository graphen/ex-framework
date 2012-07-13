<?php

/**
 * @class FormElementSelect
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FormElementSelect extends FormElementAbstract {
	
	/**
	 * Nazwa kontrolki
	 *
	 * @var string
	 * 
	 */	
	protected $_tag = 'select';
	
	/**
	 * Tablica opcji lub grup opcji
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
		$this->_attributes['name'] = '';
		$this->_attributes['size'] = '';
		$this->_attributes['disabled'] = '';
		$this->_attributes['multiple'] = '';
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
	 * Ustawia atrybut size elementu html
	 * 
	 * @access public
	 * @param string Dlugosc kontrolki
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
	 * Ustawia atrybut multiple elementu html
	 * 
	 * @access public
	 * @param bool Wybranie mozliwosci wyboru wielokrotnego/jednokrotnego
	 * @return void
	 * 
	 */			
	public function setMultiple($multiple=true) {
		if($multiple == true) {
			$this->setAttrib('multiple', 'multiple');
		}
		else {
			$this->setAttrib('multiple', '');
		}
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
	 * Zwraca wartosc atrybutu multiple elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getMultiple() {
		return $this->getAttrib('multiple');
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
	 * Dodaje obiekt option (kontrolke) lub optgroup (kontrolke) do listy opcji
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
				elseif($optionElement instanceof HtmlElementOptgroup) {
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
			if(is_array($option)) {
				$optgroupObject = $this->_htmlElementFactory->create('HtmlElementOptgroup');
				$optgroupObject->setLabel($value);
				foreach($option AS $v => $o) {
					$optionObject  = $this->_htmlElementFactory->create('HtmlElementOption');
					$optionObject->setValue($v);
					$optionObject->setOption($o);
					if(in_array($v, $selected)) {
						$optionObject->setSelected(true);
					}
					if($attributes !== null) {
						$optionObject->setAttributes($attributes);
					}
					$optgroupObject->addElement($optionObject);					
				}
				$this->_options[] = $optgroupObject;
			}
			else {
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
			if($option instanceof HtmlElementOptgroup) {
				$option->setValue($value);
			}
			else {
				if($option instanceof HtmlElementOption) {
					if(in_array($option->getValue(), $value)) {
						$option->setSelected(true);
					}
				}				
			}
		}
	}
	
}

?>
