<?php

/**
 * @class DirManager
 *
 * @author Przemyslaw Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class DirManager extends DirAndFileManager implements IDirManager {

	/**
	 * Konstruktor
	 * 
	 * @access public
	 * 
	 */	
	public function __construct() {
		//
	}

	/**
	 * Sprawdza czy plik to katalog
	 *
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return void
	 * 
	 */
	public function isDir($dirPath) {
		if(!file_exists($dirPath)) {
			throw new DirAndFileManagerException('Katalog: ' . $dirPath . ' nie istnieje');
		}
		if(is_dir($dirPath)) {
			return true;
		}
		else {
			return false;
		}
	}

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
	public function copy($dirSrcPath, $dirDestPath, $overwrite=false) {
		if(!$this->isDir($dirSrcPath)) {
			throw new DirAndFileManagerException('Sciezka: ' . $dirSrcPath . ' nie prowadzi do katalogu');
		}	
		if(file_exists($dirDestPath) && ($overwrite === false)) { //jesli docelowy istnieje i niemazna nadpisac to blad
			throw new DirAndFileManagerException('Katalog docelowy: ' . $dirDestPath . ' istnieje');
		}
		$this->mkdir($dirDestPath); //tworze katalog docelowy
		$directoryObject = dir($dirSrcPath); //czytam katalog zrodlowy
		while(($srcEntryName = $directoryObject->read()) != false) { //dopoki sa w nim pliki
			if($srcEntryName != '.' && $srcEntryName != '..') { //jelsli nie sa to '.' i '..'
				$src = $dirSrcPath . '/' . $srcEntryName; // to sciezka do pliku/katalogu zrodlowego
				$dst = $dirDestPath . '/' . $srcEntryName; //to sciezka do pliku/katalogu/docelowego
				if(is_dir($src)) { //jesli jest nim katalog
					$this->copy($src, $dst, $overwrite); //rekurencyjnie wywoluje ta funkcje
				}
				else {
					if(!is_file($src)) {
						throw new DirAndFileManagerException('Sciezka: ' . $src . ' nie prowadzi do pliku');
					}
					if(file_exists($dst) && ($overwrite == false)) {
						throw new DirAndFileManagerException('Plik docelowy: ' . $dst . ' istnieje');
					}
					if(!copy($src, $dst)) {
						throw new DirAndFileManagerException('Nie mozna skopiowac pliku: ' . $src);
					}					
				}
			}
		}
		$directoryObject->close();
	}

	/**
	 * Tworzy katalog lub nic nie robi jesli istnieje
	 *
	 * @access public
	 * @param string Sciezka do katalogu
	 * @param integer Uprawnienia
	 * @return void
	 * 
	 */	
	public function mkdir($dirPath, $dirChmod=0777, $recursive=false) {
		if(file_exists($dirPath) && is_dir($dirPath)) {
			return;
		}
		if(!mkdir($dirPath, $dirChmod, $recursive)) {
			throw new DirAndFileManagerException('Nie mozna utworzyc katalogu: ' . $dirPath);
		}
	}
	
	/**
	 * Usuwa pusty katalog
	 *
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return void
	 * 
	 */	
	public function rmdir($dirPath) {
		if(!$this->isDir($dirPath)) {
			throw new DirAndFileManagerException('Sciezka: ' . $dirPath . ' nie prowadzi do katalogu');
		}
		if(!rmdir($dirPath)) {
			throw new DirAndFileManagerException('nie mozna usunac katalogu: ' . $dirPath);
		}
	}

	/**
	 * Usuwa rekurencyjnie katalogi
	 * 
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return void
	 * 
	 */	
	public function rmdirRecursive($dirSrcPath) {
		if(!$this->isDir($dirSrcPath)) {
			throw new DirAndFileManagerException('Sciezka: ' . $dirSrcPath . ' nie prowadzi do katalogu');
		}
		$directoryObject = dir($dirSrcPath); 
		while(($srcEntryName = $directoryObject->read()) != false) {
			if($srcEntryName != '.' && $srcEntryName != '..') { 
				$src = $dirSrcPath . '/' . $srcEntryName;
				if(is_dir($src)) {
					$this->rmdirRecursive($src);
				}
				else {

					if(!is_file($src)) {
						throw new DirAndFileManagerException('Sciezka: ' . $src . ' nie prowadzi do pliku');
					}
					if(strstr("win", PHP_OS)) {
						str_replace('/', '\\', $src);
						exec("del " . $src);
						if(file_exists($src)) {
							throw new DirAndFileManagerException('Nie mozna skasowac pliku: ' . $src);
						}
					}
					else {
						if(!unlink($src)) {
							throw new DirAndFileManagerException('Nie mozna skasowac pliku: ' . $src);
						}
					}
				}
			}
		}
		$this->rmdir($dirSrcPath);
		$directoryObject->close();
	}
	
	/**
	 * Czyta katalog i zwraca zawartosc w formie tablicy
	 *
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return array
	 * 
	 */	
	public function read($dirPath) {
		if(!$this->isDir($dirPath)) {
			throw new DirAndFileManagerException('Sciezka: ' . $dirPath . ' nie prowadzi do katalogu');
		}
		$directoryContent = array();
		$directoryObject = dir($dirPath);
		while(($entryName = $directoryObject->read()) != false) {
			if($entryName != '.' && $entryName != '..') {
				$directoryContent[] = $entryName;
			}
		}
		sort($directoryContent);
		$directoryObject->close();
		return $directoryContent;
	}
	
	/**
	 * Czyta katalog rekursywnie i zwraca zawartosc w formie tablicy
	 *
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return array
	 * 
	 */	
	public function readRecursive($dirPath) {
		if(!$this->isDir($dirPath)) {
			throw new DirAndFileManagerException('Sciezka: ' . $dirPath . ' nie prowadzi do katalogu');
		}
		$directoryContent = array();
		$directoryObject = dir($dirPath);
		while(($entryName = $directoryObject->read()) != false) {
			if($entryName != '.' && $entryName != '..') {
				$entryName = $dirPath . '/' . $entryName;
				if(is_dir($entryName)) {
					$directoryContent[] = $entryName;
					$directoryContent = array_merge($directoryContent, $this->readRecursive($entryName));
				}
				else {
					$directoryContent[] = $entryName;
				}
			}
		}
		$directoryObject->close();
		return $directoryContent;
	}
	
	/**
	 * Przechodzi do wskazanego katalogu
	 *
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return void
	 * 
	 */	
	public function chdir($dirPath) {
		if(!$this->isDir($dirPath)) {
			throw new DirAndFileManagerException('Sciezka: ' . $dirPath . ' nie prowadzi do katalogu');
		}
		if(!chdir($dirPath)) {
			throw new DirAndFileManagerException('Nie mozna zmienic biezacego katalogu: ' . $dirPath);
		}
	}
	
	/**
	 * Zwraca nazwe biezacego katalogu
	 *
	 * @access public
	 * @return string
	 * 
	 */	
	public function cwd() {
		$cwd = getcwd();
		if(!$cwd) {
			throw new DirAndFileManagerException('Nie mozna pobrac nazwy biezacego katalogu');
		}
		return $cwd;
	}
	
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
	public function rename($filePath, $newFilePath, $overwrite=false) {		
		parent::rename($filePath, $newFilePath, $overwrite=false);
	}
	
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
	public function move($filePath, $newFilePath, $overwrite=false) {
		parent::move($filePath, $newFilePath, $overwrite=false);
	}
	
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
	public function chmod($filePath, $newPerms) {
		parent::chmod($filePath, $newPerms);
	}	
	
	/**
	 * Zwraca wlasciwosci pliku/katalogu
	 * 
	 * @access public
	 * @param string Sciezka do pliku/katalogu
	 * @return array
	 * 
	 */
	public function stats($filePath) {	
		return parent::stats($filePath);
	}	
	
}

?>
