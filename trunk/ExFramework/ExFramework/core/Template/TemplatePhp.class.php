<?php

/**
 * @class TemplatePhp
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class TemplatePhp implements ITemplate {

	/**
	 * Tablica przechowuje dane powiazane z identyfikatorami
	 *
	 * @var array
	 */
	protected $_data = array();
	
	/**
	 * Tablica przechowuje dane konfiguracyjne
	 *
	 * @var array
	 */
	protected $_config = array();
	
	/**
	 * Tablica zawiera dane, ktore beda serializowane i zapisywane w plikach cache
	 *
	 * @var array
	 */	
	protected $_options = array();

	/**
	 * Sciezka do katalogu z szablonami PHP
	 *
	 * @var string
	 */
	protected $_templateDir = '';
	
	/**
	 * Sciezka do katalogu z cache
	 *
	 * @var string
	 */
	protected $_cacheDir = '';

	/**
	 * Sciezka do katalogu z konfiguracja
	 *
	 * @var string
	 */
	protected $_configDir = '';

	/**
	 * Cache wlaczony/wylaczony?
	 *
	 * @var int
	 */
	protected $_caching = 0;
	
	/**
	 * Czas zycia cache, ustawiany globalnie dla wszystkich plikow
	 *
	 * @var int
	 */	
	protected $_cacheLifeTime = 3600;

	/**
	 * Zmienna jesli ustawiona powoduje ze mimo wlaczonego cache'owania szablony sa przetwarzane, dobre ustawienie do testowania
	 *
	 * @var bool
	 */	
	protected $_forceCompile = false;
	
	/**
	 * Ustawienie tej zmiennej powoduje utworzenie grupy plikow cache, 
	 * kolejne grupy tworzy sie przez ustawianie tej zmiennej w wywolaniu fetch lub display
	 * Ustawienie przydatne np. przy tworzeniu sron wielojezycznych, wtedy dla kazdego jezyka inna wartosc
	 *
	 * @var string
	 */	
	protected $_compileId = '';

	/**
	 * Ciag identyfikujacy zakonczenie wiersza opcji w pliku cache
	 *
	 * @var string
	 */	
	protected $_spacer = '';

	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param string Sciezka do katalogu z szablonami
	 * @param string Sciezka do katalogu cache
	 * @param string Sciezka do katalogu z konfiguracja
	 * @param int Wlaczenie/wylaczenie cache
	 * @param int Czas zycia cache
	 * @param bool Wymuszenie cachowania
	 * @param string Identyfikator cache
	 *
	 */	
	public function __construct($templateDir='', $cacheDir='', $configDir='', $caching=0, $cacheLifeTime=null, $forceCompile=false, $compileId='') {
		if(!empty($templateDir)) {
			$this->setTemplateDir($templateDir); //sciezka do katalogu z szablonami
		}
		if(!empty($cacheDir)) {
			$this->setCacheDir($cacheDir); //sciezka do katalogu cache
		}
		if(!empty($configDir)) {
			$this->setConfigDir($configDir); //sciezka do katalogu z plikami konfiguracjnymi
		}
		$this->setCaching($caching); //wlaczenie/wylaczenie cache
		if(!empty($cacheLifeTime)) {
			$this->setCacheLifeTime($cacheLifeTime); //czas zycia cache
		}
		$this->setForceCompile($forceCompile); //wlaczenie/wylaczenie kazdorazowego kompilowania
		if(!empty($compileId)) {
			$this->setCompileId($compileId); //id dla skompilowanych plikow, dla odroznienia np skompilowanych pikow o tych samych nazwach ale z roznych katalogow szablonow
		}
		$this->_spacer = md5('&&&&SFDG#@$%$#sfg#@gddswj090!@$%sd');		
	}
	
	/**
	 * Wyswietla zawartosc pol obiektu
	 * 
	 * @access public
	 * @return string
	 *  
	 */
	public function __toString() {
		$ret = '';
		$ret .= "<b>Template directory: " . $this->_templateDir . "</b> <br />";		
		$ret .= "<b>Config directory: " . $this->_configDir . "</b> <br />";	
		$ret .= "<b>Cache directory: " . $this->_cacheDir . "</b> <br />";
		$ret .= "<b>Caching: " . (int)$this->_caching . "</b> <br />";
		$ret .= "<b>Cache Life Time: " . $this->_cacheLifeTime . "</b> <br />";
		$ret .= "<b>Force Compile: " . (int)$this->_forceCompile . "</b> <br />";
		$ret .= "<b>Compile ID: " . $this->_compileId . "</b> <br />";

		$ret .= "<b>Dane:</b> <br />";
		$ret .= "<pre>";
		$ret .= print_r($this->_data, true);
		$ret .= "</pre>";
		$ret .= "<b>Konfiguracja:</b> <br />";
		$ret .= "<pre>";
		$ret .= print_r($this->_config, true);
		$ret .= "</pre>";
		$ret .= "<b>Opcje:</b> <br />";
		$ret .= "<pre>";
		$ret .= print_r($this->_options, true);
		$ret .= "</pre>";
		return $ret;
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
		if((!is_dir($cacheDir)) || (!is_writable($cacheDir))) {
			throw new TemplateException('Sciezka: ' . $cacheDir . ' nie prowadzi do katalogu lub katalog nie pozwala na zapis');
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
	 * Metoda ustawia sciezke do katalogu z szablonami
	 * 
	 * @access public
	 * @param string Sciezka do katalogu z szablonami
	 * @return void
	 *  
	 */
	public function setTemplateDir($templateDir) {
		if(!is_dir($templateDir)) {
			throw new TemplateException('Sciezka: ' . $templateDir . ' nie prowadzi do katalogu');
		}
		$this->_templateDir = $templateDir;
	}
	
	/**
	 * Metoda zwraca sciezke do katalogu z szablonami
	 * 
	 * @access public
	 * @return string
	 *  
	 */
	public function getTemplateDir() {
		return $this->_templateDir;
	}
	
	/**
	 * Metoda ustawia sciezke do katalogu z konfiguracja
	 * 
	 * @access public
	 * @param string Sciezka do katalogu z konfiguracja
	 * @return void
	 *  
	 */
	public function setConfigDir($configDir) {
		if(!is_dir($configDir)) {
			throw new TemplateException('Sciezka: ' . $configDir . ' nie prowadzi do katalogu');
		}
		$this->_configDir = $configDir;
	}
	
	/**
	 * Metoda zwraca sciezke do katalogu z konfiguracja
	 * 
	 * @access public
	 * @return string
	 *  
	 */
	public function getConfigDir() {
		return $this->_configDir;
	}
	
	/**
	 * Metoda ustawia globalny identyfikator dla grupy szablonow
	 * 
	 * @access public
	 * @param string Identyfikator dla grupy szablonow
	 * @return void
	 *  
	 */
	public function setCompileId($compileId) {
		$this->_compileId = (string)$compileId;
	}
	
	/**
	 * Metoda zwraca globalny identyfikator dla grupy szablonow
	 * 
	 * @access public
	 * @return string
	 *  
	 */
	public function getCompileId() {
		return $this->_compileId;
	}	
	
	/**
	 * Metoda ustawia globalny czas życia cache
	 * 
	 * @access public
	 * @param int Czas zycia cache
	 * @return void
	 *  
	 */
	public function setCacheLifeTime($cacheLifeTime) {
		$this->_cacheLifeTime = (int)$cacheLifeTime;
	}
	
	/**
	 * Metoda zwraca globalna wartosc czasu zycia szablonow
	 * 
	 * @access public
	 * @return int
	 *  
	 */
	public function getCacheLifeTime() {
		return $this->_cacheLifeTime; 
	}
	
	/**
	 * Metoda wlacza lub wylacza cache'owanie
	 * 
	 * @access public
	 * @param int Wlaczenie/wylaczenie cache'owania
	 * @return void
	 *  
	 */
	public function setCaching($caching=0) {
		$this->_caching = (int)$caching;
	}
	
	/**
	 * Metoda zwraca czy cache jest wlaczony czy wylaczony
	 * 
	 * @access public
	 * @return int
	 *  
	 */
	public function getCaching() {
		return $this->_caching;
	}
	
	/**
	 * Metoda ustawia czy pomimo wlaczonego cache'owania prztwarzac szablony
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie wymuszenia przetwarzania szablonow
	 * @return void
	 *  
	 */
	public function setForceCompile($forceCompile) {
		$this->_forceCompile = (bool)$forceCompile;
	}
	
	/**
	 * Metoda zwraca czy ustawiono wymuszanie przetwarzania szablonow
	 * 
	 * @access public
	 * @return bool
	 *  
	 */
	public function getForceCompile() {
		return $this->_forceCompile;
	}
	
	/**
	 * Metoda magiczna pozwalajaca pobrac wartosc, ktora bedzie wyswietlana w szablonie, poprzez jej identyfikator
	 * 
	 * @access public
	 * @param string Identyfikator zmiennej
	 * @return mixed
	 *  
	 */
	public function __get($var) {
		if(isset($this->_data[$var])) {
			return $this->_data[$var];
		}
		return null;
	}
	
	/**
	 * Metoda magiczna pozwalajaca powiazac wartosc, ktora bedzie wyswietlana w szablonie z jej identyfikatorem
	 * 
	 * @access public
	 * @param mixed Identyfikator zmiennej
	 * @param mixed Wartosc zmiennej
	 * @return void
	 *  
	 */
	public function __set($var, $value) {
		$this->assign($var, $value);
	}

	/** Metoda magiczna, sprawdza czy wywolana nieistniejaca metoda jest w klasie macierzystej i wywoluje ja
	 * 
	 * @access public
	 * @param string Nazwa metody
	 * @param array Argumenty metody
	 * @return void
	 *  
	 */
	public function __call($method, $args) {
		if(method_exists($this, $method)) {
			return call_user_func_array(array($this, $method), $args);
		}
		else {
			throw new TemplateException('Metoda: ' . $method . ' nie istnieje w klasie');			
		}
	}
	
	/**
	 * Metoda pozwala dolaczyc w obiekcie dane do istniejacych identyfikatorow
	 * 
	 * @access public
	 * @param mixed Identyfikator zmiennej lub tablica wartosci
	 * @param mixed Wartosc lub tablica wartosci
	 * @param bool Czy polaczyc z tablica, w takim wypadku istniejace wartosci beda zastepowane nowymi
	 * @return void
	 * @todo Sprawdzic ta metode
	 * 
	 */
	public function append($var, $value=null, $merge=false) {
		if(is_array($var)) {
			foreach($var as $k => $v) {
				if($k != '') {
					if(!is_array($this->_data[$k])) {
						$this->_data[$k] = array();
					}
					if($merge && is_array($value)) {
						foreach($value as $kl => $vl) {
							$this->_data[$k][$kl] = $vl;
						}
					}
					else {
						$this->_data[$k][] = $v;
					}
				}
			}
		}
		else {
			if($var != '' && isset($value)) {
				if(!is_array($this->_data[$var])) {
					$this->_data[$var] = array();
				}
				if($merge && is_array($value)) {
					foreach($value as $kl => $vl) {
						$this->_data[$var][$kl] = $vl;
					}
				}
				else {
					$this->_data[$var][] = $value;
				}
			}		
		}
	}
	
	/**
	 * Metoda pozwala dolaczyc w obiekcie dane do istniejacych identyfikatorow przez referencje
	 * 
	 * @access public
	 * @param string Identyfikator zmiennej
	 * @param mixed Wartosc lub tablica wartosci
	 * @param bool  Czy polaczyc z tablica, w takim wypadku istniejace wartosci beda zastepowane nowymi
	 * @return void
	 * @todo Sprawdzic ta metode
	 * 
	 */
	public function appendByRef($var, &$value=null, $merge=false) {
		if($var != '' && isset($value)) {
			if(!is_array($this->_data[$var])) {
				$this->_data[$var] = array();
			}
			if($merge && is_array($value)) {
				foreach($value as $kl => $vl) {
					$this->_data[$var][$kl] = &$value[$kl];
				}
			}
			else {
				$this->_data[$var][] = &$value;
			}
		}	
	}
	
	/**
	 * Metoda pozwala dolaczyc do obiektu dane, ktore beda wyswietlane w szablonie i powiazac je z identyfikatorami
	 * 
	 * @access public
	 * @param mixed Identyfikator lub tablica
	 * @param mixed $value
	 * @return void
	 * @todo Sprawdzic ta metode
	 * 
	 */
	public function assign($var, $value=null) {
		if(is_array($var)) {
			foreach($var as $k => $v) {
				if($k != '') {
					$this->_data[$k] = $v;
				}
			}
		}
		else {
			if($var != '') {
				$this->_data[$var] = $value;
			}
		}
	}
	
	/**
	 * Metoda pozwala dolaczyc do obiektu dane (przez referencje), ktore beda wyswietlane w szablonie i powiazac je z identyfikatorami
	 * 
	 * @access public
	 * @param mixed $var
	 * @param mixed $value
	 * @return void
	 * @todo Sprawdzic ta metode
	 * 
	 */
	public function assignByRef($var, &$value) {
		if($var != '') {
			$this->_data[$var] = &$value;
		}		
	}
	
	/**
	 * Metoda pozwala usunac z obiektu wszystkie dane, ktore byly wyswietlane w szablonie
	 * 
	 * @access public
	 * @return void
	 * 
	 */
	public function clearAllAssign() {
		foreach($this->_data as $k => $v) {
			unset($this->_data[$k]);
		}
	}
	
	/**
	 * @Metoda pozwala usunac pliki cache, wszystkie lub te ktore sa starsze niz $expireTime
	 * 
	 * @access public
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */
	public function clearAllCache($expireTime=null) {
		if(($this->_cacheDir == '') || (!is_dir($this->_cacheDir)) || (!is_writable($this->_cacheDir))) {
			throw new TemplateException('Sciezka: ' . $this->_cacheDir . ' nie prowadzi do katalogu lub nie ma mozliwosci zapisu do katalogu');
		}
		if(substr($this->_cacheDir, -1, 1) != '/') {
			$this->_cacheDir = $this->_cacheDir . '/';
		}	
		$dp = dir($this->_cacheDir);
		$time = time();
		while(($file = $dp->read()) != false) {
			if($file != '.' && $file != '..') {
				if($expireTime != null) {
					if(filemtime($this->_cacheDir . $file) < ($time - $expireTime)) {
						unlink($file);
					}
				}
				else {
					unlink($this->_cacheDir . $file);
				}
			}
		}
		$dp->close();
	}
	
	/**
	 * Metoda pozwala usunac z obiektu dane, ktore byly wyswietlane w szablonie, identyfikowane przez identyfikator $var
	 * 
	 * @access public
	 * @param string|array Identyfikator lub tablica
	 * @return void
	 * 
	 */
	public function clearAssign($var) {
		if(is_array($var)) {
			foreach($var as $k=>$v) {
				if(isset($this->_data[$k])) {
					unset($this->_data[$k]);
				}
			}
		}
		else {
			if(isset($this->_data[$var])) {
				unset($this->_data[$var]);
			}			
		}
	}
	
	/**
	 * Metoda pozwala usunac plik cache, o identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, starsze niz $expireTime
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */
	public function clearCache($template, $cacheId=null, $compileId=null, $expireTime=null) {
		$cacheFilePath = $this->makeCacheFilePath($template, $cacheId, $compileId);
		if(file_exists($cacheFilePath)) {
			if($expireTime !== null) {
				$time = time();
				if(filemtime($cacheFilePath) < ($time - $expireTime)) {
					unlink($cacheFilePath);
				}
			}
			else {
				unlink($cacheFilePath);
			}
		}
	}
	
	/**
	 * Metoda dla zgodnosci z interfejsem
	 * 
	 * @access public
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */
	public function clearCompiledTpl($templateFile=null, $compileId=null, $expireTime=null) {
		return;
	}
	
	/**
	 * Metoda pozwala usunac z obiektu dane konfiguracyjne, wszystkie lub identyfikowane przez identyfikator $var
	 * 
	 * @access public
	 * @param mixed Identyfikator zmiennej konfiguracyjnej
	 * @return void
	 * 
	 */
	public function clearConfig($var=null) {
		if($var === null) {
			foreach($this->_config as $k => $v) {
				unset($this->_config[$k]);
			}
		}
		else {
			if(isset($this->_config[$var])) {
				unset($this->_config[$var]); 
			}
		}		
	}
	
	/**
	 * Metoda pozwala zaladowac do tablicy z konfiguracja dane z pliku konfiguracyjnego, w postaci tablicy o nazwie config, lub jej czesc
	 * Dane te beda wykorzystywane w szablonie
	 * 
	 * @access public
	 * @param string Sceizka do pliku konfiguracyjnego
	 * @param string Nazwa sekcji
	 * @return void
	 * 
	 */
	public function configLoad($configFile, $section=null) {
		$configFile = rtrim($this->_configDir, '/') . '/' . $configFile;
		if((!file_exists($configFile)) || (!is_readable($configFile)) || (!is_dir($this->_configDir))) {
			throw new TemplateException('Plik konfiguracyjny: ' . $configFile . ' nie istnieje lub jest nieczytelny');
		}
		require_once($configFile);
		if(isset($config) && is_array($config)) {
			if($section === null) {
				$this->_config = array_merge($this->_config, $config);
				unset($config);		
			}
			else {
				foreach($config as $k => $v) {
					if($k == $section) {
						$this->_config[$k] = $v;
					}
				}
				unset($config);
			}
		}
	}
	
	/**
	 * Metoda wyswietla przetworzony szablon, identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, gdzie $lifeTime to czas cache'owania danego szablonu
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu lub sciezka do niego
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */
	public function display($template, $cacheId=null, $compileId=null, $lifeTime=null) {
		$this->fetch($template, $cacheId, $compileId, true, $lifeTime);
	}
	
	/**
	 * Metoda zwraca przetworzony szablon, identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, gdzie $lifeTime to czas cache'owania danego szablonu
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu lub sciezka do niego
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param bool Czy wyswietlic przetworzony szablon?
	 * @param int Czas zycia cache
	 * @return string
	 * 
	 */
	public function fetch($template, $cacheId=null, $compileId=null, $display=false, $lifeTime=null) {
		$this->_options['lifeTime'] = ($lifeTime !== null) ? (int) $lifeTime : $this->_cacheLifeTime;	
		$output = null;
		if(($this->_caching > 0) && ($this->isCached($template, $cacheId, $compileId, $lifeTime)) && ($this->_forceCompile === false)) {
			$output = $this->getCache($template, $cacheId, $compileId);
		}
		else {
			$output = $this->getOutput($template, $cacheId, $compileId);
			if($this->_caching > 0) {
				$this->addCache($output, $template, $cacheId, $compileId, $lifeTime);
			}
		}		
		if($display === true) {
			echo $output;
		}
		return $output;	
	}
	
	/**
	 * Metoda pozwalajaca pobrac wartosc z konfiguracji, ktora bedzie wyswietlana w szablonie, poprzez jej identyfikator, lub wszystkie wartosci
	 * 
	 * @access public
	 * @param string Identyfikator zmiennej konfiguracyjnej
	 * @return mixed
	 *  
	 */
	public function getConfigVars($varname=null) {
		if($varname === null) {
			return $this->_config;
		}
		else {
			return $this->searchInArray($varname,$this->_config);
		}		
	}
	
	/**
	 * Metoda pozwalajaca pobrac wartosc z danych, ktore beda wyswietlane w szablonie, poprzez jej identyfikator, lub wszystkie wartosci
	 * 
	 * @access public
	 * @param string Identyfikator zmiennej szablonu
	 * @return mixed
	 *  
	 */
	public function getTemplateVars($varname=null) {
		if($varname === null) {
			return $this->_data;
		}
		else {
			return $this->searchInArray($varname,$this->_data);
		}
	}
	
	/**
	 * Metoda sprawdza czy istnieje przetworzony szablon w cache o identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, gdzie $lifeTime to czas cache'owania danego szablonu
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu lub sciezka do niego
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache, moze byc cokolwiek, wtedy czasa zycia bedzie sprawdzany indywidualnie w pliku
	 * @return bool
	 * 
	 */
	public function isCached($template, $cacheId=null, $compileId=null, $lifeTime=null) {
		$cacheFilePath = $this->makeCacheFilePath($template, $cacheId, $compileId);	
		if(file_exists($cacheFilePath)) {
			if($lifeTime !== null) {
				$content = null;
				if(!($fp = fopen($cacheFilePath, 'r'))) {
					throw new TemplateException('Nie mozna otworzyc pliku: ' . $cacheFilePath . ' do odczytu');
				}
				if(!$content = fread($fp, filesize($cacheFilePath))) {
					throw new TemplateException('Nie mozna odczytac zawartosci pliku: ' . $cacheFilePath);
				}
				fclose($fp);
				$spacer = $this->_spacer;
				preg_match("/(.*?)$spacer\n/i", $content, $matches);
				$options = trim($matches[0]);
				if($options == '') {
					return false;
				}
				$options = unserialize($options);
				$_lifeTime = $options['lifeTime'];
				unset($content);
				$time = time();
				if(filemtime($cacheFilePath) > ($time - $_lifeTime)) {
					return true;
				}
				else {
					return false;
				}
			}
			if(filemtime($cacheFilePath) > (time() - $this->_cacheLifeTime)) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Metoda sprawdza czy dany szablon istnieje na dysku
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu lub sciezka do niego 
	 * @return bool
	 * 
	 */
	public function templateExists($template) {
		if($this->getTemplatePath($template) !== null) {
			return true;
		}
		return false;
	}
	
	/**
	 * Metoda wyswietla przetworzony szablon, identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, gdzie $lifeTime to czas cache'owania danego szablonu
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu lub sciezka do niego
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int czas zycia cache
	 * @return void
	 * 
	 */
	public function render($template, $cacheId=null, $compileId=null, $lifeTime=null) {
		$this->display($template, $cacheId, $compileId, $lifeTime);
	}
	
	/**
	 * Metoda przeszukuje rekurencyjnie tablice w poszukiwaniu danej wartosci
	 * 
	 * @access protected
	 * @param string Identyfikator
	 * @param array Przeszukiwana tablica
	 * @return mixed
	 * 
	 */
	protected function searchInArray($index, Array $arr) {
		foreach($arr as $key =>  $value) {
			if($key == $index) {
				return $value;
			}
			if(is_array($value)) {
				return $this->searchInArray($index, $value);
			}
		}
		return null;
	}	
	
	/**
	 * Metoda zwraca ciag bedacy sciezka do pliku szablonu
	 * 
	 * @access protected
	 * @param string Nazwa pliku szablonu lub sciezka do niego
	 * @return string|null
	 * 
	 */
	protected function getTemplatePath($template) {
		$filePath = null;
		if(strstr($template, "/")) {
			$filePath = $template;
			if(preg_match('|file:|', $filePath)) {
				$filePath = str_replace('file:', '', $filePath);
			}
		}
		else {
			if(($this->_templateDir == '') || (!is_dir($this->_templateDir))) {
				throw new TemplateException('Nie ustawiono katalogu z szablonami, lub sciezka ' . $this->_templateDir . ' nie prowadzi do katalogu');
			}
			if(substr($this->_templateDir, -1, 1) != '/') {
				$this->_templateDir = $this->_templateDir . '/';
			}
			$filePath = $this->_templateDir . $template;
		}
		if(file_exists($filePath)) {
			return $filePath;
		}
		return null;
	}	
	
	/**
	 * Metoda tworzy ciag bedacy sciezka do pliku cache o identyfikatorze $cacheId z grupy $compileId
	 * 
	 * @access protected
	 * @param string Nazwa pliku szablonu lub sciezka do niego
	 * @param string Id cache
	 * @param string Id grupy szablonow 
	 * @return string
	 * 
	 */
	protected function makeCacheFilePath($template, $cacheId=null, $compileId=null) {
		if((!is_dir($this->_cacheDir)) || (!is_writable($this->_cacheDir))) {
			throw new TemplateException('Sciezka: ' . $this->_cacheDir . ' nie prowadzi do katalogu lub katalog nie umozliwia zapisu');
		}
		$templateFilePath = $this->getTemplatePath($template);
		$templateFileName = basename($templateFilePath);
		$id = $this->cacheId($templateFileName, $cacheId);
		$cid = $this->compileId($compileId);
		if(substr($this->_cacheDir, -1, 1) != '/') {
			$this->_cacheDir = $this->_cacheDir . '/';
		}	
		$cacheFilePath = $this->_cacheDir . 'cache_' . $cid . '_' . $id . '_' . $templateFileName . '.cache';
		return $cacheFilePath; 	
	}
	
	/**
	 * Metoda na podstawie podanego identyfikatora pliku cache i nazwy pliku szablonu tworzy ostateczny identyfikator pliku cache
	 * 
	 * @access protected
	 * @param string Nazwa pliku szablonu
	 * @param string Id cache
	 * @return string
	 * 
	 */
	protected function cacheId($templateFileName, $cacheId=null) {
		return isset($cacheId) ? md5($templateFileName.$cacheId) : md5($templateFileName);
	}
	
	/**
	 * Metoda na podstawie podanego identyfikatora grupy tworzy ostateczny identyfikator pliku cache
	 * 
	 * @access protected
	 * @param string Id grupy szablonow
	 * @return string
	 * 
	 */
	protected function compileId($compileId=null) {
		return isset($compileId) ? md5($this->_templateDir.$compileId) : md5($this->_templateDir.$this->_compileId);
	}
	
	/**
	 * Metoda pobiera szablon, przetwarza go i zwraca ciag o identyfikatorze $cacheId i id grupy $compileId
	 * 
	 * @access protected
	 * @param string Nazwa pliku szablonu lub sciezka do niego
	 * @param string Id cache
	 * @param string Id grupy szablonow 
	 * @return string
	 * 
	 */
	protected function getOutput($template, $cacheId=null, $compileId=null) {
		$output = '';
		$filePath = $this->getTemplatePath($template);
		if(($filePath !== null) && file_exists($filePath)) {
			ob_start();
			include_once($filePath);
			$output = ob_get_contents();
			ob_end_clean();
		}
		else {
			throw new TemplateException('Plik szablonu: ' . $filePath . '(' . $template . ')'. ' nie istnieje');
		}
		return $output;
	}
	
	/**
	 * Metoda pobiera ciag z przetworzonym szablonem o identyfikatorze $cacheId i id grupy $compileId i zapisuje do pliku cache, wlacznie z czasem zycia dla tego pliku
	 * 
	 * @access protected
	 * @param string Dane w postaci ciagu
	 * @param string Nazwa pliku szablonu lub sciezka do niego
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */
	protected function addCache($output, $template, $cacheId=null, $compileId=null, $lifeTime=null) {
		$cacheFilePath = $this->makeCacheFilePath($template, $cacheId, $compileId);
		touch($cacheFilePath);
		chmod($cacheFilePath, 0666);	
		if(!($fp = fopen($cacheFilePath,'w'))) {
			throw new TemplateException('Nie mozna otworzyc pliku: ' . $cacheFilePath . ' do zapisu');
		}
		$output = serialize($this->_options) . $this->_spacer . "\n" . $output;
		if(!fwrite($fp, $output)) {
			throw new TemplateException('Nie mozna zapisac pliku cache: ' . $cacheFilePath);
		}
		fclose($fp);
	}
	
	/**
	 * Metoda pobiera przetworzony szablon z cache o identyfikatorze $cacheId i id grupy $compileId i zwraca ciag wyjsciowy
	 * 
	 * @access protected
	 * @param string Nazwa pliku szablonu lub sciezka do niego
	 * @param string Id cache
	 * @param string Id grupy szablonow 
	 * @return string
	 * 
	 */
	protected function getCache($template, $cacheId=null, $compileId=null) {
		$cacheFilePath = $this->makeCacheFilePath($template, $cacheId, $compileId);	
		$content = '';
		if(!($fp = fopen($cacheFilePath, 'r'))) {
			throw new TemplateException('Nie mozna otworzyc pliku: ' . $cacheFilePath . ' do odczytu');
		}
		if(!$content = fread($fp, filesize($cacheFilePath))) {
			throw new TemplateException('Nie mozna czytac z pliku: ' . $cacheFilePath);
		}
		fclose($fp);
		$content = preg_replace("/^(.*" . $this->_spacer ."\n)/i", "", $content);
		return $content;
	}
	
}

?>
