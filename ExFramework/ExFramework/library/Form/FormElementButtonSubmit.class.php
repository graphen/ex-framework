<?php

/**
 * @class FormElementButtonSubmit
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FormElementButtonSubmit extends FormElementButtonAbstract {
	
	/**
	 * Nazwa kontrolki
	 *
	 * @var string
	 * 
	 */		
	protected $_type = 'submit';
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt fabryczny elementow formularza
	 * @return void
	 * 
	 */	
	public function __construct(IFactory $htmlElementFactory) {
		parent::init();
		$this->_htmlElementFactory = $htmlElementFactory;
		$this->setAttrib('type', $this->_type);		
	}
	
}

?>
