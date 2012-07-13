<?php

/**
 * @interface IDirManager
 *
 * @author Przemyslaw Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IDirManager {
	
	/**
	 * Sprawdza czy plik to katalog
	 *
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return void
	 * 
	 */
	public function isDir($dirPath);

	/**
	 * Kopiuje rekurencyjnie katalogi
	 * 
	 * @access public
	 * @param string Sciezka do katalogu zrodlowego
	 * @param string Sciezka do katalogu docelowego
	 * @param bool Czy nadpisac katalog? //true jesli tak 
	 * @return void
	 * 
	 */	
	public function copy($dirSrcPath, $dirDestPath, $overwrite=false);

	/**
	 * Tworzy katalog lub nic nie robi jesli istnieje
	 *
	 * @access public
	 * @param string Sciezka do katalogu
	 * @param integer Uprawnienia
	 * @return void
	 * 
	 */	
	public function mkdir($dirPath, $dirChmod=0777, $recursive=false);
	
	/**
	 * Usuwa pusty katalog
	 *
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return void
	 * 
	 */	
	public function rmdir($dirPath);

	/**
	 * Usuwa rekurencyjnie katalogi
	 * 
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return void
	 * 
	 */	
	public function rmdirRecursive($dirSrcPath);
	
	/**
	 * Czyta katalog i zwraca zawartosc w formie tablicy
	 *
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return array
	 * 
	 */	
	public function read($dirPath);
	
	/**
	 * Czyta katalog rekursywnie i zwraca zawartosc w formie tablicy
	 *
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return array
	 * 
	 */	
	public function readRecursive($dirPath);
	
	/**
	 * Przechodzi do wskazanego katalogu
	 *
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return void
	 * 
	 */	
	public function chdir($dirPath);
	
	/**
	 * Zwraca nazwe biezacego katalogu
	 *
	 * @access public
	 * @return string
	 * 
	 */	
	public function cwd();
	
	/**
	 * Przenosi i zmienia nazwy plikow
	 *
	 * @access public
	 * @param string Sciezka do pliku/katalogu zrodlowego
	 * @param string Sciezka do pliku/katalogu docelowego
	 * @param bool Czy nadpisac? //true dla tak
	 * @return void
	 * 
	 */
	public function rename($filePath, $newFilePath, $overwrite=false);
	
	/**
	 * Przenosi zmienia nazwy plikow i katalogow. Alias do rename
	 *
	 * @access public
	 * @param string Sciezka do pliku/katalogu zrodlowego
	 * @param string Sciezka do pliku/katalogu docelowego
	 * @param bool Czy nadpisac? //true dla tak
	 * @return void
	 * 
	 */
	public function move($filePath, $newFilePath, $overwrite=false);
	
	/**
	 * Zmienia uprawnienia do plikow/katalogow 
	 * Tylko pliki wlasciciela
	 * 
	 * @access public
	 * @param string Sciezka do pliku/katalogu
	 * @param integer Uprawnienia osemkowo
	 * @return void
	 * 
	 */
	public function chmod($filePath, $newPerms);
	
	/**
	 * Zwraca wlasciwosci pliku/katalogu
	 * 
	 * @access public
	 * @param string Sciezka do pliku/katalogu
	 * @return array
	 * 
	 */
	public function stats($filePath);
	
}

?>
