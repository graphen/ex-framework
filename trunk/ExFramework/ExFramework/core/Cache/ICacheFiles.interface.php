<?php

/**
 * @interface ICacheFiles
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface ICacheFiles extends ICache {

	/**
	 * Metoda ustawia sciezke do katalogu cache
	 * 
	 * @access public
	 * @param string Sciezka do katalogu cache
	 * @return void
	 *  
	 */		
	public function setCacheDir($cacheDir);
	
	/**
	 * Metoda zwraca sciezke do katalogu cache
	 * 
	 * @access public
	 * @return string
	 *  
	 */		
	public function getCacheDir();
	
	/**
	 * Metoda ustawia rozszezrzenie plikow cache
	 * 
	 * @access public
	 * @param string Rozszezrzenie pliku
	 * @return void
	 *  
	 */		
	public function setFileExt($ext='.cache');
	
	/**
	 * Metoda zwraca rozszerzenie pliku cache
	 * 
	 * @access public
	 * @return string
	 *  
	 */		
	public function getFileExt();
	
}

?>
