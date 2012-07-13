<?php

/**
 * @class ViewFactory
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ViewFactory implements IFactory {

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
	 * Tworzy obiekty widokow
	 * 
	 * @access public
	 * @param string Nazwa klasy widoku
	 * @return object
	 * 
	 */		
	public function create($viewClassId) {
		return $this->_iocContainer->create($viewClassId);
	}
	
}

?>
