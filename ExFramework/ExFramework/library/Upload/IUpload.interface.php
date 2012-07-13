<?php

/**
 * @interface IUpload
 *
 * @author Przemyslaw Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IUpload {
	
	/**
	 * Ustawia tablice dopuszczalnych rozszerzen dla uploadowanych plikow
	 * 
	 * @access public
	 * @param array|string Tablica rozszerzen
	 * @return void
	 * 
	 */	
	public function setAllowedFileExt($ext);
	
	/**
	 * Ustawia mozliwosc uzywania wszystkich typow MIME
	 * 
	 * @access public
	 * @param bool //true jesli wszystkie maja byc uzywane
	 * @return void
	 * 
	 */	
	public function setAllMimeAllowed($allMime);
	
	/**
	 * Ustawia maksymalna wielkosc plikow
	 * 
	 * @access public
	 * @param integer Wielkosc plikow
	 * @return void
	 * 
	 */	
	public function setMaxFileSize($size);
	
	/**
	 * Ustawia maksymalna dlugosci nazw plikow
	 * 
	 * @access public
	 * @param integer Dlugosc nazwy pliku
	 * @return void
	 * 
	 */
	public function setMaxFileNameLength($length);
	
	/**
	 * Ustawia sprawdzanie czy przeslany plik jest obrazkiem
	 * 
	 * @access public
	 * @param bool //true jesli ma byc sprawdzany
	 * @return void
	 * 
	 */	
	public function setCheckImage($check);
	
	/**
	 * Ustawia maksymalna szerokosci obrazkow
	 * 
	 * @access public 
	 * @param integer Szerokosc obrazka
	 * @return void
	 * 
	 */	
	public function setMaxImageWidth($width);
	
	/**
	 * Ustawia maksymalna wysokosc obrazka
	 * 
	 * @access public
	 * @param integer Wysokosc obrazka
	 * @return void
	 * 
	 */	
	public function setMaxImageHeight($height);
	
	/**
	 * Ustawia sciezki do katalogu uploadu
	 * 
	 * @access public
	 * @param string Sciezka do katalogu uploadu 
	 * @return void
	 * 
	 */	
	public function setUploadPath($path);
	
	/**
	 * Ustawia mozliwosc nadpisywania plikow
	 * 
	 * @access public
	 * @param bool //true jesli maja byc nadpisywane
	 * @return void
	 * 
	 */	
	public function setOverwrite($overwrite);
	
	/**
	 * Ustawia kasowanie spacji z nazw plikow
	 * 
	 * @access public
	 * @param bool //true jesli maja byc usuwane
	 * @return void
	 * 
	 */	
	public function setStripSpaces($stripSpaces);
	
	/**
	 * Ustawia sprawdzanie i czyszczenie zawartosci plikow
	 * 
	 * @access public
	 * @param bool //true jesli sprawdzanie i czyszczenie ma byc wlaczone
	 * @return void
	 * 
	 */		
	public function setCleanXss($cleanXss);
	
	/**
	 * Ustawia losowe nazwy dla plikow
	 * 
	 * @access public
	 * @param bool //true jesli nazwy maja byc losowe
	 * @return void
	 * 
	 */	
	public function setRandomName($random);
	
	/**
	 * Ustawia dozwole typy MIME dla obrazkow
	 * 
	 * @access public
	 * @param array Tablica z typami
	 * @return void
	 * 
	 */		
	public function setImageMimeType(Array $images);
	
	/**
	 * Zwraca tablice z bledami
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getErrors();
	
	/**
	 * Zwraca tablice z nazwami, tymczasowymi nazwami, wielkosciami, typami itd dla przetworzonych plikow
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getData();
	
	/**
	 * Pobiera dane z tablicy $_FILES i uruchamia uplodowanie plikow 
	 * 
	 * @access public
	 * @param string Nazwa pola formularza ktorym przesylany jest plik
	 * @return bool|-1
	 * 
	 */	
	public function upload($field);
	
}

?>
