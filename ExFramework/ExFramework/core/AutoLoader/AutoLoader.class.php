<?php

/**
 * @class AutoLoader
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class AutoLoader implements IAutoLoader {
	
	/**
	 * Sciezka do projektu, katalog z ktorego maja byc ladowane klasy i interfejsy
	 *
	 * @var string
	 * 
	 */		
	protected $_projectDir = '';
	
	/**
	 * Tablica nazw podkatalogow, jesli podana, klasy i interfejsy ladowane beda tylko z nich
	 *
	 * @var array
	 * 
	 */		
	protected $_directories = array();
	
	/**
	 * Wskazuje czy obiekt autoloadera zostal juz zarejestrowany, nie mozna bowiem rejestrowac takich obiektow wielokrotnie
	 *
	 * @var bool
	 * 
	 */		
	protected $_registered = false;
	
	/**
	 * Tablica sciezek do klas i interfejsow
	 *
	 * @var array
	 * 
	 */			
	protected $_paths = array();
	
	/**
	 * Obiekt zarzadzania Cache
	 *
	 * @var object
	 * 
	 */			
	protected $_cache = null;	
	
	/**
	 * Konstruktor 
	 * 
	 * @access public
	 * @param string Sciezka do katalogu projektu
	 * @param object Obiekt zarzadzania cache
	 * @param array Tablica nazw podkatalogow katalogu projektu
	 * 
	 */	
	public function __construct($projectDir, ICache $cache=null, $directories=null) {
		$this->_cache = $cache;
		$data = false;
		if(is_object($this->_cache)) {
			$data = $this->_cache->fetch('autoLoaderData');
		}
		if($data != false) {
			$this->_paths = $data;
		}
		else {
			if(($projectDir == null) || ($projectDir == '')) {
				throw new AutoLoaderException('Nie podano sciezki do katalogu projektu');
			}
			if(!is_dir($projectDir)) {
				throw new AutoLoaderException('Sciezka ' . $projectDir . ' nie prowadzi do katalogu projektu');
			}
			$this->_projectDir = rtrim($projectDir, '/') . '/';
			if($directories != null) {
				if(!is_array($directories)) {
					$directories = array($directories);
				}
				foreach($directories AS $dir) {
					if(($dir == '') || ($dir == '/')) {
						$sdir = $this->_projectDir;
					}
					else {
						$sdir = $this->_projectDir . ltrim($dir, '/') . '/';
					}
					if(is_dir($sdir)) {
						$this->_directories[] = $sdir;
						$dirIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sdir));
						foreach ($dirIterator as $dir) {
							$classFileName = $dir->getFileName();
							$this->_paths[$classFileName] = $dir->getPathname();
						}
					}
					else {
						throw new AutoLoaderException('Sciezka: ' . $sdir . ' nie prowadzi do zadnego katalogu, w katalogu projektu');
					}
				}
			}
			else {
				$dirIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->_projectDir));
				foreach ($dirIterator as $dir) {
					$classFileName = $dir->getFileName();
					$this->_paths[$classFileName] = $dir->getPathname();
				}			
			}
			if(is_object($this->_cache)) {
				$this->_cache->store('autoLoaderData', $this->_paths, 240);
			}			
		}
		$this->register();
	}
	
	/**
	 * Rejestruje dany obiekt autoloadera
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function register() {
		if($this->_registered === false) {
			spl_autoload_register(array($this, 'autoload'));
			$this->_registered = true;
		}
	}
	
	/**
	 * Laduje automatycznie klase lub interfejs o danej nazwie
	 * 
	 * @access public
	 * @param string Nazwa klasy lub interfejsu
	 * @return bool
	 * 
	 */	
	public function autoload($className) {	
		$classFileName = $className . '.class.php';
		$interfaceFileName = $className . '.interface.php';	
		if(isset($this->_paths[$classFileName])) {
			require_once($this->_paths[$classFileName]);
			return true;
		}
		if(isset($this->_paths[$interfaceFileName])) {
			require_once($this->_paths[$interfaceFileName]);
			return true;
		}		
		
		$found = false;	
		if(count($this->_directories) == 0) {			
			$dirIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->_projectDir));
			foreach ($dirIterator as $dir) {
				if($classFileName === $dir->getFileName()) {
					require_once($dir->getPathname());
					$found = true;
					break;
				}
				if($interfaceFileName === $dir->getFileName()) {
					require_once($dir->getPathname());
					$found = true;
					break;
				}
			}
			if($found === true) {
				return true;
			}
			//else {
				//throw new AutoLoaderException('Plik klasy lub interfejsu ' . $className . ' nie zostal znaleziony!');
			//}
		}		
		else {
			foreach($this->_directories AS $dir) {
				if(file_exists($dir.$classFileName)) {
					require_once($dir.$classFileName);
					$found = true;
					break;
				}
				if(file_exists($dir.$interfaceFileName)) {
					require_once($dir.$interfaceFileName);
					$found = true;
					break;
				}
			}
			if($found === true) {
				return true;
			}
			//else {
				//throw new AutoLoaderException('Plik klasy lub interfejsu ' . $className . ' nie zostal znaleziony!');
			//}
		}
	}
	
}
