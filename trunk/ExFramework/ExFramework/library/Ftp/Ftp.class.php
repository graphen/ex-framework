<?php

/**
 * @class Ftp
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 */
class Ftp implements IFtp {
	
	/**
	 * Nazwa uzytkownika
	 * 
	 * @var string
	 * 
	 */
	protected $_user = '';
	
	/**
	 * Haslo uzytkownika
	 * 
	 * @var string
	 * 
	 */
	protected $_password = '';
	
	/**
	 * Adres hosta
	 * 
	 * @var string
	 * 
	 */	
	protected $_host = '';
	
	/**
	 * Numer portu
	 * 
	 * @var integer
	 * 
	 */	
	protected $_port = 21;
	
	/**
	 * Czas oczekiwania na odpowiedz
	 * 
	 * @var integer
	 * 
	 */	
	protected $_timeout = 120;
	
	/**
	 * Sciezka do katalogu, do ktorego ma przejsc po zalogowaniu
	 * 
	 * @var string
	 * 
	 */	
	protected $_dirPath = '';
	
	/**
	 * Tryb aktywny lub pasywny //true dla pasywnego
	 * 
	 * @var bool
	 * 
	 */	
	protected $_passive = true;
	
	/**
	 * Polaczenie szyfrowane lub nie //true dla szyfrowanego
	 * 
	 * @var string
	 * 
	 */	
	protected $_ssl = false;
	
	/**
	 * Zasob przechowujacy dane polaczenia
	 * 
	 * @var resource
	 * 
	 */	
	protected $_connId = null;
	
	/**
	 * Jesli serwer ftp wymaga preallokacji //true
	 * 
	 * @var bool
	 * 
	 */		
	protected $_preallocation = false;
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param string Nazwa hosta
	 * @param string Nazwa uzytkownika
	 * @param string Haslo uzytkownika
	 * @param string Sciezka do katalogu, do ktorego ma przejsc po zalogowaniu
	 * @param integer Numer portu uslugi
	 * @param integer Czas oczekiwania na odpowiedz
	 * @param bool Czy polaczenie pasywne
	 * @param bool Czy polaczenie szyfrowane 
	 * @param bool Czy przed uploadem ma byc preallokacja //najczesciej false
	 * 
	 */
	public function __construct($host='', $user='', $password='', $dirPath='', $port=21, $timeout=120, $passive=true, $ssl=false, $prealloc=false) {
		$this->init($host, $user, $password, $dirPath, $port, $timeout, $passive, $ssl, $prealloc);
	}
	
	/**
	 * Inicjalizuje pola obiektu
	 * 
	 * @access protected
	 * @param string Nazwa hosta
	 * @param string Nazwa uzytkownika
	 * @param string Haslo uzytkownika
	 * @param string Sciezka do katalogu, do ktorego ma przejsc po zalogowaniu
	 * @param integer Numer portu uslugi
	 * @param integer Czas oczekiwania na odpowiedz
	 * @param bool Czy polaczenie pasywne
	 * @param bool Czy polaczenie szyfrowane 
	 * @param bool Czy przed uploadem ma byc preallokacja //najczesciej false
	 * @return void
	 * 
	 */	
	protected function init($host='', $user='', $password='', $dirPath='', $port=21, $timeout=120, $passive=true, $ssl=false, $prealloc=false) {
		$this->_user = (string)$user;
		$this->_password = (string)$password;
		$this->_host = (string) preg_replace('/.+?:\/\//', '', $host);
		$this->_port = (int)$port;
		$this->_timeout = (int)$timeout;
		$this->_dirPath = (string)$dirPath;
		$this->_passive = (bool)$passive;
		$this->_ssl = (bool)$ssl;
		$this->_preallocation = (bool)$prealloc;		
	}
	
	/**
	 * Destruktor
	 * 
	 * @access public
	 * 
	 */
	public function __destruct() {
		$this->close();
	}
	
	/**
	 * Ustawia nazwe uzytkownia
	 * 
	 * @access public
	 * @param string Nazwa uzytkownika, domyslnie ''
	 * @return void
	 * 
	 */	
	public function setUser($user='') {
		$this->_user = (string)$user;
	}
	
	/**
	 * Ustawia haslo
	 * 
	 * @access public
	 * @param string Haslo, domyslnie ''
	 * @return void
	 * 
	 */	
	public function setPassword($password='') {
		$this->_password = (string)$password;
	}
	
