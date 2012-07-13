<?php

/**
 * @class FormElementInputReset
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FormElementInputReset extends FormElementInputAbstract {
	
	/**
	 * Nazwa kontrolki
	 *
	 * @var string
	 * 
	 */		
	protected $_type = 'reset';
	
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
