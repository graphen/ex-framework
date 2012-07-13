<?php

/**
 * @class FormatterFormElement
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FormatterFormElement extends FormatterAbstract {
	
	/**
	 * Element formularza (kontrolka)
	 *
	 * @var object
	 * 
	 */		
	protected $_formElement = null;
	
	/**
	 * Tag HTML dla etykiety
	 *
	 * @var string
	 * 
	 */		
	protected $_labelDecoratorTag = 'dt';
	
	/**
	 * Tablica atrybutow tagu HTML etykiety
	 *
	 * @var array
	 * 
	 */	
	protected $_labelDecoratorTagAttributes = array();
	
	/**
	 * Etykieta widoczna/nie widoczna
	 *
	 * @var bool
	 * 
	 */	
	protected $_labelDecoratorTagShow = true;
	
	/**
	 * Tag HTML dla komunikatow bledow
	 *
	 * @var string
	 * 
	 */	
	protected $_errorsDecoratorTag = 'div';
	
	/**
	 * Tablica atrybutow tagu HTML komunikatow bledow
	 *
	 * @var array
	 * 
	 */		
	protected $_errorsDecoratorTagAttributes = array();
	
	/**
	 * Komunikaty bledow widoczne/nie widoczne
	 *
	 * @var bool
	 * 
	 */		
	protected $_errorsDecoratorTagShow = true;
	
	/**
	 * Tablica atrybutow tagu HTML listy komunikatow bledow
	 *
	 * @var array
	 * 
	 */		
	protected $_errorsListTagAttributes = array();
	
	/**
	 * Tablica atrybutow tagu HTML elementu listy komunikatow bledow
	 *
	 * @var array
	 * 
	 */		
	protected $_errorsListElementTagAttributes = array();	
	
	/**
	 * Tag HTML dla komunikatow pomocy
	 *
	 * @var string
	 * 
	 */		
	protected $_descriptionDecoratorTag = 'p';
	
	/**
	 * Tablica atrybutow tagu HTML komunikatow pomocy
	 *
	 * @var array
	 * 
	 */			
	protected $_descriptionDecoratorTagAttributes = array();
	
	/**
	 * Komunikaty pomocy widoczne/nie widoczne
	 *
	 * @var bool
	 * 
	 */	
	protected $_descriptionDecoratorTagShow = true;	
	
	/**
	 * Tag HTML dla samego elementu formularza
	 *
	 * @var string
	 * 
	 */			
	protected $_elementDecoratorTag = 'dd';
	
	/**
	 * Tablica atrybutow tagu HTML samego elementu formularza
	 *
	 * @var array
	 * 
	 */			
	protected $_elementDecoratorTagAttributes = array();
	
	/**
	 * Tag HTML dla calego elementu formularza razem z komunikatami pomocy, bledow i etykieta
	 *
	 * @var string
	 * 
	 */			
	protected $_rowDecoratorTag = 'div';
	
	/**
	 * Tablica atrybutow tagu HTML calego elementu formularza wlacznie z komunikatami bledow, pomocy i etykieta 
	 *
	 * @var array
	 * 
	 */			
	protected $_rowDecoratorTagAttributes = array();
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function __construct() {
		//
	}
	
	/**
	 * Ustawia obiekt elementu formularza, ktory bedzie dekorowany
	 * 
	 * @access protected
	 * @param object Element formularza (kontrolka)
	 * @return void
	 * 
	 */			
	public function setFormElement(FormElementAbstract $formElement) {
		$this->_formElement = $formElement;
		//resetowanie ustawien
		$this->_labelDecoratorTag = 'dt';
		$this->_labelDecoratorTagAttributes = array();
		$this->_labelDecoratorTagShow = true;
		$this->_errorsDecoratorTag = 'div';
		$this->_errorsDecoratorTagAttributes = array();
		$this->_errorsDecoratorTagShow = true;
		$this->_errorsListTagAttributes = array();
		$this->_errorsListElementTagAttributes = array();	
		$this->_descriptionDecoratorTag = 'p';
		$this->_descriptionDecoratorTagAttributes = array();
		$this->_descriptionDecoratorTagShow = true;	
		$this->_elementDecoratorTag = 'dd';
		$this->_elementDecoratorTagAttributes = array();
		$this->_rowDecoratorTag = 'div';
		$this->_rowDecoratorTagAttributes = array();
	}
	
	/**
	 * Tworzy i zwraca ciag html elementu
	 * 
	 * @access protected
	 * @param object Element formularza (kontrolka)
	 * @return string
	 * 
	 */		
	public function fetchHtml() {
		$this->fetchDecoratorRules($this->_formElement->getDecoratorRules());
		
		$errorsHtml = '';
		if($this->_errorsDecoratorTagShow == true) {
			$errors = $this->_formElement->getErrors();
			$errorsHtml = $this->prepareErrorsHtml($errors);		
		}
		
		$descriptionHtml = '';
		if($this->_descriptionDecoratorTagShow == true) {
			$description = $this->_formElement->getDescription();
			$descriptionHtml = $this->prepareDescriptionHtml($description);		
		}
		
		$labelHtml = '';
		if($this->_labelDecoratorTagShow == true) {
			$labelObject = $this->_formElement->getLabel();
			if(is_object($labelObject)) {
				$label = $labelObject->fetchHtml();
				$labelHtml = $this->prepareLabelHtml($label);
			}
		}	
			
		$element = $this->_formElement->fetchHtml();
		$elementHtml = $this->prepareElementHtml($element, $errorsHtml, $descriptionHtml);
		
		$row = $this->prepareRowHtml($labelHtml, $elementHtml);
		return $row;
	}
	
	/**
	 * Tworzy i zwraca ciag html z opisem bledow dla elementu
	 * 
	 * @access protected
	 * @param array Tablica komunikatow bledow
	 * @return string
	 * 
	 */		
	protected function prepareErrorsHtml($errors) {
		if(is_array($errors) && count($errors) > 0) {
			$errorsHtml = "";
			$errorsHtml .= "<" . $this->_errorsDecoratorTag . $this->buildAttributesString($this->_errorsDecoratorTagAttributes) . ">\n";
			$errorsHtml .= "<ul" . $this->buildAttributesString($this->_errorsListTagAttributes) . ">\n";
			foreach($errors AS $err) {
				$errorsHtml .= "<li" . $this->buildAttributesString($this->_errorsListElementTagAttributes) . ">";				
				$errorsHtml .= $err;
				$errorsHtml .= "</li>\n";
			}
			$errorsHtml .= "</ul>\n";			
			$errorsHtml .= "</" . $this->_errorsDecoratorTag . ">\n";
			return $errorsHtml;
		}
		else {
			return "";
		}
	}
	
	/**
	 * Tworzy i zwraca ciag html z opisem pomocy dla elementu
	 * 
	 * @access protected
	 * @param string Komunikat pomocy dla elementu
	 * @return string
	 * 
	 */		
	protected function prepareDescriptionHtml($description) {
		if(($description === null) || ($description === '')) {
			return "";
		}
		else {
			$description = (string)$description;
			$descriptionHtml = "";
			$descriptionHtml .= "<" . $this->_descriptionDecoratorTag . $this->buildAttributesString($this->_descriptionDecoratorTagAttributes) . ">\n";
			$descriptionHtml .= $description;
			$descriptionHtml .=  "</" . $this->_descriptionDecoratorTag . ">\n";
			return $descriptionHtml;
		}
	}
	
	/**
	 * Tworzy i zwraca ciag html z etykieta elementu
	 * 
	 * @access protected
	 * @param string Etykieta dla elementu
	 * @return string
	 * 
	 */			
	protected function prepareLabelHtml($label) {
		if(($label === null) || ($label === '')) {
			return "";
		}
		else {
			$label = (string)$label;
			$labelHtml = "";
			$labelHtml .= "<" . $this->_labelDecoratorTag . $this->buildAttributesString($this->_labelDecoratorTagAttributes) . ">\n";
			$labelHtml .= $label;
			$labelHtml .=  "</" . $this->_labelDecoratorTag . ">\n";
			return $labelHtml;
		}
	}
	
	
	/**
	 * Tworzy i zwraca ciag z elementem formularza (kontrolka)
	 * 
	 * @access protected
	 * @param string Element formularza
	 * @param string Opis pomocniczy
	 * @param string Ewentualne bledy
	 * @return string
	 * 
	 */			
	protected function prepareElementHtml($element, $descriptionHtml='', $errorsHtml='') {
		if(($element === null) || ($element === '')) {
			return "";
		}
		else {
			$element = (string)$element;
			$elementHtml = "";
			$elementHtml .= "<" . $this->_elementDecoratorTag . $this->buildAttributesString($this->_elementDecoratorTagAttributes) . ">\n";
			$elementHtml .= $element;
			$elementHtml .= $descriptionHtml;
			$elementHtml .= $errorsHtml;			
			$elementHtml .=  "</" . $this->_elementDecoratorTag . ">\n";
			return $elementHtml;
		}
	}
	
	/**
	 * Tworzy i zwraca ciag z elementem formularza (kontrolka) oraz etykieta, opisem pomocy oraz ewentualnymi bledami
	 * 
	 * @access protected
	 * @param string Etykieta
	 * @param string Element formularza
	 * @return string
	 * 
	 */			
	protected function prepareRowHtml($labelHtml='', $elementHtml='') {
		if(($elementHtml === null) || ($elementHtml === '')) {
			return "";
		}
		else {
			$rowHtml = "";
			$rowHtml .= "<" . $this->_rowDecoratorTag . $this->buildAttributesString($this->_rowDecoratorTagAttributes) . ">\n";
			$rowHtml .= $labelHtml;
			$rowHtml .= $elementHtml;
			$rowHtml .=  "</" . $this->_rowDecoratorTag . ">\n";
			return $rowHtml;
		}	
	}	
		
	/**
	 * Ustawia wartosci pol na podstawie zestawu regul przekazanych w argumencie jako tablica
	 * 
	 * @access protected
	 * @param array Tablica regul o okreslonym formacie
	 * @return void
	 * 
	 */		
	protected function fetchDecoratorRules($rules) {
		if($rules === null) {
			return;
		}
		if(isset($rules['label']['htmlTag'])) { 
			$this->_labelDecoratorTag = $rules['label']['htmlTag'];
		}
		if(isset($rules['label']['tagAttr']) && is_array($rules['label']['tagAttr'])) {
			$this->_labelDecoratorTagAttributes = $rules['label']['tagAttr'];
		}
		if(isset($rules['label']['show'])) {
			$this->_labelDecoratorTagShow = $rules['label']['show'];
		}


		if(isset($rules['description']['htmlTag'])) { 
			$this->_descriptionDecoratorTag = $rules['description']['htmlTag'];
		}
		if(isset($rules['description']['tagAttr']) && is_array($rules['description']['tagAttr'])) {
			$this->_descriptionDecoratorTagAttributes = $rules['description']['tagAttr'];
		}
		if(isset($rules['description']['show'])) {
			$this->_descriptionDecoratorTagShow = $rules['description']['show'];
		}				
			

		if(isset($rules['element']['htmlTag'])) { 
			$this->_elementDecoratorTag = $rules['element']['htmlTag'];
		}
		if(isset($rules['element']['tagAttr']) && is_array($rules['element']['tagAttr'])) {
			$this->_elementDecoratorTagAttributes = $rules['element']['tagAttr'];			
		}


		if(isset($rules['errors']['htmlTag'])) { 
			$this->_errorsDecoratorTag = $rules['errors']['htmlTag'];
		}
		if(isset($rules['errors']['tagAttr']) && is_array($rules['errors']['tagAttr'])) {
			$this->_errorsDecoratorTagAttributes = $rules['errors']['tagAttr'];
		}		
		if(isset($rules['errors']['show'])) {
			$this->_errorsDecoratorTagShow = $rules['errors']['show'];
		}		
		if(isset($rules['errors']['listTagAttr']) && is_array($rules['errors']['listTagAttr'])) {
			$this->_errorsListTagAttributes = $rules['errors']['listTagAttr'];
		}
		if(isset($rules['errors']['listElTagAttr']) && is_array($rules['errors']['listElTagAttr'])) {
			$this->_errorsListElementTagAttributes = $rules['errors']['listElTagAttr'];
		}				

		
		if(isset($rules['row']['htmlTag'])) { 
			$this->_rowDecoratorTag = $rules['row']['htmlTag'];
		}
		if(isset($rules['row']['tagAttr']) && is_array($rules['row']['tagAttr'])) {
			$this->_rowDecoratorTagAttributes = $rules['row']['tagAttr'];
		}
	}
	
}

?>
