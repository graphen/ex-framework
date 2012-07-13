<?php

/**
 * @interface IExif
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 */
interface IExif {
		
	/**
	 * Zwraca typ obrazu lub rozszerzenie na podstawie typu
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @param bool Wlaczenie/wylaczenie pobierania rozszerzenia OPTIONAL
	 * @return mixed
	 * 
	 */		
	public static function getImageType($path, $extension=true);
	
	/**
	 * Zwraca tablice informacji o obrazie
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @return array
	 * 
	 */		
	public static function getImageInfo($path);
	
	/**
	 * Zwraca tablice podstawowych informacji o obrazie
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @return array
	 * 
	 */		
	public static function getBasicImageInfo($path);
	
	/**
	 * Zwraca ciag znakow, ktory mozna wyslac do przegladarki ustawiajac odpowiedni typ MIME poprzez funkcje header
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @param ref Szerokosc zdjecia OPTIONAL
	 * @param ref Wysokosc zdjecia OPTIONAL
	 * @param ref Typ zdjecia OPTIONAL
	 * @return string
	 * 
	 */		
	public static function getThumbnail($path, &$width=null, &$height=null, &$type=null);
	
}

?>
