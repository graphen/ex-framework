<?php

/**
 * @class FormElementInputHidden
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FormElementInputHidden extends FormElementInputAbstract {
	
	/**
	 * Nazwa kontrolki
	 *
	 * @var string
	 * 
	 */		
	protected $_type = 'hidden';
	
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
