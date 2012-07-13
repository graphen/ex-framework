<?php

/**
 * @class Request
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Request implements IRequest {
	
	/**
	 * Instancja obiektu przechowujacego tablice $_COOKIE
	 *
	 * @var object
	 * 
	 */	
	protected $_cookie = null;
	
	/**
	 * Instancja obiektu przechowujacego tablice $_GET
	 *
	 * @var object
	 * 
	 */	
	protected $_get = null;
	
	/**
	 * Instancja obiektu przechowujacego tablice $_POST
	 *
	 * @var object
	 * 
	 */	
	protected $_post = null;
	
	/**
	 * Instancja obiektu przechowujacego tablice $_SERVER 
	 *
	 * @var object
	 * 
	 */	
	protected $_server = null;

	/**
	 * Instancja obiektu przechowujacego tablice $_ENV 
	 *
	 * @var object
	 * 
	 */	
	protected $_env = null;
	
	/*
	 * URL strony
	 * 
	 * @var string
	 * 
	 */
	protected $_baseUrl =null;
	
	/**
	 * Konstruktor
	 * 
	 * @access private
	 * @param object Obiekt zarzadzajacy tablica $_COOKIE
	 * @param object Obiekt zarzadzajacy tablica $_GET
	 * @param object Obiekt zarzadzajacy tablica $_POST
	 * @param object Obiekt zarzadzajacy tablica $_SERVER
	 * @param object Obiekt zarzadzajacy tablica $_ENV
	 *  
	 */
	public function __construct(ISa $cookie, ISaGet $get, ISa $post, ISa $server, ISa $env, $baseUrl) {
		if(get_magic_quotes_runtime()) {
			set_magic_quotes_runtime(0);
		}
		$this->_cookie = $cookie;
		$this->_get = $get;
		$this->_post = $post;
		$this->_server = $server;
		$this->_env = $env;
		$this->_baseUrl = $baseUrl;
		$this->removeGlobal();		
	}

	/**
	 * Kasuje zmienne globalne jesli register globals jest wlaczone
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function removeGlobal() {
		if(ini_get('register_globals')) {
			$_REQUEST = array();
			$globalsArrays = array('_REQUEST', '_GET', '_POST', '_SERVER', '_COOKIE', '_ENV', '_FILES'); //_SESSION must be sanitized after sesion_start()
			if(isset($_SESSION) && is_array($_SESSION)) {
				$globalsArrays[] = $_SESSION;
			}
			foreach($globalsArrays AS $global) {
				foreach($GLOBALS[$global] AS $key => $var){
					if(isset($GLOBALS[$key])) {
						unset($GLOBALS[$key]);
					}
				}
			}
		}
	}
	
	/**
	 * Zwraca wartosc podanego klucza z tablicy _GET lub cala tablice
	 *
	 * @access public
	 * @param string Klucz tablicy
	 * @return mixed //string|array|null
	 * 
	 */	
	public function get($key=null) {
		return $this->_get->get($key);
	}

	/**
	 * Zwraca wartosc podanego klucza z tablicy _GET, bez jakichkolwiek zmian lub cala tablice
	 *
	 * @access public
	 * @param string Klucz tablicy
	 * @return mixed //string|array|null
	 * 
	 */	
	public function rawGet($key=null) {
		return $this->_get->getRaw($key);
	}

	/**
	 * Zapisuje do tablicy _GET podana wartosc
	 *
	 * @access public
	 * @param string|array Klucz tablicy lub tablica
	 * @param mixed Zapisywana wartosc
	 * @return void
	 * 
	 */	
	public function setQuery($key, $value=null) {
		$this->_get->setQuery($key, $value);
	}
	
	/**
	 * Zwraca wartosc podanego klucza z tablicy _POST lub cala tablice
	 *
	 * @access public
	 * @param string Klucz tablicy
	 * @return mixed //string|array|null
	 * 
	 */	
	public function post($key=null) {
		return $this->_post->get($key);
	}
	
	/**
	 * Zwraca wartosc podanego klucza z tablicy _POST, bez jakichkolwiek zmian lub cala tablice
	 *
	 * @access public
	 * @param string Klucz tablicy
	 * @return mixed //string|array|null
	 * 
	 */	
	public function rawPost($key=null) {
		return $this->_post->getRaw($key);
	}	
	
	/**
	 * Zwraca wartosc podanego klucza z tablicy _COOKIE lub cala tablice
	 *
	 * @access public
	 * @param string Klucz tablicy
	 * @return mixed //string|array|null
	 * 
	 */	
	public function cookie($key=null) {
		return $this->_cookie->get($key);
	}
	
	/**
	 * Zwraca wartosc podanego klucza z tablicy _COOKIE, bez jakichkolwiek zmian lub cala tablice
	 *
	 * @access public
	 * @param string Klucz tablicy
	 * @return mixed //string|array|null
	 * 
	 */	
	public function rawCookie($key=null) {
		return $this->_cookie->getRaw($key);
	}	
	
	/**
	 * Zwraca wartosc podanego klucza z tablicy _SERVER, lub cala tablice
	 *
	 * @access public
	 * @param string Klucz tablicy
	 * @return mixed //string|array|null
	 * 
	 */		
	public function server($key=null) {
		return $this->_server->get($key);
	}	
	
	/**
	 * Zwraca wartosc podanego klucza z tablicy _SERVER, bez jakichkolwiek zmian lub cala tablice
	 *
	 * @access public
	 * @param string Klucz tablicy
	 * @return mixed //string|array|null
	 * 
	 */		
	public function rawServer($key=null) {
		return $this->_server->getRaw($key);
	}	

	/**
	 * Zwraca wartosc podanego klucza z tablicy _ENV, lub cala tablice
	 *
	 * @access public
	 * @param string Klucz tablicy
	 * @return mixed //string|array|null
	 * 
	 */		
	public function env($key=null) {
		return $this->_env->get($key);
	}	
	
	/**
	 * Zwraca wartosc podanego klucza z tablicy _ENV, bez jakichkolwiek zmian lub cala tablice
	 *
	 * @access public
	 * @param string Klucz tablicy
	 * @return mixed //string|array|null
	 * 
	 */		
	public function rawEnv($key=null) {
		return $this->_env->getRaw($key);
	}	
	
	//Metody pobierajace rozne informacje z tablicy $_SERVER
	
	/**
	 * Zwraca wartosc klucza HTTP_HOST z tablicy _SERVER,
	 * czyli nazwe hosta, na ktorym wykonywany jest skrypt
	 *
	 * @access public
	 * @return mixed //string|null
	 * 
	 */ 
	public function getHost() {
		return htmlentities($this->server('HTTP_HOST'));
	}

	/**
	 * Zwraca nazwe katalogu w ktorym znajduje sie wykonywany skrypt. 
	 * Korzysta z klucza SCRIPT_NAME z tablicy _SERVER
	 *
	 * @access public
	 * @return mixed //string|null
	 * 
	 */ 	
	public function getScriptFolder() {
		$scriptName = $this->server('SCRIPT_NAME');
		if(isset($scriptName)) {
			return htmlentities(dirname($this->server('SCRIPT_NAME')));
		}
		return null;	
	}

	/**
	 * Zwraca nazwe wykonywanego skryptu. 
	 * Korzysta z klucza SCRIPT_NAME z tablicy _SERVER
	 *
	 * @access public
	 * @return mixed //string|null
	 * 
	 */ 
	public function getScriptName() {
		$scriptName = $this->server('SCRIPT_NAME');
		if(isset($scriptName)) {
			return htmlentities(basename($this->server('SCRIPT_NAME')));
		}
		return null;	
	}
	
	/**
	 * Zwraca metode zadania POST lub GET
	 *
	 * @access public
	 * @return string
	 * 
	 */			
	public function getRequestMethod() {
		$requestMethod = $this->server('REQUEST_METHOD');
		if(isset($requestMethod)) {
			return $this->server('REQUEST_METHOD'); 
		}
		return 'GET';
	}
	
	/**
	 * Sprawdza czy metoda zadania jest taka jaka podana w parametrze
	 *
	 * @access public
	 * @param string Metoda zadania
	 * @return bool
	 * 
	 */			
	public function isRequestMethod($requestMethod) {
		if($requestMethod == $this->getRequestMethod()) {
			return true;
		}
		return false;
	}
	
	/**
	 * Sprawdza czy metoda zadania jest metoda POST
	 *
	 * @access public
	 * @return bool
	 * 
	 */			
	public function isPost() {
		return $this->isRequestMethod('POST');
	}
	
	/**
	 * Sprawdza czy metoda zadania jest metoda GET
	 *
	 * @access public
	 * @return bool
	 * 
	 */			
	public function isGet() {
		return $this->isRequestMethod('GET');
	}
	
	/**
	 * Sprawdza czy metoda zadania jest metoda PUT
	 *
	 * @access public
	 * @return bool
	 * 
	 */			
	public function isPut() {
		return $this->isRequestMethod('PUT');
	}
	
	/**
	 * Sprawdza czy metoda zadania jest metoda DELETE
	 *
	 * @access public
	 * @return bool
	 * 
	 */			
	public function isDelete() {
		return $this->isRequestMethod('DELETE');
	}	
	
	/**
	 * Sprawdza czy zadanie jest poprzez XmlHttpRequest
	 *
	 * @access public
	 * @return bool
	 * 
	 */			
	public function isAjax() {
		$httpRequestedWith = $this->server('HTTP_X_REQUESTED_WITH');
		if(isset($httpRequestedWith) && strtolower($httpRequestedWith) == 'xmlhttprequest') {
			return true;
		}
		return false;		
	}

	/**
	 * Zwraca dostepne IP klienta, nawet jesli to jest IP proxy
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getIp() {
		$ip = '';
		if(isset($this->_server['REMOTE_ADDR']) && $this->_server['REMOTE_ADDR'] != '') {
			$ip = $this->_server['REMOTE_ADDR'];
		}
		elseif (getenv('REMOTE_ADDR')) {
			$ip = getenv('REMOTE_ADDR');
		}
		if($ip && ($ip != '') && ($this->validIp($ip) === true)) {
			return $ip;
		}
		if($ip == '' || (!$ip)) {
			$ip = '0.0.0.0';
		}		
		return $ip;		
	}
	
	/**
	 * Probuje znalesc IP klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientIp() {
		$ip = '';		
		if(isset($this->_server['HTTP_X_FORWARDED_FOR']) && ($this->_server['HTTP_X_FORWARDED_FOR'] != '')) {
			$ip = $this->_server['HTTP_X_FORWARDED_FOR'];
		}	
		elseif(isset($this->_server['HTTP_X_FORWARDED']) && ($this->_server['HTTP_X_FORWARDED'] != '')) {
			$ip = $this->_server['HTTP_X_FORWARDED'];
		}
		elseif(isset($this->_server['HTTP_FORWARDED_FOR']) && ($this->_server['HTTP_FORWARDED_FOR'] != '')) {
			$ip = $this->_server['HTTP_FORWARDED_FOR'];
		}		
		elseif(isset($this->_server['HTTP_FORWARDED']) && ($this->_server['HTTP_FORWARDED'] != '')) {
			$ip = $this->_server['HTTP_FORWARDED'];
		}	
		elseif(isset($this->_server['HTTP_X_COMMING_FROM']) && ($this->_server['HTTP_X_COMMING_FROM'] != '')) {
			$ip = $this->_server['HTTP_X_COMMING_FROM'];
		}	
		elseif(isset($this->_server['HTTP_COMMING_FROM']) && ($this->_server['HTTP_COMMING_FROM'] != '')) {
			$ip = $this->_server['HTTP_COMMING_FROM'];
		}
		elseif(isset($this->_server['CLIENT_IP']) && $this->_server['CLIENT_IP'] != '') {
			$ip = $this->_server['CLIENT_IP'];
		}			
		elseif(isset($this->_server['HTTP_VIA']) && $this->_server['HTTP_VIA'] != '') {
			$ip = $this->_server['HTTP_VIA'];
		}
		elseif(isset($this->_server['REMOTE_ADDR']) && $this->_server['REMOTE_ADDR'] != '') {
			$ip = $this->_server['REMOTE_ADDR'];
		}
		elseif (getenv('REMOTE_ADDR')) {
			$ip = getenv('REMOTE_ADDR');
		}
		if(strstr($ip, ',')) {
			$ips = array();
			$ips = explode(',', $ip);
			$ip = trim(end($ips));
		}
		if($ip AND ($ip != '') && ($this->validIp($ip) === true)) {
			return $ip;
		}
		if($ip == '' || (!$ip)) {
			$ip = '0.0.0.0';
		}		
		return $ip;
	}
	
	/**
	 * Sprawdza czy adres IP jest poprawnym adresem IP
	 * 
	 * @access public
	 * @param string Sprawdzany ciag
	 * @return bool
	 * 
	 */			
	public function validIp($ip) {
		if(filter_var($ip, FILTER_VALIDATE_IP) === false) {
			return false;
		}
		return true;
	}
	
	/**
	 * Zwraca surowy ciag wyslany przez klienta
	 * 
	 * @access public
	 * @return string|null
	 * 
	 */		
	public function getUserAgent() {
		return htmlentities($this->server('HTTP_USER_AGENT'));
	}	

	/**
	 * Zwraca adres strony z ktorej przybyl klient
	 * 
	 * @access public
	 * @return string|null
	 * 
	 */	
	public function getReferer() {
		return htmlentities($this->server('HTTP_REFERER'));
	}
	
	/**
	 * Zwraca adres strony z  konfiguracji
	 * 
	 * @access public
	 * @return string|null
	 * 
	 */		
	public function getBaseUrl() {
		if(!empty($this->_baseUrl)) {
			return $this->_baseUrl;
		}
		else {
			return $this->getHost() . $this->getScriptFolder();
		}
	}
	
}

?>
