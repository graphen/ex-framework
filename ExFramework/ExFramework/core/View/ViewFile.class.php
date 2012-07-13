<?php

/**
 * @class ViewFile
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ViewFile implements IView {
	
	/**
	 * Typ MIME wysylanego pliku
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_viewType = 'application/force-download';
	
	/**
	 * Tablica nazw plikow
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_data = array();
	
	/**
	 * Obiekt obslugi plikow
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_fileManager = null;	
	
	/*
	 * Konstruktor
	 * 
	 * @access public
	 * @return void
	 * 
	 */ 			
	public function __construct(FileManager $fileManager) {
		$this->_fileManager = $fileManager;
	}	
	
	/*
	 * Dodaje zmienna o okreslonej etykiecie do tablicy zmiennych dla szablonu
	 * 
	 * @access public
	 * @param string Etykieta pliku
	 * @param mixed Sciezka do pliku
	 * @return void
	 * 
	 */ 		
	public function assign($var, $value) {
		if(!is_string($value)) {
			throw new ViewException('Podana wartosc nie jest nazwa pliku');
		}
		if(!is_file($value)) {
			throw new ViewException('Podana nazwa nie jest nazwa pliku');
		}
		if(!file_exists($value)) {
			throw new ViewException('Plik nie istnieje w podanej sciezce');
		}
		$this->_data[(string)$var] = $value;
		$this->_viewType = $this->_fileManager->getMime($value);
	}
	
	/*
	 * Zwraca sciezke do pliku
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 		
	public function fetch() {
		reset($this->_data);
		return current($this->_data);
	}
	
	/*
	 * Zwraca typ MIME pliku
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getMimeType() {
		return $this->_viewType;
	}

	/*
	 * Ustawia typ MIME pliku
	 * 
	 * @access public
	 * @param string Typ MIME dla pliku
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