	/**
	 * Ustawia nazwe hosta
	 * 
	 * @access public
	 * @param string Nazwa hosta, domyslnie ''
	 * @return void
	 * 
	 */		
	public function setHost($host='') {
		$this->_host = (string)$host;
	}
	
	/**
	 * Ustawia port
	 * 
	 * @access public
	 * @param int Port, domyslnie 21
	 * @return void
	 * 
	 */		
	public function setPort($port=21) {
		$this->_port = (int)$port;
	}
	
	/**
	 * Ustawia czas po ktorym nastapi rozlaczenie 
	 * 
	 * @access public
	 * @param int Czas do rozlaczenia, domyslnie 120s
	 * @return void
	 * 
	 */		
	public function setTimeout($timeout=120) {
		$this->_timeout = (int)$timeout;
	}
	
	/**
	 * Ustawia sciezke do katalogu poczatkowego
	 * 
	 * @access public
	 * @param string Sciezka do katalogu poczatkowego, domyslnie ''
	 * @return void
	 * 
	 */		
	public function setDirPath($dirPath='') {
		$this->_dirPath = (string)$dirPath;
	}
	
	/**
	 * Wlacza/wylacza tryb pasywny
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie trybu pasywnego, domyslnie true
	 * @return void
	 * 
	 */		
	public function setPassive($passive=true) {
		$this->_passive = (bool)$passive;
	}

	/**
	 * Wlacza/wylacza przesylanie poprzez ssl
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie przesylania przez ssl, domyslnie false
	 * @return void
	 * 
	 */		
	public function setSsl($ssl=false) {
		$this->_ssl = (bool)$ssl;
	}

	/**
	 * Wlacza/wylacza preallokacje na serwerze
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie preallokacji, domyslnie false
	 * @return void
	 * 
	 */	
	public function setPreallocation($prealloc=false) {
		$this->_preallocation = (bool)$prealloc;
	}
	
	/**
	 * Nawiazuje polaczenie, loguje uzytkownika do serwera, ustawia tryb i przechodzi do okreslonego katalogu jesli go podano
	 * 
	 * @access public
	 * @return void
	 * 
	 */
	public function connect() {
		if($this->isConnected() === true) {
			return;
		}
		if(empty($this->_host) || empty($this->_user)) {
			throw new FtpException('Nazwa hosta i nazwa uzytkownika nie moga byc puste');
		}
		if($this->_ssl === true) {
			if(function_exists(ftp_ssl_connect)) {
				$connId = @ftp_ssl_connect($this->_host, $this->_port, $this->_timeout);
			}
			else {
				throw new FtpException('Protokol SSL jest niedostepny');
			}
		}
		else {
			$connId = @ftp_connect($this->_host, $this->_port, $this->_timeout);
		}
		if($connId === false) {
			throw new FtpException('Nie mozna polaczyc sie z serwerem ftp');
		}
		$this->_connId = $connId;
		if(!@ftp_login($this->_connId, $this->_user, $this->_password)) {
			throw new FtpException('Nie mozna zalogowac sie do serwera ftp');			
		}
		if(!ftp_pasv($this->_connId, $this->_passive)) {
			throw new FtpException('Nie mozna ustawic trybu (passive/active)');			
		}
		if(!empty($this->_dirPath)) {
			$this->chdir($this->_dirPath);
		}
	}
	
	/**
	 * Zwraca rodzaj systemu operacyjnego na ktorym dziala serwer ftp
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function systype() {
		$this->connect();
		if(($sysType = @ftp_systype($this->_connId)) === false) {
			throw new FtpException('Nie mozna pobrac nazwy systemu operacyjnego');			
		}
		return $sysType;
	}
	
	/**
	 * Sprawdza czy nawiazano poaczenie poprzez obecnosc zmiennej zasobu
	 * 
	 * @access public
	 * @return bool
	 * 
	 */
	public function isConnected() {
		return (is_resource($this->_connId)) ? true : false;
	}
	
	/**
	 * Przechodzi do podanego katalogu
	 * 
	 * @access public
	 * @param string Sciezka do katalogu
	 * @return void
	 * 
	 */
	public function chdir($dirPath) {
		$this->connect();
		$dirPath = (substr($dirPath, -1) == '/') ? $dirPath : $dirPath . '/';
		if((@ftp_chdir($this->_connId, $dirPath)) === false) {
			throw new FtpException('Nie mozna zmienic biezacego katalogu na katalog: ' . $dirPath);
		}
	}
	
