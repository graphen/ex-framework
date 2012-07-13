<?php

/**
 * @class DbResultFactory
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class DbResultFactory implements IFactory {

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
	public function __construct(IocContainer $iocContainer) {
		$this->_iocContainer = $iocContainer;
	}
	
	/**
	 * Tworzy obiekty z wynikami zapytan
	 * 
	 * @access public
	 * @param string Nazwa kontrolera
	 * @return object
	 * 
	 */		
	public function create($dbResultClassId) {
		return $this->_iocContainer->create($dbResultClassId);
	}
	
}

?>
