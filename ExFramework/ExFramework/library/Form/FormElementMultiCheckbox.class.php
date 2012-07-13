<?php

/**
 * @class FormElementMultiCheckbox
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FormElementMultiCheckbox extends FormElementAbstract {
		
	/**
	 * Tablica elementow checkbox
	 *
	 * @var array
	 * 
	 */		
	protected $_checkboxes = array();
	
	/**
	 * Nazwa elementu
	 *
	 * @var string
	 * 
	 */		
	protected $_name = '';
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt fabryczny elementow formularza
	 * @return void
	 * 
	 */		
	public function __construct(IFactory $htmlElementFactory) {
		$this->_htmlElementFactory = $htmlElementFactory;
	}
	
	/**
	 * Ustawia wartosc name
	 * 
	 * @access public
	 * @param string Nazwa elementu
	 * @return void
	 * 
	 */			
	public function setName($name) {
		if($name !== null) {
			$this->_name = $name;
		}
	}	
	
	/**
	 * Zwraca wartosc name elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */			
	public function getName() {
		return $this->_name;
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
		if(isset($this->_checkboxes[$this->_name])) {
			foreach($this->_checkboxes[$this->_name] AS $checkbox) {
				$labelObject = $checkbox->getLabel();
				$label = '';
				if($labelObject instanceof HtmlElementLabel) {
					$label = $labelObject->fetchHtml();
				}
				$htmlString .= $checkbox->fetchHtml() . " " . $label . "<br />\n";
			}
		}		
		return $htmlString;
	}
	
	/**
	 * Dodaje obiekt checkbox (kontrolke) do listy 
	 * 
	 * @access public
	 * @param object Obiekt checkbox
	 * @return void
	 * 
	 */			
	public function addElement($checkboxElement) {
		if(is_array($checkboxElement)) {
			foreach($checkboxElement AS $checkbox) {
				$this->addElement($checkbox);
			}
		}
		else {
			if(is_object($checkboxElement)) {
				if($checkboxElement instanceof HtmlElementInputCheckbox) {
					if($this->_name != '') {
						$name = $this->_name;
					}
					else {
						$name = $checkboxElement->getName();
						$this->_name = $name;
					}
					if($name == '') {
						$name = md5(time());
					}						
					$this->_checkboxes[$name][] = $checkboxElement;	
				}
			}
		}
	}
	
	/**
	 * Tworzy i dodaje obiekt checkbox (kontrolke) do listy
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param array Wartosc kontrolki i jej etykieta 
	 * @param bool Wybrano kontrolke
	 * @param array Pozostale atrybuty
	 * @return void
	 * 
	 */	
	public function addCheckbox($name, $value=null, $checked=false, $attributes=null) {
		if(is_array($attributes) && isset($attributes['name'])) {
			unset($attributes['name']);
		}
		if(!is_array($value)) {
			$value = array($value);
		}
		$checkboxObject = $this->_htmlElementFactory->create('FormElementInputCheckbox');
		$checkboxObject->setName($name);
		$checkboxObject->setValue(key($value));
		$checkboxObject->setLabel(current($value));
		$checkboxObject->setChecked((bool)$checked);
		if($attributes !== null) {
			$checkboxObject->setAttributes($attributes);
		}
		if($this->_name != '') {
			$name = $this->_name;
		}
		else {
			$this->_name = $name;			
		}
		if($name == '') {
			$name = md5(time());
		}			
		$this->_checkboxes[$name][] = $checkboxObject;
	}
	
	/**
	 * Tworzy i dodaje zestaw obiektow checkbox (kontrolki) do listy
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param array Tablica wartosci dla grupy kontrolek checkbox oraz ich etykiet
	 * @param array Tablica wartosci wybranych wsrod grupy kontrolek checkbox
	 * @param array Pozostale atrybuty
	 * @return void
	 * 
	 */			
	public function addCheckboxes($name, $value=null, $checked=null, $attributes=null) {
		if(is_array($attributes) && isset($attributes['name'])) {
			unset($attributes['name']);
		}
		if($checked !== null) {
			if(!is_array($checked)) {
				$checked = array($checked);
			}
		}
		else {
			$checked = array();
		}
		if($value === null) {
			return;
		}
		if(!is_array($value)) {
			$value = array($value);
		}
		foreach($value AS $val=>$label) {
			$checkboxObject = $this->_htmlElementFactory->create('FormElementInputCheckbox');
			$checkboxObject->setName($name);
			$checkboxObject->setLabel($label);
			$checkboxObject->setValue($val);
			if(in_array($val, $checked)) {
				$checkboxObject->setChecked(true);				
			}
			if($attributes !== null) {
				$checkboxObject->setAttributes($attributes);
			}
			if($this->_name != '') {
				$name = $this->_name;
			}
			else {
				$this->_name = $name;	
			}
			if($name == '') {
				$name = md5(time());
			}				
			$this->_checkboxes[$name][] = $checkboxObject;
		}
	}
	
	/**
	 * Ustawia wybrane wartosci dla calego elementu
	 * 
	 * @access public
	 * @param string|array Tablica wartosci jakie maja byc przypisane poszczegolnym kontrolkom grupy
	 * @return void
	 * 
	 */		
	public function setValue($value) {
		if($value === null) {
			return;
		}
		if(!is_array($value)) {
			$value = array($value);
		}
		if(isset($this->_checkboxes[$this->_name])) {
			foreach($this->_checkboxes[$this->_name] AS $checkbox) {
				$checkbox->setChecked(false); //reset
			}		
			foreach($this->_checkboxes[$this->_name] AS $checkbox) {
				if(in_array($checkbox->getValue(), $value)) {
					$checkbox->setChecked(true);
				}
			}
		}
	}
	
}

?>
