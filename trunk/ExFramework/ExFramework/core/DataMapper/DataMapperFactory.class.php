<?php

/**
 * @class DataMapperFactory
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class DataMapperFactory implements IFactory {
	
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
	 * Tworzy obiekty mapperow identyfikowane podanym identyfikatorem
	 * 
	 * @access public
	 * @param string Id obiektu
	 * @return object
	 * 
	 */				
	public function create($mapperClassId) {
		return $this->_iocContainer->create($mapperClassId);
	}
	
}

?>
