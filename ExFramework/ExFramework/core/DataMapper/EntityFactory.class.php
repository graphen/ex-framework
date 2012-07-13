<?php

/**
 * @class EntityFactory
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class EntityFactory implements IFactory {
	
	/**
	 * Kontener IoC
	 *
	 * @var object
	 */		
	protected $_iocContainer;
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Kontener IoC
	 *
	 */		
	public function __construct(IocContainer $iocContainer) {
		$this->_iocContainer = $iocContainer;
	}
	
	/**
	 * Tworzy obiekty biznesowe identyfikowane podanym identyfikatorem
	 * 
	 * @access public
	 * @param string Id obiektu
	 * @return object
	 * 
	 */			
	public function create($entityClassId) {
		return $this->_iocContainer->create($entityClassId);
	}
	
}

?>
