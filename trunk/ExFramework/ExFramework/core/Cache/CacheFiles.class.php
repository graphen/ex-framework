<?php

/**
 * @class CacheFiles
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class CacheFiles implements ICacheFiles {
	
	/**
	 * Czas zycia cache
	 *
	 * @var integer
	 */	
	protected $_lifeTime = 3600; //seconds	
	
	/**
	 * Sciezka do katalogu z plikami cache
	 *
	 * @var string
	 */	
	protected $_cacheDir = '';
	
	/**
	 * Rozszerzenie dla plikow cache
	 *
	 * @var string
	 */	
	protected $_fileExt = '.cache';
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param int Czas zycia cache
	 * @param string Sciezka do katalogu cache
	 * @param string Nazwa rozszerzenia pliku cache
	 *
	 */	
	public function __construct($cacheLifeTime=3600, $cacheDir='', $cacheFileExt='.cache') {
		if(!empty($cacheDir)) {
			$this->setCacheDir($cacheDir);
		}
		$this->setFileExt($cacheFileExt);
		$this->setLifeTime($cacheLifeTime);
	}
	
	/**
	 * Metoda tworzy ciag bedacy sciezka do pliku cache o podanym identyfikatorze
	 * 
	 * @access protected
	 * @param string Identyfikator cache
	 * @return string
	 * 
	 */
	protected function makeCacheFilePath($id) {
		$dirPath = $this->_cacheDir;
		if(substr($dirPath, -1, 1) != '/') {
			$dirPath = $dirPath . '/';
		}
		$filePath = $dirPath . 'cache_' . $id . '_' . $this->_fileExt;
		return $filePath;
	}

	/**
	 * Prywatna metoda clone zapobiegajaca kopiowaniu obiektu
	 * 
	 * @access private
	 *
	 */		
	private function __clone() {
		throw new CacheException('Obiekt nie moze byc kopiowany');
	}
	
	/**
	 * Metoda magiczna pozwala na zapis danych cache identyfikowanych przez podany identyfikator do pliku
	 * 
	 * @access public
	 * @param string Identyfikator cache
	 * @param mixed Dane do zapisania 
	 * @return void
	 *  
	 */	
	public function __set($id, $data) {
		$this->store($id, $data);
	}
	
	/**
	 * Metoda magiczna pozwala na odczyt z pliku danych identyfikowanych podanym identyfikatorem i zwrocenie ich. Mozna odczytac jednoczesnie takze cala tablice
	 * 
	 * @access public
	 * @param string Identyfikator cache, lub tablica z identyfikatorami
	 * @return mixed|false
	 *  
	 */	
	public function __get($id) {
		return $this->fetch($id);
	}
	
	/**
	 * Metoda magiczna pozwala na sprawdzenie czy cache dla danego identyfikatora istnieje
	 * 
	 * @access public
	 * @param string Identyfikator cache
	 * @return bool
	 * 
	 */	
	public function __isset($id) {
		return $this->exists($id);
	}
	
	/**
	 * Metoda magiczna pozwala na usuniecie pliku cache zawierajacego dane identyfikowane podanym identyfikatorem 
	 * 
	 * @access public
	 * @param string Identyfikator cache
	 * @return void
	 * 
	 */		
	public function __unset($id) {
		$this->delete($id);
	}
	
	/**
	 * Metoda ustawia sciezke do katalogu cache
	 * 
	 * @access public
	 * @param string Sciezka do katalogu z cache
	 * @return void
	 *  
	 */
	public function setCacheDir($cacheDir) {
		if(!file_exists($cacheDir)) {
			throw new CacheException('Podany katalog: ' . $cacheDir . ' nie istnieje');
		}
		if(!is_dir($cacheDir)) {
			throw new CacheException('Podana sciezka: ' . $cacheDir . ' nie prowadzi do katalogu');
		}
		if(!is_writable($cacheDir)) {
			throw new CacheException('Nie mozna zapisywac w katalogu cache: ' . $cacheDir);
		}
		$this->_cacheDir = $cacheDir;
	}
	
	/**
	 * Metoda zwraca sciezke do katalogu cache
	 * 
	 * @access public
	 * @return string
	 *  
	 */
	public function getCacheDir() {
		return $this->_cacheDir;
	}
	
	/**
	 * Metoda ustawia globalny czas życia cache
	 * 
	 * @access public
	 * @param int Czas zycia cache
	 * @return void
	 *  
	 */
	public function setLifeTime($lifeTime=3600) {
		$this->_lifeTime = (int)$lifeTime;
	}
	
	/**
	 * Metoda zwraca globalna wartosc czasu zycia szablonow
	 * 
	 * @access public
	 * @return int
	 *  
	 */
	public function getLifeTime() {
		return $this->_lifeTime; 
	}
	
	/**
	 * Metoda ustawia rozszerzenie plikow cache
	 * 
	 * @access public
	 * @param string Rozszerzenie plikow cache
	 * @return void
	 *  
	 */
	public function setFileExt($ext='.cache') {
		if($ext !== null) {
			$this->_fileExt = '.' . ltrim($ext, '.');
		}
	}
	
	/**
	 * Metoda zwraca rozszerzenie plikow cache
	 * 
	 * @access public
	 * @return string
	 *  
	 */
	public function getFileExt() {
		return $this->_fileExt; 
	}	

	/**
	 * Metoda odczytuje z pliku dane identyfikowane podanym identyfikatorem i zwraca je. Mozna odczytac jednoczesnie takze cala tablice
	 * 
	 * @access public
	 * @param string Identyfikator cache, lub tablica z identyfikatorami
	 * @return mixed|false
	 *  
	 */
	public function fetch($id) {
		if(is_array($id)) {
			$retArr = array();
			foreach($id AS $index) {
				$filePath = $this->makeCacheFilePath($index);
				if(!file_exists($filePath)) {
					$retArr[$index] = null; //brak pliku, cache nie istnieje
					continue;
				}				
				if(!($fp = fopen($filePath, 'r'))) {
					throw new CacheException('Nie mozna otworzyc pliku: ' . $filePath . ' do odczytu');
				}
				if(!flock($fp, LOCK_SH)) {
					throw new CacheException('Nie mozna zablokowac pliku: ' . $filePath . ' do odczytu');
				}
				if(!($content = fread($fp, filesize($filePath)))) {
					throw new CacheException('Nie mozna czytac danych z pliku: ' . $filePath);
				}
				$unserializedData = @unserialize($content);
				$lifeTime = $content['lifeTime'];
				$data = $content['data'];
				fclose($fp);
				$time = time();
				if(filemtime($filePath) > ($time - $lifeTime)) {
					$retArr[$index] = $data;
				}
				else {
					@unlink($filePath);
					$retArr[$index] = null; //cache jest nieaktualny
				}
			}
			return $retArr;
		}
		else {
			$filePath = $this->makeCacheFilePath($id);
			if(!file_exists($filePath)) {
				return false; //brak pliku, cache nie istnieje
			}
			if(!($fp = fopen($filePath, 'r'))) {
				throw new CacheException('Nie mozna otworzyc pliku: ' . $filePath . ' do odczytu');
			}
			if(!flock($fp, LOCK_SH)) {
				throw new CacheException('Nie mozna zablokowac pliku: ' . $filePath . ' do odczytu');
			}
			$fileSize = (filesize($filePath) == 0) ? 1024 : filesize($filePath);
			if(!($content = fread($fp, $fileSize))) {
				throw new CacheException('Nie mozna czytac danych z pliku: ' . $filePath);
			}
			$unserializedData = @unserialize($content);
			$lifeTime = $unserializedData['lifeTime'];
			$data = $unserializedData['data'];
			fclose($fp);
			$time = time();
			if(filemtime($filePath) >= ($time - $lifeTime)) {
				return $data;
			}
			else {
				@unlink($filePath);
				return false; //cache jest nieaktualny
			}
		}
	}
	
	/**
	 * Metoda zapisuje dane cache identyfikowane przez podany identyfikator do pliku
	 * 
	 * @access public
	 * @param string Identyfikator cache 
	 * @param mixed Dane do zapisania
	 * @param int Czas zycia cache
	 * @return void
	 *  
	 */
	public function store($id, $data, $lifeTime=null) {
		$arrToStore = array();
		$arrToStore['data'] = $data;
		$arrToStore['lifeTime'] = ($lifeTime !== null) ? $lifeTime : $this->_lifeTime;
		$serializedData = serialize($arrToStore);
		$filePath = $this->makeCacheFilePath($id);
		touch($filePath);
		chmod($filePath, 0666);
		if(!$fp = fopen($filePath, 'r+')) {
			throw new CacheException('Nie mozna otworzyc pliku: ' . $filePath . ' do zapisu');
		}
		if(!flock($fp, LOCK_EX)) {
			throw new CacheException('Nie mozna zablokowac pliku: ' . $filePath . ' do zapisu');
		}
		fseek($fp, 0);
		ftruncate($fp, 0);
		if(!fwrite($fp, $serializedData)) {
			throw new CacheException('Nie mozna zapisac danych do pliku: ' . $filePath);
		}
		if(!flock($fp, LOCK_UN)) {
			throw new CacheException('Nie mozna odblokowac pliku: ' . $filePath);
		}
		fclose($fp);
	}
	
	/**
	 * Metoda sprawdza czy cache dla danego identyfikatora istnieje
	 * 
	 * @access public
	 * @param string Identyfikator cache
	 * @return bool
	 * 
	 */
	public function exists($id) {
		$ret = $this->fetch($id);
		if($ret !== false) {
			return true;
		}
		else {
			return false;
		}
	} 
	
	/**
	 * Metoda usuwa plik cache zawierajacy dane identyfikowane podanym identyfikatorem 
	 * 
	 * @access public
	 * @param string Identyfikator cache
	 * @return void
	 * 
	 */	
	public function delete($id) {
		$filePath = $this->makeCacheFilePath($id);
		if(!file_exists($filePath)) {
			return; //brak pliku, cache nie istnieje
		}
		if(!@unlink($filePath)) {
			throw new CacheException('Nie mozna usunac pliku cache: ' . $filePath);
		}
	}
	
	/**
	 * Metoda usuwa caly cache
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function clear() {
		$cacheDir = rtrim($this->_cacheDir, '/');
		$directory = dir($cacheDir);
		while(($entry = $directory->read()) != false) {
			if($entry != '.' && $entry != '..') {
				if(!is_dir($cacheDir . '/' . $entry)) {
					@unlink($cacheDir . '/' . $entry);
				}
			}
		}
		$directory->close();
	}
	
}

?>
