<?php

/**
 * @class FormElementInputImage
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FormElementInputImage extends FormElementInputAbstract {
	
	/**
	 * Nazwa kontrolki
	 *
	 * @var string
	 * 
	 */		
	protected $_type = 'image';
	
	/**
	 * Inicjalizuje dodatkowe atrybuty dla elementu html
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function init() {
		parent::init();
		$this->_attributes['src'] = '';
		$this->_attributes['alt'] = '';
		$this->_attributes['width'] = '';
		$this->_attributes['height'] = '';
		$this->_attributes['border'] = '';
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
		$this->setAttrib('type', $this->_type);
	}
	
	/**
	 * Ustawia atrybut src elementu html
	 * 
	 * @access public
	 * @param string Sciezka do obrazka
	 * @return void
	 * 
	 */			
	public function setSrc($src) {
		$this->setAttrib('src', $src);
	}
	
	/**
	 * Ustawia atrybut alt elementu html
	 * 
	 * @access public
	 * @param string Opis obrazka
	 * @return void
	 * 
	 */		
	public function setAlt($alt) {
		$this->setAttrib('alt', $alt);
	}
	
	/**
	 * Zwraca wartosc atrybutu src elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getSrc() {
		return $this->getAttrib('src');
	}
	
	/**
	 * Zwraca wartosc atrybutu alt elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getAlt() {
		return $this->getAttrib('alt');
	}
	
}

?>
