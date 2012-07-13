<?php

/**
 * @class ViewJson
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ViewJson extends ViewPlain implements IView {
	
	/**
	 * Typ MIME dokumentu
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_viewType = 'application/json';
	
	/**
	 * Tablica zmiennych
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_data = array();
	
	/*
	 * Konstruktor
	 * 
	 * @access public
	 * @return void
	 * 
	 */ 			
	public function __construct() {
		//
	}	
	
	/*
	 * Dodaje zmienna o okreslonej etykiecie do tablicy zmiennych dla szablonu
	 * 
	 * @access public
	 * @param string Etykieta zmiennej
	 * @param mixed Wartosc zmiennej
	 * @return void
	 * 
	 */ 		
	public function assign($var, $value) {
		$this->_data[(string)$var] = $value;
	}
	
	/*
	 * Przetwarza zmienne, zwracajac ciag tekstowy
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 		
	public function fetch() {
		foreach($this->_data AS $index=>$value) {
			if(is_object($value)) {
				$this->_data[$index] = $value->fetchHtml();
			}
		}
		$str = '';
		$str = json_encode($this->_data);
		return $str;
	}
	
	/*
	 * Zwraca typ MIME widoku jesli ustawiono
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getMimeType() {
		return $this->_viewType;
	}

	/*
	 * Ustawia typ MIME widoku
	 * 
	 * @access public
	 * @param string Typ MIME dla widoku
	 * @return void
	 * 
	 */ 	
	public function setMimeType($mimeType) {
		return $this->_viewType = (string)$mimeType;
	}
	
	/*
	 * Zwraca informacje tekstowa o pewnych wlasciwosciach obiektu
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function __toString() {
		$str = "";
		$str .= "View Type: " . $this->_viewType . "<br />";
		$str .= "Data: \n<pre>" . print_r($this->_data, true) . "</pre><br />";
		return $str;
	}	
	
}

?>
