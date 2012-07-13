<?php

/**
 * @class AuthAdapterFactory
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class AuthAdapterFactory implements IFactory {
	
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
	 * Tworzy obiekty adapterow uwierzytelniania
	 * 
	 * @access public
	 * @param string Identyfikator klasy adaptera uwierzytelniania
	 * @return object
	 * 
	 */		
	public function create($authAdapterClassId) {
		return $this->_iocContainer->create($authAdapterClassId);
	}
	
}

?>