	/**
	 * Przechodzi do katalogu o jeden poziom wyzej
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function cdup() {
		$this->connect();		
		if((@ftp_cdup($this->_connId)) === false) {
			throw new FtpException('Nie mozna zmienic biezacego katalogu na katalog nadrzedny');				
		}
	}
	
	/**
	 * Zwraca sciezke do biezacego katalogu
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function pwd() {
		$this->connect();
		if(($curDir = @ftp_pwd($this->_connId)) === false) {
			throw new FtpException('Nie mozna pobrac nazwy biezacego katalogu');				
		}
		return $curDir;
	}
	
	/**
	 * Zamyka polaczenie z serwerem ftp
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function close() {
		if($this->isConnected()) {
			if((@ftp_close($this->_connId)) === false) {
				throw new FtpException('Nie mozna zamknac polaczenia z serwerem ftp');			
			}
		}
	}
	
	/**
	 * Zwraca liste plikow w podanym katalogu w formie tablicy
	 * 
	 * @access public
	 * @param string Sciezka do zadanego katalogu
	 * @return array
	 * 
	 */	
	public function arrayList($dirPath='') {
		$this->connect();		
		if($dirPath == '') {
			$dirPath = $this->pwd();
		}
		$dirPath = (substr($dirPath, -1) == '/') ? $dirPath : $dirPath . '/';
		if(($dirArray = @ftp_nlist($this->_connId, $dirPath)) === false) {
			throw new FtpException('Nie mozna pobrac listy plikow katalogu: ' . $dirPath);				
		}
		return $dirArray;		
	}
	
	/**
	 * Zwraca liste plikow w podanym katalogu w formie tablicy z surowymi danymi w formie tekstowej
	 * 
	 * @access public
	 * @param string Sciezka do zadanego katalogu
	 * @param bool Czy pobrac cale drzewo od podanej sciezki
	 * @return array
	 * 
	 */
	public function rawList($dirPath='', $recursive=false) {
		$this->connect();
		if($dirPath == '') {
			$dirPath = $this->pwd();
		}
		//$dirPath = (substr($dirPath, -1) == '/') ? $dirPath : $dirPath . '/';
		if(($arrayList = @ftp_rawlist($this->_connId, $dirPath, $recursive)) === false) {
			throw new FtpException('Nie mozna pobrac listy plikow katalogu: ' . $dirPath);				
		}
		return $arrayList;		
	}
	
	/**
	 * Tworzy katalog i moze nadac uprawnienia
	 * 
	 * @access public
	 * @param string Nazwa lub sciezka tworzonego katalogu
	 * @param int Uprawnienia
	 * @return void
	 * 
	 */
	public function mkdir($dir, $perms=null) {
		$this->connect();		
		if((@ftp_mkdir($this->_connId, $dir)) === false) {
			throw new FtpException('Nie mozna utworzyc katalogu: ' . $dir);
		}
		if($perms !== null) {
			$this->chmod($dir, $perms);
		}
	}
	
	/**
	 * Usuwa podany katalog
	 * 
	 * @access public
	 * @param string Sciezka do usuwanego katalogu
	 * @return void
	 * 
	 */
	public function rmdir($dir) {
		$this->connect();		
		if((@ftp_rmdir($this->_connId, $dir)) === false) {
			throw new FtpException('Nie mozna usunac katalogu: ' . $dir);
		}
	}	
	
	/**
	 * Usuwa podany plik
	 * 
	 * @access public
	 * @param string Sciezka do usuwanego pliku
	 * @return void
	 * 
	 */
	public function delete($file) {
		$this->connect();		
		if((ftp_delete($this->_connId, $file)) === false) {
			throw new FtpException('Nie mozna skasowac pliku: ' . $file);
		}
	}	

	/**
	 * Zmienia nazwe, przenosci pliki i katalogi
	 * 
	 * @access public
	 * @param string Sciezka zrodlowa do pzrenoszonego lub zmienianego katalogu
	 * @param string Sciezka docelowa dla pliku
	 * @return void
	 * 
	 */
	public function rename($src, $dest) {
		$this->connect();		
		if((@ftp_rename($this->_connId, $src, $dest)) === false) {
			throw new FtpException('Nie mozna zmienic nazwy pliku: ' . $src);
		}
	}	

