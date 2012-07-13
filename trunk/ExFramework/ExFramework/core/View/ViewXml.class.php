<?php

/**
 * @class ViewXml
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ViewXml extends ViewPlain implements IView {
	
	/**
	 * Typ MIME dokumentu
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_viewType = 'application/xml';
	
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
				$this->_data[$index] = null;
			}
		}
		$xml = $this->toXml($this->_data);
		return $xml;
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
	
	/**
	 * Konwertuje tablice PHP do XML
	 * Kod pochodzi czesciowo z http://snipplr.com/view/3491/
	 *
	 * @access protected
	 * @param array $data - Tablica danych
	 * @param string $rootNodeName - Nazwa dla wezla root 
	 * @param SimpleXMLElement $xml - parametr tylko dla wywolan rekursywnych
	 * @return string
	 * 
	 */
	protected function toXml($data, $rootNodeName='data', $xml=null) {
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if(ini_get('zend.ze1_compatibility_mode') == 1) {
			ini_set ('zend.ze1_compatibility_mode', 0);
		}
		if($xml == null) {
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
		}
		foreach($data as $key => $value) {
			if(is_numeric($key)) {
				$key = "unknownNode_" . (string)$key;//bez liczb jako znaczniki
			}
			//zamiana wszystkiego co nie jest znakiem alfanumerycznym na ciag pusty
			$key = preg_replace('/[^a-z0-9]/i', '', $key);
			if (is_array($value)) { //jesli wartosc jest tablica, wywolanie rekursywne
				$node = $xml->addChild($key);
				$this->toXml($value, $rootNodeName, $node);
			}
			else { //w innym wypadku dodanie elementu
                //$value = htmlentities($value);
				$xml->addChild($key,$value);
			}
		}
		return $xml->asXML();
		
	}
	
}

?>
