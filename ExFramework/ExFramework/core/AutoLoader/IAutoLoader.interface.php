<?php

/**
 * @interface IAutoLoader
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IAutoLoader {
	
	/**
	 * Laduje automatycznie klase lub interfejs o danej nazwie
	 * 
	 * @access public
	 * @param string Nazwa klasy lub interfejsu
	 * @return void
	 * 
	 */		
	public function autoload($className);
		
}

?>