	/**
	 * Jest to alias dla rename
	 * 
	 * @access public
	 * @param string Sciezka zrodlowa do pzrenoszonego lub zmienianego katalogu
	 * @param string Sciezka docelowa dla pliku
	 * @return void
	 * 
	 */	
	public function move($src, $dest) {
		try {
			$this->rename($src, $dest);
		}
		catch (FtpException $e) {
			throw new FtpException('Nie mozna przeniesc lub zmienic nazwy pliku: ' . $src);
		}
	}
	
	/**
	 * Nadaje uprawnienia
	 * 
	 * @access public
	 * @param string Sciezka do pliku ktoremu nadaje uprawnienia
	 * @param integer Uprawnienia w formie osemkowej //np 0777
	 * @return void
	 * 
	 */
	public function chmod($file, $perms) {
		if(!function_exists('ftp_chmod')) {
			throw new FtpException('Nie mozna zmienic uprawnien, funkcja nie istnieje');
		}
		$this->connect();
		if(($retPerms = @ftp_chmod($this->_connId, $perms, $file)) === false) {
			throw new FtpException('Nie mozna zmienic uprawnien do pliku: ' . $file);
		}
	}		

	/**
	 * Zwraca czas ostatniej modyfikacji
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @return integer //-1 jesli nie ma obslugi 
	 * 
	 */
	public function mdtm($file) {
		$this->connect();		
		$lastMod = @ftp_mdtm($this->_connId, $file);
		return $lastMod;
	}

	/**
	 * Zwraca wielkosc pliku w bajtach
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @return integer
	 * 
	 */		
	public function size($file) {
		$this->connect();		
		if(($size = @ftp_size($this->_connId, $file)) == -1) {
			throw new FtpException('Nie mozna pobrac wielkosci pliku: ' . $file);
		}
		return $size;
	}

	/**
	 * Allokuje miejsce dla pliku na serwerze //najczesciej niepotrzebne
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */		
	public function alloc($filePath) {
		$this->connect();
		if($this->_preallocation !== true) {
			return;
		}	
		$fileSize = filesize($filePath);
		if((@ftp_alloc($this->_connId, $fileSize)) === false) {
			throw new FtpException('Nie mozna allokowac miejsca na serwerze');
		}
	}			

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
	public function upload($localFile, $remoteFile, $mode='binary', $perms=null) {
		$mode = ($mode == 'binary') ? FTP_BINARY : FTP_ASCII;
		$this->connect();
		if(!is_resource($localFile)) {
			if(!file_exists($localFile)) {
				throw new FtpException('Lokalny plik: ' . $localFile . ' nie istnieje');
			}
			$this->alloc($localFile);			
			if((ftp_put($this->_connId, $remoteFile, $localFile, $mode)) === false) {
				throw new FtpException('Nie mozna przeslac pliku: ' . $localFile . ' na serwer ftp');
			}
		}
		else {			
			if((@ftp_fput($this->_connId, $remoteFile, $localFile, $mode)) === false) {
				throw new FtpException('Nie mozna zapisac danych do zdalnego pliku: ' . $remoteFile . ' na serwerze ftp');
			}
		}
		if($perms !== null) {
			$this->chmod($remoteFile, $perms);
		}
	}
	
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
	public function download($remoteFile, $localFile, $mode='binary') {
		$this->connect();
		$mode = ($mode == 'binary') ? FTP_BINARY : FTP_ASCII;				
		if(!is_resource($localFile)) {
			if((@ftp_get($this->_connId, $localFile, $remoteFile, $mode)) === false) {
				throw new FtpException('Nie mozna pobrac pliku: ' . $remoteFile);
			}
		}
		else {
			if((@ftp_fget($this->_connId, $localFile, $remoteFile, $mode)) === false) {
				throw new FtpException('Nie mozna zapisac danych do lokalnego pliku: ' . $localFile);
			}			
		}
	}

	/**
	 * Zada wykonania polecenia przez serwer ftp
	 * UWAGA! To rozszerzenie jest zadko obslugiwane
	 * 
	 * @access public
	 * @param string Polecenie
	 * @return void
	 * 
	 */	
	public function exec($command) {
		$this->connect();		
		if((@ftp_exec($this->_connId, $command)) === false) {
			throw new FtpException('Nie mozna wykonac polecenia: ' . $command);
		}
	}

