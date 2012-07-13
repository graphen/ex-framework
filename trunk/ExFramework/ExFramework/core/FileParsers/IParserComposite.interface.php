<?php

/**
 * @interface IParserComposite
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IParserComposite extends IParser {

	/**
	 * 
	 * Dodaje parsery do tablicy
	 * 
	 * @access public
	 * @param object Parser plikow
	 * @param string Id
	 * @return void
	 * 
	 */	
	public function addDataParser(IParser $parser, $id);
	
	/**
	 * Zwraca obiekt parsera
	 *
	 * @access public
	 * @param string Identyfikator parsera
	 * @return object
	 * 
	 */	
	public function getParser($id);	

}

?>
