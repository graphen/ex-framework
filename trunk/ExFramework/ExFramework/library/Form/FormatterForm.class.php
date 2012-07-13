<?php

/**
 * @class FormatterForm
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FormatterForm extends FormatterAbstract {
	
	/**
	 * Formularz
	 *
	 * @var object
	 * 
	 */		
	protected $_form = null;
	
	/**
	 * Tag HTML dla calego formularza
	 *
	 * @var string
	 * 
	 */			
	protected $_rowDecoratorTag = 'div';
	
	/**
	 * Tablica atrybutow tagu HTML calego formularza
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
	 * Ustawia obiekt formularza, ktory bedzie dekorowany
	 * 
	 * @access protected
	 * @param object Formularz
	 * @return void
	 * 
	 */			
	public function setForm(Form $form) {
		$this->_form = $form;
		//reset ustawien
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
		$this->fetchDecoratorRules($this->_form->getDecoratorRules());
		$formHtml = $this->_form->fetchHtml();
		$row = $this->prepareRowHtml($formHtml);
		return $row;
	}	
	
	/**
	 * Tworzy i zwraca ciag z formularzem
	 * 
	 * @access protected
	 * @param string Ciag reprezentujacy formularz
	 * @return string
	 * 
	 */			
	protected function prepareRowHtml($form) {
		if(($form === null) || ($form === '')) {
			return "";
		}
		else {
			$rowHtml = "";
			$rowHtml .= "<" . $this->_rowDecoratorTag . $this->buildAttributesString($this->_rowDecoratorTagAttributes) . ">\n";
			$rowHtml .= $form;
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
		if(!is_array($rules)) {
			return;
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