	/**
	 * Wykonuje polecenie na serwerze, zwraca wynik, nie sprawdza czy sie wykonalo poprawnie
	 * 
	 * @access public
	 * @param string Polecenie
	 * @return mixed
	 * 
	 */
	public function raw($command) {
		$this->connect();		
		return @ftp_raw($this->_connId, $command);
	}

	/**
	 * Wykonuje polecenie FEAT na serwerze
	 * 
	 * @access public
	 * @return array
	 * 
	 */
	public function feat() {
		$this->connect();		
		return @ftp_raw($this->_connId, 'FEAT');
	}

	/**
	 * Usuwa rekursywnie katalogi na serwerze ftp
	 * 
	 * @access public
	 * @param string Sciezka do zdalnego katalogu
	 * @return void
	 * 
	 */	
	public function rmdirRecursive($dirPath) {
		$this->connect();
		$rawList = $this->rawlist($dirPath); //moze rzucic wyjatkiem
		if(count($rawList) > 0) {
			foreach($rawList as $file) {
				$file = preg_split('/ +/', $file);
				$fileType = substr($file[0], 0, 1);
				$fileName = $file[8];
				if($fileName == '.' OR $fileName == '..') {
					continue;
				}
				if($fileType == 'd') {
					$this->rmdirRecursive($dirPath . '/' . $fileName);
				}
				else {
					$this->delete($dirPath . '/' . $fileName); //moze rzucic wyjatkiem
				}
			}
		}
		$this->rmdir($dirPath); //moze rzucic wyjatkiem
	}
	
	/**
	 * Sprawdza czy istnieje zdalny katalog
	 * 
	 * @access public
	 * @param string Sciezka do zdalnego katalogu
	 * @return bool
	 * 
	 */	
	public function dirExists($dirPath) {
		$tmpPath = $this->pwd(); //moze rzucic wyjatkiem
		$dirPath = (substr($dirPath, -1) == '/') ? $dirPath : $dirPath . '/';
		if((@ftp_chdir($this->_connId, $dirPath)) === false) {
			$result = false;
		}
		else {
			$result = true;
		}
		$this->chdir($tmpPath); //moze rzucic wyjatkiem
		return $result;
	}
	
	/**
	 * Tworzenie drzewa katalogow
	 * 
	 * @access public
	 * @param string Sciezka do zdalnego katalogu
	 * @param int Uprawnienia
	 * @return bool
	 * 
	 */		
	public function mkdirRecursive($dir, $perms=null) {
		$this->connect();
		$parts = explode('/', $dir);
		$tempDir = '';
		foreach($parts as $part) {
			$tempDir .= $part;
			if(!$this->dirExists($tempDir)) {
				$this->mkdir($tempDir);
			}
			$tempDir .= '/';
		}
		if($perms !== null) {
			$this->chmod($tempDir, $perms); //moze rzucic wyjatkiem
		}		
	}
	
	/**
	 * Tworzy kopie drzewa katalogow na sewerze
	 * 
	 * @access public
	 * @param string Sciezka do localnego katalogu 
	 * @param string Sciezka do zdalnego katalogu
	 * @return void
	 * 
	 */	
	public function uploadRecursive($localDir, $remoteDir) {
		$this->connect();
		if($dirHandler = @opendir($localDir)) {
			if(!$this->dirExists($remoteDir)) {
				$this->mkdir($remoteDir); //moze rzucic wyjatkiem
				//$this->chdir($remoteDir); //moze rzucic wyjatkiem //tego nie moze byc
			}
			$localDir = (substr($localDir, -1) == '/') ? $localDir : $localDir . '/';		
			$remoteDir = (substr($remoteDir, -1) == '/') ? $remoteDir : $remoteDir . '/';	
			while(($file = readdir($dirHandler)) !== false) {
				if(($file != '.') AND ($file != '..')) {
					if((@is_dir($localDir . $file))) {
						$this->uploadRecursive($localDir . $file, $remoteDir . $file);
					}
					else {
						$this->upload($localDir . $file, $remoteDir . $file, 'binary');
					}
				}
			}	
		}
		else {
			throw new FtpException('Nie mozna otworzyc folderu: ' . $localDir);
		}
	}
	
}

?>
