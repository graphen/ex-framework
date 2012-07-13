<?php

/**
 * @class HtmlElementFactory
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class HtmlElementFactory implements IFactory {

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
	 * Tworzy obiekty html
	 * 
	 * @access public
	 * @param string Id klasy
	 * @return object
	 * 
	 */
	public function create($htmlElementClassId) {
		return $this->_iocContainer->create($htmlElementClassId);
	}
	
}

?>
