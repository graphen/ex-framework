<?php

/**
 * @class FormElementMultiRadio
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FormElementMultiRadio extends FormElementAbstract {
	
	/**
	 * Tablica elementow radio
	 *
	 * @var array
	 * 
	 */	
	protected $_radios = array();
	
	/**
	 * Nazwa elementu
	 *
	 * @var string
	 * 
	 */		
	protected $_name = '';
	
	/**
	 * Licznik wybranych elementow radio
	 *
	 * @var int
	 * 
	 */		
	protected $_checkedCounter = 0;
	
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
	 * Ustawia atrybut name elementu html
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @return void
	 * 
	 */		
	public function setName($name) {
		if($name !== null) {
			$this->_name = $name;
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
		if(isset($this->_radios[$this->_name])) {
			foreach($this->_radios[$this->_name] AS $radio) {
				$labelObject = $radio->getLabel();
				$label = '';
				if($labelObject instanceof HtmlElementLabel) {
					$label = $labelObject->fetchHtml();
				}
				$htmlString .= $radio->fetchHtml() . " " . $label . "<br />\n";
			}
		}		
		return $htmlString;
	}	
	
	
	/**
	 * Dodaje obiekt radio (kontrolke) do listy 
	 * 
	 * @access public
	 * @param object Obiekt radio
	 * @return void
	 * 
	 */		
	public function addElement($radioElement) {
		if(is_array($radioElement)) {
			foreach($radioElement AS $radio) {
				$this->addElement($radio);
			}
		}
		else {
			if(is_object($radioElement)) {
				if($radioElement instanceof HtmlElementInputRadio) {
					$checked = $radioElement->getChecked();
					if($checked === true) {
						$this->_checkedCounter++;
						if($this->_checkedCounter > 1) {
							$radioElement->setChecked(false);
						}
					}
					if($this->_name != '') {
						$name = $this->_name;
					}
					else {
						$name = $radioElement->getName();
						$this->_name = $name;
					}
					if($name == '') {
						$name = md5(time());
					}	
					$this->_radios[$name][] = $radioElement;	
				}
			}
		}
	}
	
	/**
	 * Tworzy i dodaje obiekt radio (kontrolke) do listy
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param array Wartosc kontrolki i etykieta
	 * @param bool Wybrano kontrolke
	 * @param array Pozostale atrybuty
	 * @return void
	 * 
	 */		
	public function addRadio($name, $value=null, $checked=false, $attributes=null) {
		if(is_array($attributes) && isset($attributes['name'])) {
			unset($attributes['name']);
		}
		if(is_array($attributes) && isset($attributes['checked'])) {
			unset($attributes['checked']);
		}
		if($value === null) {
			return;
		}
		if(!is_array($value)) {
			$value = array($value);
		}
		$radioObject = $this->_htmlElementFactory->create('FormElementInputRadio');
		$radioObject->setName($name);
		$radioObject->setValue(key($value));
		$radioObject->setLabel(current($value));
		$radioObject->setChecked((bool)$checked);
		if($attributes !== null) {
			$radioObject->setAttributes($attributes);
		}
		$checkedFetched = $radioObject->getChecked();
		if($checkedFetched === true) {
			$this->_checkedCounter++;
			if($this->_checkedCounter > 1) {
				$radioObject->setChecked(false);
			}
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
		$this->_radios[$name][] = $radioObject;
	}
	
	/**
	 * Tworzy i dodaje zestaw obiektow radio (kontrolki) do listy
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param array Tablica wartosci dla grupy kontrolek radio
	 * @param string Wartosc wybrana wsrod grupy kontrolek radio
	 * @param array Pozostale atrybuty
	 * @return void
	 * 
	 */				
	public function addRadios($name, $value=null, $checked=null, $attributes=null) {
		if(is_array($attributes) && isset($attributes['name'])) {
			unset($attributes['name']);
		}
		if(is_array($attributes) && isset($attributes['checked'])) {
			unset($attributes['checked']);
		}
		if(is_array($checked)) {
			if(count($checked) > 0) {
				$checked = $checked[0];
			}
			else {
				$checked = null;
			}
		}
		if($value === null) {
			return;
		}
		if(!is_array($value)) {
			$value = array($value);
		}
		foreach($value AS $val=>$label) {
			$radioObject = $this->_htmlElementFactory->create('FormElementInputRadio');
			$radioObject->setName($name);
			$radioObject->setValue($val);
			$radioObject->setLabel($label);			
			if($checked !== null) {
				if($val == $checked) {					
					$radioObject->setChecked((bool)$checked);				
				}
			}
			if($attributes !== null) {
				$radioObject->setAttributes($attributes);
			}
			$checkedFetched = $radioObject->getChecked();
			if($checkedFetched === true) {
				$this->_checkedCounter++;
				if($this->_checkedCounter > 1) {
					$radioObject->setChecked(false);
				}
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
			$this->_radios[$name][] = $radioObject;			
		}
	}
	
	/**
	 * Ustawia wybrane wartosci dla calego elementu
	 * 
	 * @access public
	 * @param string Wartosc jaka ma byc przypisana zestawowi kontrolek radio
	 * @return void
	 * 
	 */		
	public function setValue($value) {
		if($value == null) {
			return;
		}
		if(is_array($value)) {
			if(count($value) > 0) {
				$value = $value[0];
			}
			else {
				$value = null;
			}
		}
		if($value != null) {
			if(isset($this->_radios[$this->_name])) {
				foreach($this->_radios[$this->_name] AS $radio) {
					$radio->setChecked(false); //reset
				}				
				foreach($this->_radios[$this->_name] AS $radio) {
					if($radio->getValue() === $value) {
						$radio->setChecked(true);
					}
				}
			}
		}
	}
	
}

?>
