<?php

/**
 * @class ParserPhp
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ParserPhp extends ParserAbstract implements IParser {

	/**
	 * Nazwa dla tablicy z danymi
	 *
	 * @var string
	 */	
	protected $_arrayName = 'data';
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * 
	 */
	public function __construct() {
		//
	}
	
	/**
	 * Pozwala okreslic nazwe tablicy w pliku php
	 *
	 * @access public
	 * @param string Nazwa tablicy
	 * @return void
	 * 
	 */	
	public function setArrayName($newName) {
		$this->_arrayName = $newName;
	}
	
	/**
	 * Dodanie pliku i umieszczenie jego zawartosci w tablicy
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 */	
	public function addFile($fileName) {
		if(!strstr(strtolower($fileName), '.php')) {
			return;
		}
		if(in_array($fileName, $this->_files)) {
			return;
		}
		if(!file_exists($fileName) || !is_readable($fileName)) {
			throw new ParserException('Plik: ' . $fileName . ' nie istnieje lub nie mozna z niego czytac');
		}
		$arrName = $this->_arrayName;
		require($fileName);
		if(isset($$arrName) && is_array($$arrName)) {
			//$this->_data = array_merge($this->_data, $$arrName); //2012.01.09
			$this->_data = array_merge_recursive($this->_data, $$arrName); //2012.01.09
			unset($$arrName);
			return;
		}
		$this->_files[] = $fileName;
	}

}

?>
