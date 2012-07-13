<?php

/**
 * @class Form
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Form extends HtmlElementAbstract {
	
	/**
	 * Nazwa kontrolki
	 *
	 * @var string
	 * 
	 */	
	protected $_tag = 'form';
	
	/**
	 * Obiekt walidatora formularza
	 *
	 * @var object
	 * 
	 */	
	protected $_inputValidator = null;

	/**
	 * Obiekt filtra formularza
	 *
	 * @var object
	 * 
	 */	
	protected $_inputFilter = null;
	
	/**
	 * Obiekt dekoratora elementow formularza
	 *
	 * @var object
	 * 
	 */	
	protected $_formElementDecorator = null;	
	
	/**
	 * Obiekt dekoratora formularza
	 *
	 * @var object
	 * 
	 */	
	protected $_formDecorator = null;		
	
	/**
	 * Tablica regul dekorowania formularza
	 *
	 * @var array
	 * 
	 */	
	protected $_decoratorRules = array();	
	
	/**
	 * Tablica elementow (kontrolek) formularza
	 *
	 * @var object
	 * 
	 */	
	protected $_elements = array();

	/**
	 * Dane po filtrowaniu
	 *
	 * @var array
	 * 
	 */	
	protected $_filteredData = array();
		
	/**
	 * Inicjalizuje dodatkowe atrybuty dla elementu html
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function init() {
		parent::init();
		$this->_attributes['action'] = '';		
		$this->_attributes['accept'] = '';
		$this->_attributes['accept-charset'] = '';	
		$this->_attributes['enctype'] = '';			
		$this->_attributes['method'] = 'post';		
		$this->_attributes['name'] = '';		
	}
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt fabryczny elementow formularza
	 * @param object Obiekt walidatora formularza
	 * @param object Obiekt dekoratora elementow formularza
	 * @return void
	 * 
	 */		
	public function __construct(IFactory $htmlElementFactory, IValidator $inputValidator, IFilter $inputFilter, FormatterForm $formDecorator, FormatterFormElement  $formElementDecorator) {
		$this->_htmlElementFactory = $htmlElementFactory;
		$this->_inputValidator = $inputValidator;
		$this->_inputFilter = $inputFilter;
		$this->_formElementDecorator = $formElementDecorator;
		$this->_formDecorator = $formDecorator;
		$this->_formDecorator->setForm($this);
		self::init();
	}

	/**
	 * Dodaje obiekt option (kontrolke) do listy
	 * 
	 * @access public
	 * @param object Obiekt FormElement
	 * @return void
	 * 
	 */			
	public function addElement($formElement) {
		if(is_array($formElement)) {
			foreach($formElement AS $element) {
				$this->addElement($element);
			}
		}
		else {
			if(is_object($formElement)) {
				if($formElement instanceof FormElementAbstract) {
					$name = $formElement->getName();
					$name = str_replace(array('[', ']'), '', $name);
					//if(isset($this->_elements[$name])) { //proba dolaczania grup kontrolek, to trzeba zrobic inaczej
						//$tmp = $this->_elements[$name];
						//$this->_elements[$name] = array();
						//$this->_elements[$name][] = $tmp;
						//$this->_elements[$name][] = $formElement;
					//}
					//else {
						$this->_elements[$name] = $formElement;
						if($formElement instanceof FormElementInputFile) {
							$this->setEnctype('multipart/form-data');
							$this->setMethod('post');
						}
					//}
				}
				else {
					$this->_elements[] = $formElement;
				}
			}
		}
	}
	
	/**
	 * Tworzy i zwraca ciag definiujacy nieudekorowany formularz 
	 * 
	 * @access protected
	 * @return string
	 * 
	 */		
	public function fetchHtml() {
		$htmlString = '';
		$htmlString .= $this->getHeader();
		foreach($this->_elements AS $element) {
			if(is_array($element)) {
				foreach($element AS $e) {
					$this->_formElementDecorator->setFormElement($e);
					$htmlString .= $this->_formElementDecorator->fetchHtml();					
				}
			}
			else {
				$this->_formElementDecorator->setFormElement($element);
				$htmlString .= $this->_formElementDecorator->fetchHtml();
			}
		}
		$htmlString .= $this->getFooter();
		return $htmlString;
	}
	
	/**
	 * Tworzy i zwraca ciag definiujacy formularz 
	 * 
	 * @access public
	 * @return string
	 * 
	 */			
	public function fetchForm() {
		return $this->_formDecorator->fetchHtml();
	}
	
	/**
	 * Ustawia wybrane wartosci dla poszczegolnych elementow
	 * 
	 * @access public
	 * @param string|array Tablica wartosci jakie maja byc przypisane elementom
	 * @return void
	 * 
	 */		
	public function setValues($values=array()) {
		if($values === null) {
			return;
		}
		if(!is_array($values)) {
			$values = array($values);
		}
		foreach($values AS $name => $value) {
			if(isset($this->_elements[$name])) {
				//if((is_array($this->_elements[$name])) && (count($this->_elements[$name]) > 0)) {
				//	foreach($this->_elements[$name] AS $element) {
						//$element->setValue($value);
					//}
				//}
				//else {
					$this->_elements[$name]->setValue($value);			
				//}
			}
		}
	}	
	
	/**
	 * Ustawia bledy zaistniale po walidacji dla poszczegolnych elementow
	 * 
	 * @access public
	 * @param string|array Tablica bledow jakie maja byc przypisane do elementow
	 * @return void
	 * 
	 */		
	public function setErrors($errors=array()) {
		if($errors === null) {
			return;
		}		
		if(!is_array($errors)) {
			$errors = array($errors);
		}
		foreach($errors AS $name => $err) {
			if(isset($this->_elements[$name])) {
				//if((is_array($this->_elements[$name])) && (count($this->_elements[$name]) > 0)) {
					//foreach($this->_elements[$name] AS $element) {
						//$element->setErrors($err);
					//}
				//}
				//else {				
					$this->_elements[$name]->setErrors($err);
				//}
			}
		}
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
		if($rule === null) {
			return;
		}
		if(is_array($rule)) {
			$this->_decoratorRules = array_merge($this->_decoratorRules, $rule);
		}
	}
	
	/**
	 * Ustawia zestaw regul dekorowania dla formularza
	 * 
	 * @access public
	 * @param array Tablica tablic z regulami dekorowania
	 * @return void
	 * 
	 */		
	public function addDecoratorRules($rules) {
		if($rules === null) {
			return;
		}		
		foreach($rules AS $index=>$rule) {
			if(is_array($rule)) {
				$this->_decoratorRules[$index] = $rule;
			}
		}
	}
	
	/**
	 * Zwraca tablice z regulami dekorowania formularza
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getDecoratorRules() {
		return $this->_decoratorRules;
	}	
	
	/**
	 * Zwraca tablice z elementami formularza
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getElements() {
		return $this->_elements;
	}		
	
	/**
	 * Zwraca ciag bedacy naglowkiem formularza
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getHeader() {
		return "<" . $this->_tag . $this->buildAttributesString() . ">\n";
	}
	
	/**
	 * Zwraca ciag bedacy stopka formularza
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getFooter() {
		return "</" . $this->_tag . ">\n";
	}	

	/**
	 * Zwraca dane formularza po filtrowaniu 
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function getFilteredData() {
		return $this->_filteredData;
	}
	
	/*
	 *******************************************************************
	 * Atrybuty formularza
	 *******************************************************************
	 */		
	
	/**
	 * Ustawia atrybut action elementu html
	 * 
	 * @access public
	 * @param string URL na ktory ma zostac wyslany formularz
	 * @return void
	 * 
	 */		
	public function setAction($action) {
		$this->setAttrib('action', $action);
	}
	
	/**
	 * Ustawia atrybut accept elementu html
	 * 
	 * @access public
	 * @param string Akceptowany format
	 * @return void
	 * 
	 */		
	public function setAccept($accept) {
		$this->setAttrib('accept', $accept);
	}
	
	/**
	 * Ustawia atrybut accept-charset elementu html
	 * 
	 * @access public
	 * @param string Akceptowany format znakow
	 * @return void
	 * 
	 */		
	public function setAcceptCharset($acceptCharset) {
		$this->setAttrib('accept-charset', $acceptCharset);
	}
	
	/**
	 * Ustawia atrybut enctype elementu html
	 * 
	 * @access public
	 * @param string Kodowanie
	 * @return void
	 * 
	 */	
	public function setEnctype($enctype) {
		$this->setAttrib('enctype', $enctype);
	}
	
	/**
	 * Ustawia atrybut method elementu html
	 * 
	 * @access public
	 * @param string Metoda przesylania formularza post lub get
	 * @return void
	 * 
	 */		
	public function setMethod($method='post') {
		$this->setAttrib('method', $method);
	}
	
	/**
	 * Ustawia atrybut name elementu html
	 * 
	 * @access public
	 * @param string Nazwa formularza
	 * @return void
	 * 
	 */		
	public function setName($name) {
		$this->setAttrib('name', $name);
	}	
	
	/**
	 * Zwraca wartosc atrybutu action elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */				
	public function getAction() {
		return $this->getAttrib('action');
	}
	
	/**
	 * Zwraca wartosc atrybutu accept elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */				
	public function getAccept() {
		return $this->getAttrib('accept');
	}
	
	/**
	 * Zwraca wartosc atrybutu accept-charset elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */				
	public function getAcceptCharset() {
		return $this->getAttrib('accept-charset');
	}
	
	/**
	 * Zwraca wartosc atrybutu enctype elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */				
	public function getEnctype() {
		return $this->getAttrib('enctype');
	}
	
	/**
	 * Zwraca wartosc atrybutu method elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */				
	public function getMethod() {
		return $this->getAttrib('method');
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
		
	/*
	 *******************************************************************
	 * Kontrolki
	 *******************************************************************
	 */	
	
	/**
	 * Tworzy obiekt reprezentujacy kontrolke typu input
	 * 
	 * @access protected
	 * @param string Typ kontrolki input
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return object
	 * 
	 */		
	protected function addInput($type, $name, $value=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		if(is_array($attributes) && isset($attributes['name'])) {
			unset($attributes['name']);
		}
		if(is_array($attributes) && isset($attributes['value'])) {
			unset($attributes['value']);
		}
		$input = $this->_htmlElementFactory->create($type);
		$input->setName($name);
		if($value !== null) {
			$input->setValue($value);
		}
		if($attributes !== null) {
			$input->setAttributes($attributes);
		}
		if($labelString !== null) {
			$for = null;
			if(isset($attributes['id'])) {
				$for = $attributes['id'];
			}
			$input->addLabel($labelString, $for);
		}
		if($dsc !== null) {
			$input->setDescription($dsc);
		}
		if($validatorRules !== null) {
			$input->addValidatorRules($validatorRules);
		}
		if($filterRules !== null) {
			$input->addFilterRules($filterRules);
		}
		if($decoratorRules !== null) {
			$input->addDecoratorRules($decoratorRules);
		}
		return $input;		
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu input text
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */		
	public function addInputText($name, $value=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$type = 'FormElementInputText';
		$input = $this->addInput($type, $name, $value, $attributes, $labelString, $dsc, $validatorRules, $filterRules, $decoratorRules);
		$this->addElement($input);
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu input password
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */		
	public function addInputPassword($name, $value=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$type = 'FormElementInputPassword';
		$input = $this->addInput($type, $name, $value, $attributes, $labelString, $dsc, $validatorRules, $filterRules, $decoratorRules);
		$this->addElement($input);
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu input hidden
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania 
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */		
	public function addInputHidden($name, $value=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$type = 'FormElementInputHidden';
		$input = $this->addInput($type, $name, $value, $attributes, $labelString, $dsc, $validatorRules, $filterRules, $decoratorRules);
		$this->addElement($input);
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu input radio
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */		
	public function addInputRadio($name, $value=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$type = 'FormElementInputRadio';
		$input = $this->addInput($type, $name, $value, $attributes, $labelString, $dsc, $validatorRules, $filterRules, $decoratorRules);
		$this->addElement($input);
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu input checkbox
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */		
	public function addInputCheckbox($name, $value=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$type = 'FormElementInputCheckbox';
		$input = $this->addInput($type, $name, $value, $attributes, $labelString, $dsc, $validatorRules, $filterRules, $decoratorRules);
		$this->addElement($input);
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu input submit
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */		
	public function addInputSubmit($name, $value=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$type = 'FormElementInputSubmit';
		$input = $this->addInput($type, $name, $value, $attributes, $labelString, $dsc, $validatorRules, $filterRules, $decoratorRules);
		$this->addElement($input);
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu input reset
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */		
	public function addInputReset($name, $value=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$type = 'FormElementInputReset';
		$input = $this->addInput($type, $name, $value, $attributes, $labelString, $dsc, $validatorRules, $filterRules, $decoratorRules);
		$this->addElement($input);
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu input button
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */		
	public function addInputButton($name, $value=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$type = 'FormElementInputButton';
		$input = $this->addInput($type, $name, $value, $attributes, $labelString, $dsc, $validatorRules, $filterRules, $decoratorRules);
		$this->addElement($input);
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu input file
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */		
	public function addInputFile($name, $value=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$type = 'FormElementInputFile';
		$input = $this->addInput($type, $name, $value, $attributes, $labelString, $dsc, $validatorRules, $filterRules, $decoratorRules);
		$this->addElement($input);
		$this->setEnctype('multipart/form-data');
		$this->setMethod('post');		
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu input image
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */		
	public function addInputImage($name, $value=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$type = 'FormElementInputImage';
		$input = $this->addInput($type, $name, $value, $attributes, $labelString, $dsc, $validatorRules, $filterRules, $decoratorRules);
		$this->addElement($input);
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu textarea
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */		
	public function addTextarea($name, $value=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		if(is_array($attributes)  && isset($attributes['name'])) {
			unset($attributes['name']);
		}
		if(is_array($attributes)  && isset($attributes['value'])) {
			unset($attributes['value']);
		}
		$textArea = $this->_htmlElementFactory->create('FormElementTextArea');
		$textArea->setName($name);
		if($value !== null) {
			$textArea->setValue($value);
		}
		if($attributes !== null) {
			$textArea->setAttributes($attributes);
		}
		if($labelString !== null) { 
			$textArea->setLabel($labelString);
		}
		if($dsc !== null) {
			$textArea->setDescription($dsc);
		}
		if($validatorRules !== null) {
			$textArea->addValidatorRules($validatorRules);
		}
		if($filterRules !== null) {
			$textArea->addFilterRules($filterRules);
		}
		if($decoratorRules !== null) {
			$textArea->addDecoratorRules($decoratorRules);
		}
		$this->addElement($textArea);		
	}
	
	/**
	 * Tworzy obiekt reprezentujacy kontrolke typu button
	 * 
	 * @access protected
	 * @param string Typ kontrolki button
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param string Zawartosc kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return object
	 * 
	 */
	protected function addButton($type, $name, $value=null, $content=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		if(is_array($attributes) && isset($attributes['name'])) {
			unset($attributes['name']);
		}
		if(is_array($attributes)  && isset($attributes['value'])) {
			unset($attributes['value']);
		}
		$button = $this->_htmlElementFactory->create($type);
		$button->setName($name);
		if($value !== null) {
			$button->setValue($value);
		}
		if($content !== null) {
			$button->setContent($content);
		}
		if($attributes !== null) {
			$button->setAttributes($attributes);
		}
		if($labelString !== null) {
			$button->setLabel($labelString);
		}
		if($dsc !== null) {
			$button->setDescription($dsc);
		}
		if($validatorRules !== null) {
			$button->addValidatorRules($validatorRules);
		}
		if($filterRules !== null) {
			$button->addFilterRules($filterRules);
		}
		if($decoratorRules !== null) {
			$button->addDecoratorRules($decoratorRules);
		}
		return $button;
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu button 
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param string Zawartosc kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */			
	public function addButtonButton($name, $value=null, $content=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$type = 'FormElementButtonButton';
		$button = $this->addButton($type, $name, $value, $content, $attributes, $labelString, $dsc, $validatorRules, $filterRules, $decoratorRules);
		$this->addElement($button);
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu submit button
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param string Zawartosc kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */		
	public function addButtonSubmit($name, $value=null, $content=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$type = 'FormElementButtonSubmit';
		$button = $this->addButton($type, $name, $value, $content, $attributes, $labelString, $dsc, $validatorRules, $filterRules, $decoratorRules);
		$this->addElement($button);
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu reset button
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param mixed Wartosc/wartosci dla kontrolki
	 * @param string Zawartosc kontrolki
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */			
	public function addButtonReset($name, $value=null, $content=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$type = 'FormElementButtonReset';
		$button = $this->addButton($type, $name, $value, $content, $attributes, $labelString, $dsc, $validatorRules, $filterRules, $decoratorRules);
		$this->addElement($button);
	}

	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu select
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param array Tablica przechowujaca zestawy wartosc=opcja dla kontrolki select
	 * @param array Tablica wartosci wybranych w kontrolce
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @param array Atrybuty dla opcji
	 * @return void
	 * 
	 */		
	public function addSelect($name, $options, $value=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null, $optionAttributes=null) {
		$selectObject = $this->_htmlElementFactory->create('FormElementSelect');
		$selectObject->setName($name);
		if($attributes !== null) {
			$selectObject->setAttributes($attributes);
		}
		$selectObject->addOptions($options, $value, $optionAttributes);
		if($labelString !== null) {
			$selectObject->setLabel($labelString);
		}
		if($dsc !== null) {
			$selectObject->setDescription($dsc);
		}
		if($validatorRules !== null) {
			$selectObject->addValidatorRules($validatorRules);
		}
		if($filterRules !== null) {
			$selectObject->addFilterRules($filterRules);
		}
		if($decoratorRules !== null) {
			$selectObject->addDecoratorRules($decoratorRules);
		}
		$this->addElement($selectObject);
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu multicheckbox
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param array Tablica przechowujaca wartosci kontrolek checkbox dla kontrolki multicheckbox
	 * @param array Tablica wartosci wybranych w kontrolce
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */	
	public function addMultiCheckbox($name, $value=null, $checked=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$multiCheckboxObject = $this->_htmlElementFactory->create('FormElementMultiCheckbox');
		$multiCheckboxObject->addCheckboxes($name, $value, $checked, $attributes);
		if($labelString !== null) {
			$multiCheckboxObject->setLabel($labelString);
		}
		if($dsc !== null) {
			$multiCheckboxObject->setDescription($dsc);
		}
		if($validatorRules !== null) {
			$multiCheckboxObject->addValidatorRules($validatorRules);
		}
		if($filterRules !== null) {
			$multiCheckboxObject->addFilterRules($filterRules);
		}
		if($decoratorRules !== null) {
			$multiCheckboxObject->addDecoratorRules($decoratorRules);
		}
		$this->addElement($multiCheckboxObject);		
	}
	
	/**
	 * Dodaje do formularza obiekt reprezentujacy kontrolke typu multiradio
	 * 
	 * @access public
	 * @param string Nazwa kontrolki
	 * @param array Tablica przechowujaca wartosci kontrolek radio dla kontrolki multiradio
	 * @param mixed Wartosc wybrana w kontrolce
	 * @param array Atrybuty kontrolki
	 * @param string Etykieta dla kontrolki
	 * @param string Opis kontrolki
	 * @param array Reguly walidatora
	 * @param array Reguly filtrowania
	 * @param array Reguly dekoratora
	 * @return void
	 * 
	 */	
	public function addMultiRadio($name, $value=null, $checked=null, $attributes=null, $labelString=null, $dsc=null, $validatorRules=null, $filterRules=null, $decoratorRules=null) {
		$multiRadioObject = $this->_htmlElementFactory->create('FormElementMultiRadio');
		$multiRadioObject->addRadios($name, $value, $checked, $attributes);
		if($labelString !== null) {
			$multiRadioObject->setLabel($labelString);
		}
		if($dsc !== null) {
			$multiRadioObject->setDescription($dsc);
		}
		if($validatorRules !== null) {
			$multiRadioObject->addValidatorRules($validatorRules);
		}
		if($filterRules !== null) {
			$multiRadioObject->addFilterRules($filterRules);
		}
		if($decoratorRules !== null) {
			$multiRadioObject->addDecoratorRules($decoratorRules);
		}
		$this->addElement($multiRadioObject);
	}
	
	/*
	 *******************************************************************
	 * Walidacja i filtrowanie
	 *******************************************************************
	 */
	
	/**
	 * Pobiera z obiektow elementow formularza reguly walidacji i na ich podstawie tworzy walidatory i dodatkowe reguly
	 * 
	 * @access protected
	 * @return array
	 * 
	 */	
	protected function prepareValidatorsRules() {
		$validatorRules = array();
		foreach($this->_elements AS $name=>$element) {
			if($element instanceof FormElementAbstract) {
				$rules = $element->getValidatorRules();
				if((is_array($rules)) && (count($rules) > 0)) {
					$validatorRules[$name] = $rules;
				}
			}
		}
		return $validatorRules;
	}
	
	/**
	 * Pobiera z obiektow elementow formularza reguly filtrowania i na ich podstawie tworzy filtry i dodatkowe reguly
	 * 
	 * @access protected
	 * @return array
	 * 
	 */	
	protected function prepareFiltersRules() {
		$filtersRules = array();
		foreach($this->_elements AS $name=>$element) {
			if($element instanceof FormElementAbstract) {
				$rules = $element->getFilterRules();
				if((is_array($rules)) && (count($rules) > 0)) {
					$filterRules[$name] = $rules;
				}
			}
		}		
		return $filterRules;
	}	
	
	/**
	 * Sprawdza, czy po weryfikcji danych pojawily sie bledy
	 * 
	 * @access public
	 * @return bool
	 * 
	 */		
	public function hasErrors() {
		return $this->_inputValidator->hasErrors();
	}
	
	/**
	 * Zwraca bledy weryfikacji, jesli sa
	 * 
	 * @access public
	 * @return array
	 * 
	 */			
	public function getErrors() {
		return $this->_inputValidator->getErrors();
	}
	
	/**
	 * Czysci tablice bledow
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function clearErrors() {
		$this->_inputValidator->reset();
	}
	
	/**
	 * Przeprowdza filtrowanie i weryfikacje formularza
	 * 
	 * @access public
	 * @param array Tablica do walidacji
	 * @return bool
	 * 
	 */		
	public function isValid($data=array()) {
		$this->clearErrors();
		$this->_inputFilter->setRules($this->prepareFiltersRules());
		$dataFiltered = $this->_inputFilter->filter($data);
		$this->_inputValidator->setRules($this->prepareValidatorsRules());
		$result = $this->_inputValidator->isValid($this->getName(), $dataFiltered);
		//if($result === false) {
			//$this->setValues($dataFiltered);
		//}
		$this->_filteredData = $dataFiltered;
		return $result;
	}	
	
}

?>
