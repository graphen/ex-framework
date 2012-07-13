<?php

/**
 * @interface IFactory
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IFactory {
	
	/**
	 * Tworzy obiekty 
	 * 
	 * @access public
	 * @param string Identyfikator klasy
	 * @return object
	 * 
	 */		
	public function create($className);
}


?>
