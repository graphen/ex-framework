<?php

/**
 * @class FormatterAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class FormatterAbstract {
	
	/**
	 * Tworzy i zwraca wartosc ciag zdefiniowanych atrybutow dla danego elementu html
	 * 
	 * @access protected
	 * @param array Tablica atrybutow zestawy: nazwa=wartosc
	 * @return string
	 * 
	 */	
	protected function buildAttributesString($attributes) {
		if($attributes === null) {
			return '';
		}
		$attributesString  = '';
		foreach($attributes AS $index=>$value) {
			if($value != '') {
				$attributesString .= ' ' . htmlspecialchars($index) . '="' . htmlspecialchars($value) . '"';
			}
		}
		return $attributesString;
	}		
	
	/**
	 * Tworzy i zwraca ciag html elementu
	 * Metoda abstrakcyjna
	 * 
	 * @access protected
	 * @param object Element formularza (kontrolka)
	 * @return string
	 * 
	 */		
	abstract public function fetchHtml();
	
}

?>
