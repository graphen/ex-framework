<?php

/**
 * @interface IFtp
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 */
interface IFtp {
	
	/**
	 * Ustawia nazwe uzytkownia
	 * 
	 * @access public
	 * @param string Nazwa uzytkownika, domyslnie ''
	 * @return void
	 * 
	 */	
	public function setUser($user='');
	
	/**
	 * Ustawia haslo
	 * 
	 * @access public
	 * @param string Haslo, domyslnie ''
	 * @return void
	 * 
	 */	
	public function setPassword($password='');
	
	/**
	 * Ustawia nazwe hosta
	 * 
	 * @access public
	 * @param string Nazwa hosta, domyslnie ''
	 * @return void
	 * 
	 */		
	public function setHost($host='');
	
	/**
	 * Ustawia port
	 * 
	 * @access public
	 * @param int Port, domyslnie 21
	 * @return void
	 * 
	 */		
	public function setPort($port=21);
	
	/**
	 * Ustawia czas po ktorym nastapi rozlaczenie 
	 * 
	 * @access public
	 * @param int Czas do rozlaczenia, domyslnie 120s
	 * @return void
	 * 
	 */		
	public function setTimeout($timeout=120);
	
	/**
	 * Ustawia sciezke do katalogu poczatkowego
	 * 
	 * @access public
	 * @param string Sciezka do katalogu poczatkowego, domyslnie ''
	 * @return void
	 * 
	 */		
	public function setDirPath($dirPath='');
	
	/**
	 * Wlacza/wylacza tryb pasywny
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie trybu pasywnego, domyslnie true
	 * @return void
	 * 
	 */		
	public function setPassive($passive=true);

	/**
	 * Wlacza/wylacza przesylanie poprzez ssl
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie przesylania przez ssl, domyslnie false
	 * @return void
	 * 
	 */		
	public function setSsl($ssl=false);

	/**
	 * Wlacza/wylacza preallokacje na serwerze
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie preallokacji, domyslnie false
	 * @return void
	 * 
	 */	
	public function setPreallocation($prealloc=false);
	
	/**
	 * Nawiazuje polaczenie, loguje uzytkownika do serwera, ustawia tryb i przechodzi do okreslonego katalogu jesli go podano
	 * 
	 * @access public
	 * @return void
	 * 
	 */
	public function connect();
	
	/**
	 * Zwraca rodzaj systemu operacyjnego na ktorym dziala serwer ftp
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function systype();
	
	/**
	 * Sprawdza czy nawiazano poaczenie poprzez obecnosc zmiennej zasobu
	 * 
	 * @access public
	 * @return bool
	 * 
	 */
	public function isConnected();
	
	/**
	 * Przechodzi do podanego katalogu
	 * 
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return void
	 * 
	 */
	public function chdir($dirPath);
	
	/**
	 * Przechodzi do katalogu o jeden poziom wyzej
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function cdup();
	
	/**
	 * Zwraca sciezke do biezacego katalogu
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function pwd();
	
	/**
	 * Zamyka polaczenie z serwerem ftp
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function close();
	
	/**
	 * Zwraca liste plikow w podanym katalogu w formie tablicy
	 * 
	 * @access public
	 * @param string Sciezka do zadanego katalogu
	 * @return array
	 * 
	 */	
	public function arrayList($dirPath='');
	
	/**
	 * Zwraca liste plikow w podanym katalogu w formie tablicy z surowymi danymi w formie tekstowej
	 * 
	 * @access public
	 * @param string Sciezka do zadanego katalogu
	 * @param bool Czy pobrac cale drzewo od podanej sciezki
	 * @return array
	 * 
	 */
	public function rawList($dirPath='', $recursive=false);
	
	/**
	 * Tworzy katalog i moze nadac uprawnienia
	 * 
	 * @access public
	 * @param string Nazwa lub sciezka tworzonego katalogu
	 * @param int Uprawnienia
	 * @return void
	 * 
	 */
	public function mkdir($dir, $perms=null);
	
	/**
	 * Usuwa podany katalog
	 * 
	 * @access public
	 * @param string Sciezka do usuwanego katalogu
	 * @return void
	 * 
	 */
	public function rmdir($dir);
	
