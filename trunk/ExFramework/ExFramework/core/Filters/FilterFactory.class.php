<?php

/**
 * @class FilterFactory
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FilterFactory implements IFactory {

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
	 * Tworzy obiekty filtrow
	 * 
	 * @access public
	 * @param string Nazwa filtra
	 * @return object
	 * 
	 */
	public function create($filterClassId) {
		return $this->_iocContainer->create($filterClassId);
	}
	
}

?>
