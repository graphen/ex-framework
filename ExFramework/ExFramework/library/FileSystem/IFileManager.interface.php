<?php

/**
 * @interface IFileManager
 *
 * @author Przemyslaw Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IFileManager {
	
	/**
	 * Sprawdza czy plik jest zwyklym plikiem
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */
	public function isFile($filePath);
			
	/**
	 * Kopiuje pliki
	 *
	 * @access public
	 * @param string Sciezka do pliku zrodlowego
	 * @param string Sciezka do pliku docelowego
	 * @param bool Czy nadpisac? //true dla tak
	 * @return void
	 * 
	 */
	public function copy($filePath, $newFilePath, $overwrite=false);
	
	/**
	 * Tworzy nowe pliki
	 *
	 * @access public
	 * @param string Sciezka do tworzonego pliku
	 * @param bool Czy nadpisac jesli istnieje?
	 * @param integer Uprawnienia
	 * @return void
	 * 
	 */
	public function create($filePath, $overwrite=false, $mode=0666);
	
	/**
	 * Kasuje pliki
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */
	public function delete($filePath);
		
	/**
	 * Zwraca zawartosc pliku jako tablice
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return array
	 * 
	 */
	public function readToArray($filePath);

	/**
	 * Zwraca zawartosc pliku jako string
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return string
	 * 
	 */
	public function readToString($filePath);

	/**
	 * Zapisuje string do pliku
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @param string Dane
	 * @param bool Czy dolaczyc na koniec pliku 
	 * @return void
	 * 
	 */
	public function write($filePath, $data, $append=false);
	
	/**
	 * Wyswietla zawartosc pliku
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */
	public function show($filePath);

	/**
	 * Zwraca obiekt reprezentujacy plik
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return object
	 */	
	public function getHandler($filePath='');
	
	/**
	 * Zwraca typ MIME pliku
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @return mixed //Array lub String
	 *
	 */
	public function getMime($filePath);
	
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
