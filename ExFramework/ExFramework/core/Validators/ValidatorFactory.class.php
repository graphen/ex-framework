<?php

/**
 * @class ValidatorFactory
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ValidatorFactory implements IFactory {

	/**
	 * Kontener IoC
	 *
	 * @var object
	 */	
	protected $_iocContainer = null;
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Kontener IoC
	 *
	 */	
	public function __construct($iocContainer) {
		$this->_iocContainer = $iocContainer;
	}
	
	/**
	 * Tworzy obiekty weryfikatorow
	 * 
	 * @access public
	 * @param string Identyfikator klasy weryfikatora
	 * @return object
	 * 
	 */		
	public function create($validatorClassId) {
		return $this->_iocContainer->create($validatorClassId);
	}
	
}

?>
