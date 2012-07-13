<?php

/**
 * @class FormElementAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class FormElementAbstract extends HtmlElementAbstract {
	
	/**
	 * Tablica regul walidacji
	 *
	 * @var array
	 * 
	 */		
	protected $_validatorRules = array();
	
	/**
	 * Tablica regul filtrowania
	 *
	 * @var array
	 * 
	 */	
	protected $_filterRules = array();
	
	/**
	 * Tablica regul dekorowania elementow
	 *
	 * @var array
	 * 
	 */	
	protected $_decoratorRules = array();
	
	/**
	 * Opis elementu
	 *
	 * @var string
	 * 
	 */		
	protected $_description = '';
	
	/**
	 * Tablica komunikatow bledow elementu
	 *
	 * @var array
	 * 
	 */		
	protected $_errors = array();
	
	/**
	 * Obiekt  reprezentujacy kontrolke label powiazana z dana kontrolka
	 *
	 * @var object
	 * 
	 */		
	protected $_label = null;	
		
	/**
	 * Inicjalizuje dodatkowe atrybuty dla elementu html
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function init() {
		parent::init();
		$this->_stdAttributes['accesskey'] = '';
		$this->_stdAttributes['tabindex'] = '';
	}
	
	/**
	 * Ustawia wartosc pola description
	 * 
	 * @access public
	 * @param string Opis elementu
	 * @return void
	 * 
	 */		
	public function setDescription($dsc='') {
		if($dsc !== null) {
			$this->_description = $dsc;
		}
	}
	
	/**
	 * Zwraca wartosc pola description
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getDescription() {
		return $this->_description;
	}
	
	/**
	 * Ustawia wartosc pola errors
	 * 
	 * @access public
	 * @param array Tablica komunikatow bledow
	 * @return void
	 * 
	 */		
	public function setErrors($errors) {
		if($errors === null) {
			return;
		}
		if(!is_array($errors)) {
			$errors = array($errors);
		}
		$this->_errors = $errors;
	}
	
	/**
	 * Zwraca wartosc pola errors
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function getErrors() {
		return $this->_errors;
	}
	
	/**
	 * Ustawia atrybut accesskey elementu html
	 * 
	 * @access public
	 * @param string Skrot klawiszowy
	 * @return void
	 * 
	 */	
	public function setAccessKey($accessKey) {
		$this->setStdAttrib('accesskey', $accessKey);
	}
	
	/**
	 * Ustawia atrybut tabindex elementu html
	 * 
	 * @access public
	 * @param string Indeks dla tabulatora
	 * @return void
	 * 
	 */	
	public function setTabIndex($tabindex) {
		$this->setStdAttrib('tabindex', $tabindex);
	}
	
	/**
	 * Zwraca wartosc atrybutu accesskey elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getAccessKey() {
		return $this->getStdAttrib('accesskey');
	}
	
	/**
	 * Zwraca wartosc atrybutu tabindex elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getTabIndex() {
		return $this->getStdAttrib('tabindex');
	}
	
	/**
	 * Dodaje regule walidacji dla danego elementu (kontrolki) formularza
	 * 
	 * @access public
	 * @param array|string Regula walidacji
	 * @return void
	 * 
	 */		
	public function addValidatorRule() {
		$numArgs = func_num_args();
		$args = func_get_args();
		if($numArgs == 1) {
			if($args[0]==null) {
				return;
			}
			$this->_validatorRules[] = $args[0];
		}
		elseif($numArgs == 2) {
			$this->_validatorRules[$args[0]] = $args[1];	
		}
	}
	
	/**
	 * Ustawia zestaw regul walidacji dla danego elementu (kontrolki) formularza
	 * 
	 * @access public
	 * @param array Tablica z regulami walidacji
	 * @return void
	 * 
	 */		
	public function addValidatorRules($rules) {
		if($rules === null) {
			return;
		}
		if(is_array($rules)) {
			foreach($rules AS $index=>$rule) {
				$this->_validatorRules[$index] = $rule;
			}
		}
	}
	
	/**
	 * Zwraca tablice z regulami walidacji danego elementu (kontrolki) formularza
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getValidatorRules() {
		return $this->_validatorRules;
	}
	
	/**
	 * Dodaje regule filtrowania dla danego elementu (kontrolki) formularza
	 * 
	 * @access public
	 * @param array|string Regula filtrowania
	 * @return void
	 * 
	 */		
	public function addFilterRule() {
		$numArgs = func_num_args();
		$args = func_get_args();
		if($numArgs == 1) {
			if($args[0]==null) {
				return;
			}			
			$this->_filterRules[] = $args[0];
		}
		elseif($numArgs == 2) {
			$this->_filterRules[$args[0]] = $args[1];	
		}
	}
	
	/**
	 * Ustawia zestaw regul filtrowania dla danego elementu (kontrolki) formularza
	 * 
	 * @access public
	 * @param array Tablica z regulami filtrowania
	 * @return void
	 * 
	 */		
	public function addFilterRules($rules) {
		if($rules === null) {
			return;
		}
		if(is_array($rules)) {
			foreach($rules AS $index=>$rule) {
				$this->_filterRules[$index] = $rule;
			}
		}		
	}
	
	/**
	 * Zwraca tablice z regulami filtrowania danego elementu (kontrolki) formularza
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getFilterRules() {
		return $this->_filterRules;
	}	
	
	/**
	 * Dodaje regule dekorowania dla danego elementu (kontrolki) formularza
	 * 
	 * @access public
	 * @param array Regula dekorowania
	 * @return void
	 * 
	 */		
	public function addDecoratorRule($rule) {
		if($rule==null) {
			return;
		}		
		if(is_array($rule)) {
			$this->_decoratorRules = array_merge($this->_decoratorRules, $rule);
		}
	}
	
	/**
	 * Ustawia zestaw regul dekorowania dla danego elementu (kontrolki) formularza
	 * 
	 * @access public
	 * @param array Tablica z regulami dekorowania
	 * @return void
	 * 
	 */		
	public function addDecoratorRules($rules) {
		if($rules === null) {
			return;
		}
		foreach($rules AS $index=>$rule) {
			$this->_decoratorRules[$index] = $rule;
		}
	}
	
	/**
	 * Zwraca tablice z regulami dekorowania danego elementu (kontrolki) formularza
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getDecoratorRules() {
		return $this->_decoratorRules;
	}	
	
	/**
	 * Dodaje powiazany z danym elementem obiekt label
	 * 
	 * @access public
	 * @param object
	 * @return void
	 * 
	 */		
	public function  addElement($element) {
		if($element instanceof HtmlElementLabel) {
			$this->_label = $element;
		}
	}	
	
	/**
	 * Tworzy i dodaje powiazany z danym elementem obiekt label
	 * 
	 * @access public
	 * @param string Etykieta
	 * @param string Id elementu formularza
	 * @return void
	 * 
	 */	
	public function addLabel($label, $for=null) {
		if($label === null) {
			$label = '';
		}
		$labelObject = $this->_htmlElementFactory->create('HtmlElementLabel');
		$labelObject->setLabel($label);
		if($for !== null) {
			$labelObject->setFor($for);
		}
		$this->_label = $labelObject;
	}	
	
	/**
	 * Tworzy i dodaje powiazany z danym elementem obiekt label. Alias do addLabel()
	 * 
	 * @access public
	 * @param string Etykieta
	 * @param string Id elementu formularza
	 * @return void
	 * 
	 */	
	public function setLabel($label, $for=null) {
		$this->addLabel($label, $for);
	}		

	/**
	 * Zwraca obiekt label
	 * 
	 * @access public
	 * @return object
	 * 
	 */	
	public function getLabel() {
		return $this->_label;	
	}
	
	/**
	 * Metoda abstrakcyjna
	 * Ustawia wartosc elementu (kontrolki) formularza
	 * 
	 * @access public
	 * @param mixed Wartosc elementu formularza
	 * @return void
	 * 
	 */		
	abstract public function setValue($value);			
	
}

?>
