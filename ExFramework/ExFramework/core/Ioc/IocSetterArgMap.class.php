<?php

/**
 * @class IocSetterArgMap
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 */
 
class IocSetterArgMap extends IocArgMap {
	
	/**
	 * 
	 * Zwraca nazwe settera
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function getSetterName() {
		return 'set' . ucfirst($this->_argName);
	}
	
	/**
	 * 
	 * Zwraca nazwe gettera
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function getGetterName() {
		return 'get' . ucfirst($this->_argName);
	}
	
}

?>
