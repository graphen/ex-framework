<?php

/**
 * @class ParserIni
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ParserIni extends ParserAbstract implements IParser {

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
	 * Dodanie pliku i umieszczenie jego zawartosci w tablicy
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 */
	public function addFile($fileName) {
		if(!strstr(strtolower($fileName), '.ini')) {
			return;
		}
		if(in_array($fileName, $this->_files)) {
			return;
		}
		if(!file_exists($fileName) || !is_readable($fileName)) {
			throw new ParserException('Plik: ' . $fileName . ' nie istnieje lub nie mozna z niego czytac');
		}
		$arrTmp = array();
		if(!$arrTmp = parse_ini_file($fileName, true)) {
			throw new ParserException('Plik: ' . $fileName . ' nie istnieje lub nie mozna z niego czytac');			
		}
		$this->_data = array_merge($this->_data, $arrTmp);
		$this->_files[] = $fileName;
	}

}

?>