	/**
	 * Usuwa podany plik
	 * 
	 * @access public
	 * @param string Sciezka do usuwanego pliku
	 * @return void
	 * 
	 */
	public function delete($file);

	/**
	 * Zmienia nazwe, przenosci pliki i katalogi
	 * 
	 * @access public
	 * @param string Sciezka zrodlowa do pzrenoszonego lub zmienianego katalogu
	 * @param string Sciezka docelowa dla pliku
	 * @return void
	 * 
	 */
	public function rename($src, $dest);

	/**
	 * Jest to alias dla rename
	 * 
	 * @access public
	 * @param string Sciezka zrodlowa do pzrenoszonego lub zmienianego katalogu
	 * @param string Sciezka docelowa dla pliku
	 * @return void
	 * 
	 */	
	public function move($src, $dest);
	
	/**
	 * Nadaje uprawnienia
	 * 
	 * @access public
	 * @param string Sciezka do pliku ktoremu nadaje uprawnienia
	 * @param integer Uprawnienia w formie osemkowej //np 0777
	 * @return void
	 * 
	 */
	public function chmod($file, $perms);

	/**
	 * Zwraca czas ostatniej modyfikacji
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @return integer //-1 jesli nie ma obslugi 
	 * 
	 */
	public function mdtm($file);

	/**
	 * Zwraca wielkosc pliku w bajtach
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @return integer
	 * 
	 */		
	public function size($file);

	/**
	 * Allokuje miejsce dla pliku na serwerze //najczesciej niepotrzebne
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */		
	public function alloc($filePath);

	/**
	 * Wysyla plik na zdalny serwer
	 * 
	 * @access public
	 * @param string Sciezka do pliku lokalnego
	 * @param string Sciezka do pliku zdalnego
	 * @param string Tryb przesylania // binary|ascii
	 * @param integer Uprawnienia osemkowo
	 * @return void
	 * 
	 */		
	public function upload($localFile, $remoteFile, $mode='binary', $perms=null);
	
	/**
	 * Pobiera plik ze zdalnego serwera
	 * 
	 * @access public
	 * @param string Sciezka do pliku zdalnego
	 * @param string Sciezka do pliku lokalnego
	 * @param string Tryb przesylania // binary|ascii
	 * @return void
	 * 
	 */	
	public function download($remoteFile, $localFile, $mode='binary');

	/**
	 * Zada wykonania polecenia przez serwer ftp
	 * UWAGA! To rozszerzenie jest zadko obslugiwane
	 * 
	 * @access public
	 * @param string Polecenie
	 * @return void
	 * 
	 */	
	public function exec($command);

	/**
	 * Wykonuje polecenie na serwerze, zwraca wynik, nie sprawdza czy sie wykonalo poprawnie
	 * 
	 * @access public
	 * @param string Polecenie
	 * @return mixed
	 * 
	 */
	public function raw($command);

	/**
	 * Wykonuje polecenie FEAT na serwerze
	 * 
	 * @access public
	 * @return array
	 * 
	 */
	public function feat();

	/**
	 * Usuwa rekursywnie katalogi na serwerze ftp
	 * 
	 * @access public
	 * @param string Sciezka do zdalnego katalogu
	 * @return void
	 * 
	 */	
	public function rmdirRecursive($dirPath);
	
	/**
	 * Sprawdza czy istnieje zdalny katalog
	 * 
	 * @access public
	 * @param string Sciezka do zdalnego katalogu
	 * @return bool
	 * 
	 */	
	public function dirExists($dirPath);
	
	/**
	 * Tworzenie drzewa katalogow
	 * 
	 * @access public
	 * @param string Sciezka do zdalnego katalogu
	 * @param int Uprawnienia
	 * @return bool
	 * 
	 */		
	public function mkdirRecursive($dir, $perms=null);
	
	/**
	 * Tworzy kopie drzewa katalogow na sewerze
	 * 
	 * @access public
	 * @param string Sciezka do localnego katalogu 
	 * @param string Sciezka do zdalnego katalogu
	 * @return void
	 * 
	 */	
	public function uploadRecursive($localDir, $remoteDir);
	
}

?>
