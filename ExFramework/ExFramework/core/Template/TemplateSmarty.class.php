<?php

/**
 * @class TemplateSmarty
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class TemplateSmarty extends Smarty implements ITemplate {
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param string Katalog szablonow
	 * @param string Katalog cache
	 * @param string Katalog z prekompilowanymi plikami PHP
	 * @param string Katalog z konfiguracja
	 * @param int Wlaczenie/wylaczenie cache
	 * @param bool Wlaczenie dodatkowej ochrony
	 * @param int Czas zycia cache
	 * @param bool Wlaczenie/wylaczenie debugowania
	 * @param bool Wlaczenie/wylaczenie wymuszenia kompilacji szablonow podczas kazdego uruchomienia
	 * @param string Identyfikator cache
	 *
	 */
	public function __construct($templateDir='', $cacheDir='', $compileDir='', $configDir='', $caching=0, $security=false, $cacheLifeTime=null, $debugging=false, $forceCompile=false, $compileId='') {
		parent::Smarty();
		if(!empty($templateDir)) {
			$this->setTemplateDir($templateDir); //sciezka do katalogu z szablonami
		}
		if(!empty($cacheDir)) {
			$this->setCacheDir($cacheDir); //sciezka do katalogu cache
		}
		if(!empty($compileDir)) {
			$this->setCompileDir($compileDir); //sciezka do katalogu ze skompilowanymi plikami PHP
		}
		if(!empty($configDir)) {
			$this->setConfigDir($configDir); //sciezka do katalogu z plikami konfiguracjnymi
		}
		$this->setCaching($caching); //wlaczenie/wylaczenie cache
		$this->setSecurity($security); //wlaczenie/wylaczenie dodatkowej ochrony
		if(!empty($cacheLifeTime)) {
			$this->setCacheLifeTime($cacheLifeTime); //czas zycia cache
		}
		$this->setDebugging($debugging); //wlaczenie/wylaczenie debugowania
		$this->setForceCompile($forceCompile); //wlaczenie/wylaczenie kazdorazowego kompilowania
		if(!empty($compileId)) {
			$this->setCompileId($compileId); //id dla skompilowanych plikow, dla odroznienia np skompilowanych pikow o tych samych nazwach ale z roznych katalogow szablonow
		}
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
		$ret .= "<b>Template directory: " . $this->template_dir . "</b> <br />";		
		$ret .= "<b>Config directory: " . $this->config_dir . "</b> <br />";	
		$ret .= "<b>Cache directory: " . $this->cache_dir . "</b> <br />";
		$ret .= "<b>Caching: " . (int)$this->caching . "</b> <br />";
		$ret .= "<b>Cache Life Time: " . $this->cache_lifetime . "</b> <br />";
		$ret .= "<b>Force Compile: " . (int)$this->force_compile . "</b> <br />";
		$ret .= "<b>Compile ID: " . $this->compile_id . "</b> <br />";

		$ret .= "<b>Dane:</b> <br />";
		$ret .= "<pre>";
		$data = $this->getTemplateVars();
		if(!is_array($data)) {
			$data = array($data);
		}
		$ret .= print_r($data, true);
		$ret .= "</pre>";
		$ret .= "<b>Konfiguracja:</b> <br />";
		$ret .= "<pre>";
		$conf = $this->getConfigVars();
		if(!is_array($conf)) {
			$conf = array($conf);
		}		
		$ret .= print_r($conf, true);
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
			throw new TemplateException('Podana sciezka: ' . $cacheDir . ' nie prowadzi do katalogu lub katalog nie pozwala na zapis');
		}
		$this->cache_dir = $cacheDir;
	}
	
	/**
	 * Metoda zwraca sciezke do katalogu cache
	 * 
	 * @access public
	 * @return string
	 *  
	 */
	public function getCacheDir() {
		return $this->cache_dir;
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
		$this->template_dir = $templateDir;
	}
	
	/**
	 * Metoda zwraca sciezke do katalogu z szablonami
	 * 
	 * @access public
	 * @return string
	 *  
	 */
	public function getTemplateDir() {
		return $this->template_dir;
	}
	
	/**
	 * Metoda ustawia sciezke do katalogu z przetworzonymi szablonami 
	 * 
	 * @access public
	 * @param string Sciezka do katalogu z przetworzonymi szablonami
	 * @return void
	 *  
	 */
	public function setCompileDir($compileDir) {
		if(!is_dir($compileDir)) {
			throw new TemplateException('Sciezka: ' . $compileDir . ' nie prowadzi do katalogu');
		}
		$this->compile_dir = $compileDir;
	}
	
	/**
	 * Metoda zwraca sciezke do katalogu z przetworzonymi szablonami
	 * 
	 * @access public
	 * @return string
	 *  
	 */
	public function getCompileDir() {
		return $this->compile_dir;
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
		$this->config_dir = $configDir;
	}
	
	/**
	 * Metoda zwraca sciezke do katalogu z konfiguracja
	 * 
	 * @access public
	 * @return string
	 *  
	 */
	public function getConfigDir() {
		return $this->config_dir;
	}
	
	/**
	 * Metoda ustawia sciezke do katalogu z pluginami
	 * 
	 * @access public
	 * @param string Sciezka do katalogu z pluginami
	 * @return void
	 *  
	 */
	public function setPluginsDir($pluginsDir) {
		if(!is_dir($pluginsDir)) {
			throw new TemplateException('Sciezka: ' . $pluginsDir . ' nie prowadzi do katalogu');
		}
		$this->plugins_dir = $pluginsDir;
	}
	
	/**
	 * Metoda zwraca sciezke do katalogu z pluginami
	 * 
	 * @access public
	 * @return string
	 *  
	 */
	public function getPluginsDir() {
		return $this->plugins_dir;
	}
	
	/**
	 * Metoda ustawia globalny identyfikator dla grupy szblonow
	 * 
	 * @access public
	 * @param string Identyfikator dla grupy szablonow
	 * @return void
	 *  
	 */
	public function setCompileId($compileId) {
		$this->compile_id = (string)$compileId;
	}
	
	/**
	 * Metoda zwraca globalny identyfikator dla grupy szablonow
	 * 
	 * @access public
	 * @return string
	 *  
	 */
	public function getCompileId() {
		return $this->compile_id;
	}	

	/**
	 * Metoda wlacza lub wylacza mozliwosc wstawiania kodu PHP w szablonach
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie kodu PHP w szablonach
	 * @return void
	 *  
	 */
	public function setSecurity($secure) {
		$this->security = (bool)$secure;
	}
	
	/**
	 * Metoda zwraca wartosc security
	 * 
	 * @access public
	 * @return bool
	 *  
	 */
	public function getSecurity() {
		return $this->security; 
	}
	
	/**
	 * Metoda ustawia czas życia cache
	 * 
	 * @access public
	 * @param int Czas zycia cache
	 * @return void
	 *  
	 */
	public function setCacheLifeTime($cacheLifeTime) {
		$this->cache_lifetime = (int)$cacheLifeTime;
	}
	
	/**
	 * Metoda zwraca wartosc czasu zycia szablonow
	 * 
	 * @access public
	 * @return int
	 *  
	 */
	public function getCacheLifeTime() {
		return $this->cache_lifetime; 
	}
	
	/**
	 * Metoda wlacza lub wylacza cache'owanie
	 * 
	 * @access public
	 * @param int Wlaczenie/wylaczenie cachowania
	 * @return void
	 *  
	 */
	public function setCaching($cache=0) {
		$this->caching = (int)$cache;
	}
	
	/**
	 * Metoda zwraca czy cache jest wlaczony czy wylaczony
	 * 
	 * @access public
	 * @return int
	 *  
	 */
	public function getCaching() {
		return $this->caching;
	}

	/**
	 * Metoda wlacza lub wylacza debugowanie
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie debugowania
	 * @return void
	 *  
	 */
	public function setDebugging($debug) {
		$this->debugging = (bool)$debug;
	}
	
	/**
	 * Metoda zwraca czy ustawiono debugowanie
	 * 
	 * @access public
	 * @return bool
	 *  
	 */
	public function getDebugging() {
		return $this->debugging;
	}
	
	/**
	 * Metoda ustawia czy pomimo wlaczonego cache'owania przetwarzac szablony
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie wymuszania przetwarzania szablonow
	 * @return void
	 *  
	 */
	public function setForceCompile($forceCompile) {
		$this->force_compile = (bool)$forceCompile;
	}
	
	/**
	 * Metoda zwraca czy ustawiono wymuszanie przetwarzania szablonow
	 * 
	 * @access public
	 * @return bool
	 *  
	 */
	public function getForceCompile() {
		return $this->force_compile;
	}
	
	/**
	 * Metoda magiczna, sprawdza czy wywolana nieistniejaca metoda jest w klasie macierzystej i wywoluje ja
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
	 * @param string|array Identyfikator lub tablica wartosci
	 * @param mixed Wartosc lub tablica wartosci
	 * @param bool Wlaczenie polaczenia z istniejacym identyfikatorem
	 * @return void
	 * 
	 */	
	public function append($var, $value=null, $merge=false) {
		parent::append($var, $value, $merge);
	}
	
	/**
	 * Metoda pozwala dolaczyc w obiekcie dane do istniejacych identyfikatorow przez referencje
	 * 
	 * @access public
	 * @param string Identyfikator
	 * @param mixed Wartosc lub tablica wartosci
	 * @param bool Wlaczenie polaczenia z istniejacym identyfikatorem
	 * @return void
	 * 
	 */	
	public function appendByRef($var, &$value=null, $merge=false) {
		parent::append_by_ref($var, $value, $merge);
	}
	
	/**
	 * Metoda pozwala dolaczyc do obiektu dane, ktore beda wyswietlane w szablonie i powiazac je z identyfikatorami
	 * 
	 * @access public
	 * @param string|array Indentyfikator lub tablica
	 * @param mixed Wartosc
	 * @return void
	 * 
	 */	
	public function assign($var, $value) {
		parent::assign($var, $value);
	}
	
	/**
	 * Metoda pozwala dolaczyc do obiektu dane (przez referencje), ktore beda wyswietlane w szablonie i powiazac je z identyfikatorami
	 * 
	 * @access public
	 * @param string Identyfikator
	 * @param mixed Wartosc
	 * @return void
	 * 
	 */	
	public function assignByRef($var, &$value) {
		parent::assign_by_ref($var, $value);		
	}
	
	/**
	 * Metoda pozwala usunac z obiektu wszystkie dane, ktore byly wyswietlane w szablonie
	 * 
	 * @access public
	 * @return void
	 * 
	 */
	public function clearAllAssign() {
		parent::clear_all_assign();	
	}
	
	/**
	 * Metoda pozwala usunac pliki cache, wszystkie lub te ktore sa starsze niz $expireTime
	 * 
	 * @access public
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */
	public function clearAllCache($expireTime=null) {
		parent::clear_all_cache($expireTime);	
	}
	
	/**
	 * Metoda pozwala usunac z obiektu dane, ktore byly wyswietlane w szablonie, identyfikowane przez identyfikator $var
	 * 
	 * @access public
	 * @param mixed Identyfikator danej
	 * @return void
	 * 
	 */
	public function clearAssign($var) {
		parent::clear_assign($var);	
	}
	
	/**
	 * Metoda pozwala usunac plik cache, o identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, starsze niz $expireTime
	 * 
	 * @access public
	 * @param string Nazwa pliku
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */	
	public function clearCache($templateFile, $cacheId=null, $compileId=null, $expireTime=null) {
		parent::clear_cache($templateFile, $cacheId, $compileId, $expireTime);			
	}
	
	/**
	 * Metoda pozwala usunac 'skompilowane' do kodu PHP szablony z katalogu compile_dir 
	 * 
	 * @access public
	 * @param string Nazwa pliku
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */	
	public function clearCompiledTpl($templateFile=null, $compileId=null, $expireTime=null) {
		parent::clear_compiled_tpl($templateFile, $compileId, $expireTime);					
	}
	
	/**
	 * Metoda pozwala usunac z obiektu dane konfiguracyjne, wszystkie lub identyfikowane przez identyfikator $var
	 * 
	 * @access public
	 * @param String Identyfikator
	 * @return void
	 * 
	 */	
	public function clearConfig($var=null) {
		parent::clear_config($var);
	}
	
	/**
	 * Metoda pozwala zaladowac do tablicy z konfiguracja dane z pliku konfiguracyjnego, w postaci tablicy o nazwie config, lub jej czesc
	 * Dane te beda wykorzystywane w szablonie
	 * 
	 * @access public
	 * @param string Nazwa pliku konfiguracyjnego
	 * @param string nazwa sekcji
	 * @return void
	 * 
	 */	
	public function configLoad($configFile, $section=null) {
		parent::config_load($configFile, $section);		
	}
	
	/**
	 * Metoda wyswietla przetworzony szablon, identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, gdzie $lifeTime to czas cache'owania danego szablonu
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */	
	public function display($template, $cacheId=null, $compileId=null, $lifeTime=null) {
		if($lifeTime !== null) {
			$oldCacheLifeTime = $this->cache_lifetime;			
			$this->setCaching(2);
			$this->setCacheLifeTime($lifeTime);
		}
		parent::display($template, $cacheId, $compileId);	
		if($lifeTime !== null) {
			$this->setCacheLifeTime($oldCacheLifeTime);
			$this->setCaching(1);
		}						
	}
	
	/**
	 * Metoda zwraca przetworzony szablon, identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, gdzie $lifeTime to czas cache'owania danego szablonu
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param bool Wlaczenie/wylaczenie wyswietlania
	 * @param int Czas zycia cache 
	 * @return string
	 * 
	 */	
	public function fetch($template, $cacheId=null, $compileId=null, $display=false, $lifeTime=null) {
		if($lifeTime !== null) {
			$oldCacheLifeTime = $this->cache_lifetime;
			$this->setCaching(2);
			$this->setCacheLifeTime($lifeTime);
		}		
		$result = parent::fetch($template, $cacheId, $compileId, $display);
		if($lifeTime !== null) {
			$this->setCacheLifeTime($oldCacheLifeTime);
			$this->setCaching(1);
		}
		return $result;
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
		return parent::get_config_vars($varname);			
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
		return parent::get_template_vars($varname);			
	}
	
	/**
	 * Metoda sprawdza czy istnieje przetworzony szablon w cache o identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, gdzie $lifeTime to czas cache'owania danego szablonu
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache
	 * @return bool
	 * 
	 */	
	public function isCached($template, $cacheId=null, $compileId=null, $lifeTime=null) {
		if($lifeTime !== null) {
			$this->setCaching(2);
			$this->setCacheLifeTime($lifeTime);
		}
		return parent::is_cached($template, $cacheId, $compileId);		
	}
	
	/**
	 * Metoda sprawdza czy dany szablon istnieje na dysku
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu
	 * @return bool
	 * 
	 */	
	public function templateExists($template) {
		return parent::template_exists($template);		
	}
	
	/**
	 * Metoda wyswietla przetworzony szablon, identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, gdzie $lifeTime to czas cache'owania danego szablonu
	 * 
	 * @access public
	 * @param Nazwa pliku szablonu
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */	
	public function render($template, $cacheId=null, $compileId=null, $lifeTime=null) {
		$this->display($template, $cacheId, $compileId);
	}
	
}

?>
