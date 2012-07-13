<?php

/**
 * @interface IViewResolver
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IViewResolver {
	
	/**
	 * Zwraca obiekt widoku na podstawie danych z zadania
	 * 
	 * @access public
	 * @param string Nazwa szablonu
	 * @param string Nazwa layoutu
	 * @param bool Czy uzyc layoutu
	 * @return object
	 * 
	 */			
	public function resolve($templateName=null, $layoutName=null, $useLayout=false);
	
}

?>
