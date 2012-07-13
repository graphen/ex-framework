<?php

/**
 * @class ControllerFactory
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ControllerFactory implements IFactory {
	
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
	 * Tworzy obiekty kontrolerow
	 * 
	 * @access public
	 * @param string Identyfikator klasy kontrolera
	 * @return object
	 * 
	 */		
	public function create($actionControllerClassId) {
		return $this->_iocContainer->create($actionControllerClassId);
	}
	
}

?>
