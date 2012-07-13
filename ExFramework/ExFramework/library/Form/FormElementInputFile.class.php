<?php

/**
 * @class FormElementInputFile
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FormElementInputFile extends FormElementInputAbstract {
	
	/**
	 * Nazwa kontrolki
	 *
	 * @var string
	 * 
	 */		
	protected $_type = 'file';
	
	/**
	 * Inicjalizuje dostepne atrybuty elementu html
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function init() {
		parent::init();
		$this->_attributes['accept'] = '';
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
	 * Ustawia atrybut accept elementu html
	 * 
	 * @access public
	 * @param string 
	 * @return void
	 * 
	 */			
	public function setAccept($accept) {
		$this->setAttrib('accept', $accept);
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
	
}

?>
