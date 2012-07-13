<?php

/**
 * @class HtmlElementLabel
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class HtmlElementLabel extends HtmlElementAbstract {
	
	/**
	 * Nazwa kontrolki
	 *
	 * @var string
	 * 
	 */			
	protected $_tag = 'label';
	
	/**
	 * Etykieta
	 *
	 * @var string
	 * 
	 */			
	protected $_label = '';
	
	/**
	 * Inicjalizuje dostepne atrybuty elementu html
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function init() {
		parent::init();
		$this->_attributes['for'] = '';
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
	 * Ustawia atrybut for elementu html
	 * 
	 * @access public
	 * @param string Id elementu input
	 * @return void
	 * 
	 */		
	public function setFor($for) {
		if($for !== null) {
			$this->setAttrib('for', $for);
		}
	}
	
	/**
	 * Ustawia wartosc etykiety
	 * 
	 * @access public
	 * @param string Etykieta elementu
	 * @return void
	 * 
	 */		
	public function setLabel($label) {
		if($label !== null) {
			$this->_label = $label;
		}
	}
	
	/**
	 * Zwraca wartosc atrybutu for
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getFor() {
		return $this->getAttrib('for');
	}
	
	/**
	 * Zwraca wartosc etykiety
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function geLabel() {
		return $this->_label;
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
		$htmlString .= "<" . $this->_tag . $this->buildAttributesString() . ">";
		$htmlString .= $this->_label;
		$htmlString .= "</" . $this->_tag . ">\n";
		return $htmlString;
	}		
	
}

?>
