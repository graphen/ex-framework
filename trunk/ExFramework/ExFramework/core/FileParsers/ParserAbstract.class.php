<?php

/**
 * @class ParserAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class ParserAbstract implements IParser {

	/**
	 * Tablica z danymi
	 *
	 * @var array
	 */
	protected $_data = array();

	/**
	 * Tablica z nazwami dodanych plikow
	 *
	 * @var array
	 */
	protected $_files = array();

	/**
	 * 
	 * Konstruktor 
	 * 
	 * @access public
	 * 
	 */
	public function __construct() {
		//
	}

	/**
	 * Pobranie wartosci z tablicy dla podanego indeksu
	 *
	 * @access public
	 * @param string Zadany index
	 * @return mixed
	 * 
	 */
	public function getData($dataIndex) {
		$keys = explode('.', $dataIndex);
		$arrTemp = & $this->_data;
		do {
			$key = array_shift($keys);
			if(isset($arrTemp[$key])) {
				if(is_array($arrTemp[$key]) && !empty($keys)) {
					$arrTemp = & $arrTemp[$key];
				}
				else {
					return $arrTemp[$key];
				}
			}
			else {
				return null;
			}
		} while(!empty($keys));
		return null;
	}

	/**
	 * Pobranie grupy danych
	 *
	 * @access public
	 * @param string Grupa danych
	 * @return array or null
	 * 
	 */	
	public function getDataGroup($group) {
		if(isset($this->_data[$group])) {
			return $this->_data[$group];
		}
		return null;
	}

	/**
	 * Usuniecie grupy danych
	 *
	 * @access public
	 * @param string Grupa danych, ktora ma zostac usunieta
	 * @return void
	 * 
	 */		
	public function unsetDataGroup($group) {
		if(isset($this->_data[$group])) {
			unset($this->_data[$group]);
		}	
	}

	/**
	 * Pobranie tabeli z danymi
	 *
	 * @access public
	 * @return array
	 */	
	public function getAll() {
		return $this->_data;
	}

	/**
	 * Zrzut tabeli z danymi
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function __toString() {
		$out = "";
		$out .= "<pre>\n";
		$out .= print_r($this->getAll(), true);
		$out .= "</pre>\n";
		return $out;
	}

}

?>
