<?php

/**
 * @class DirAndFileManager
 *
 * @author Przemyslaw Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class DirAndFileManager implements IDirAndFileManager {
		
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
		if(!file_exists($filePath)) {
			throw new DirAndFileManagerException('Plik/katalog zrodlowy: ' . $filePath . ' nie istnieje');
		}
		if(file_exists($newFilePath) AND ($overwrite == false)) {
			throw new DirAndFileManagerException('Plik/katalog docelowy: ' . $newFilePath . ' istnieje');
		}
		if(!rename($filePath, $newFilePath)) {
			throw new DirAndFileManagerException('Nie mozna przeniesc lub zmienic nazwy pliku/katalogu: ' . $filePath);
		}
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
		return $this->rename($filePath, $newFilePath, $overwrite);
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
		if(!file_exists($filePath)) {
			throw new DirAndFileManagerException('Plik/katalog: ' . $filePath . ' nie istnieje');
		}
		if(!chmod($filePath, $newPerms)) {
			throw new DirAndFileManagerException('Nie mozna zmienic uprawnien pliku/katalogu: ' . $filePath);
		}
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
		if(!file_exists($filePath)) {
			throw new DirAndFileManagerException('Plik/katalog: ' . $filePath . ' nie istnieje');
		}
		$properities = array();
		$properities['name'] = basename($filePath);
		$properities['iswritable'] = is_writable($filePath);
		$properities['isreadable'] = is_readable($filePath);
		$properities['isexecutable'] = is_executable($filePath);
		$properities['isdir'] = is_dir($filePath);
		$properities['isfile'] = is_file($filePath);
		$properities['islink'] = is_link($filePath);		
		if(($properities['filesize'] = filesize($filePath)) == false) {
			throw new DirAndFileManagerException('Nie mozna pobrac wielkosci pliku/katalogu: ' . $filePath);
		}
		if(($properities['ctime'] = filectime($filePath)) == false) {
			throw new DirAndFileManagerException('Nie mozna pobrac daty ostatniej zmiany pliku/katalogu: ' . $filePath); //[utworzenie/zapis/zmiana uprawnien]
		}
		if(($properities['mtime'] = filemtime($filePath)) == false) {
			throw new DirAndFileManagerException('Nie mozna pobrac daty ostatniej modyfikacji pliku/katalogu: ' . $filePath); //[utworzenie/zmiana zawartosci]
		}
		if(!strstr('win', PHP_OS)) {
			if (($properities['atime'] = fileatime($filePath)) == false) {
				throw new DirAndFileManagerException('Nie mozna pobrac daty ostatniego uzycia pliku/katalogu: ' . $filePath);
			}
			if(($properities['fileowner'] = fileowner($filePath)) == false) {
				throw new DirAndFileManagerException('Nie mozna pobrac identyfikatora UID wlasciciela pliku/katalogu: ' . $filePath);
			}
			if(($properities['filegroup'] = filegroup($filePath)) == false) {
				throw new DirAndFileManagerException('Nie mozna pobrac numeru grupy GID pliku/katalogu: ' . $filePath);
			}
			if(($properities['filetype'] = filetype($filePath)) == false) {
				throw new DirAndFileManagerException('Nie mozna pobrac typu pliku/katalogu: ' . $filePath);
			}
			if(($properities['fileperms'] = decoct(fileperms($filePath))) == false) {
				throw new DirAndFileManagerException('Nie mozna pobrac upawnien do pliku/katalogu: ' . $filePath);
			}
			if(($properities['fileinode'] = fileinode($filePath)) == false) {
				throw new DirAndFileManagerException('Nie mozna pobrac numeru inode pliku/katalogu: ' . $filePath);
			}
			/*
			if(count($properities['userInfo'] = posix_getpwuid($properities['fileowner'])) == 0) {
				throw new DirAndFileManagerException('Nie mozna pobrac informacji o uzytkowniku pliku: ' . $filePath);
			}
			if(($properities['groupInfo'] = posix_getgrgid($properities['filegroup'])) == false) {
				throw new DirAndFileManagerException('Nie mozna pobrac informacji o grupie, do ktorej nalezy plik: ' . $filePath);
			}
			*/
		}
		return $properities;
	}
	
}

?>
