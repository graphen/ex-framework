<?php

/**
 * @class ParserXml
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ParserXml extends ParserAbstract implements IParser {
	
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
	 * Zwracanie tablicy z przetworzonego pliku XML
	 *
	 * @access protected
	 * @param string Nazwa pliku
	 * @return array
	 * 
	 */	
	protected function xmlToArray($fileName) {
		$fileContent = file_get_contents($fileName);
		$sXMLIterator = new SimpleXMLIterator($fileContent);
		$arrTmp = array();
		$arrTmp = $this->sXMLIteratorToArray($sXMLIterator); 
		return $arrTmp;
	}

	/**
	 * Konwertuje XML do tablicy
	 *
	 * @access protected
	 * @param SimpleXMLIterator Iterator
	 * @return array
	 * 
	 */
	protected function sXMLIteratorToArray($XMLIter) {
		$data = array();	
		for($XMLIter->rewind(); $XMLIter->valid(); $XMLIter->next()) {
			if(!array_key_exists($XMLIter->key(), $data)) {
				$data[$XMLIter->key()] = array();
			}
			if($XMLIter->hasChildren()) {
				$data[$XMLIter->key()] = $this->sXMLIteratorToArray($XMLIter->current());
			}
			else {
				$data[$XMLIter->key()] = strval($XMLIter->current());
			}
		} 
		return $data;
	}	
	
	/**
	 * Dodanie pliku i umieszczenie jego zawartosci w tablicy
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 */
	public function addFile($fileName) {
		if(!strstr(strtolower($fileName), '.xml')) {
			return;
		}
		if(in_array($fileName, $this->_files)) {
			return;
		}
		if (!file_exists($fileName) || !is_readable($fileName)) {
			throw new ParserException('Plik: ' . $fileName . ' nie istnieje lub nie mozna z niego czytac');
		}
		$arrTmp = array();
		$arrTmp = $this->XMLToArray($fileName);
		$this->_data = array_merge($this->_data, $arrTmp);
		$this->_files[] = $fileName;
	}
	
}

?>
