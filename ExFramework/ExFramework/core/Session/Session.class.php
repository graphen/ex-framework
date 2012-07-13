<?php

/**
 * @class Session
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Session implements ISession, Countable, ArrayAccess, Iterator {
	
	/**
	 * Nazwa sesji
	 *
	 * @var string
	 */	
	protected $_sessionName = null;
	
	/**
	 * Obiekt obslugi i zarzadzania sesja
	 *
	 * @var resource
	 */	
	protected $_sessionHandler = null;

	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt zarzadzajacy i obslugujacy sesje
	 * @param string Nazwa sesji
	 * @param bool Czy sesja ma startowac automatycznie czy ma byc uruchamiana recznie
	 * @param int Maksymalny czas zycia sesji
	 * @param int gcProbability
	 * @param int gcDivisor
	 * @param string 'Sciezka dla ciasteczek'
	 * @param string 'Domena ciasteczek'
	 * @param string 'Bezpieczne ciasteczka'
	 * @param string 'Ciasteczka tylko przez HTTP'
	 *
	 */	
	public function __construct(ISessionHandler $sessionHandler=null, $sessionName=null, $sessionAutostart=true, $sessionMaxLifeTime=1440, $sessionGcProbability=1, $sessionGcDivisor=1000, $cookiePath='/', $cookieDomain='', $cookieSecure='', $cookieHttpOnly='') {
		
		$this->_sessionName = $sessionName;
		$this->_sessionHandler = $sessionHandler;
		
		if($this->_sessionName !== null) {
			session_name($this->_sessionName);
		}
		else {
			$this->_sessionName = session_name();
		}
		
		if(($sessionHandler !== null) && ((!$sessionHandler instanceof SessionNativeHandler))) { //jesli false to natywna obsluga sesji
			session_set_save_handler(array(&$sessionHandler, 'open'),
									 array(&$sessionHandler, 'close'),
									 array(&$sessionHandler, 'read'),
									 array(&$sessionHandler, 'write'),
									 array(&$sessionHandler, 'destroy'),
									 array(&$sessionHandler, 'gc')
									);
		}
		
		ini_set('session.cookie_lifetime', 0); //Czas zycia cookie jest nieskonczony, czas zycia sesji zalezy od maksymalnego okreslonego dla sesji
		if($cookiePath != '') {
			ini_set('session.cookie_path', (string)$cookiePath);
		}
		if($cookieDomain != '') {
			ini_set('session.cookie_domain', (string)$cookieDomain);
		}
		if($cookieSecure != '') {
			ini_set('session.cookie_secure', (string)$cookieSecure);
		}
		if($cookieHttpOnly != '') {
			ini_set('session.cookie_httponly', (string)$cookieHttpOnly);
		}			
		
		if($sessionMaxLifeTime != null) { //Maksymalny czas zycia sesji
			ini_set('session.gc_maxlifetime', (int)$sessionMaxLifeTime);
		}
		//probability = gc_probability / gc_divisor * 100%
		if($sessionGcProbability != null) {
			ini_set('session.gc_probability', (int)$sessionGcProbability);
		}
		if($sessionGcDivisor != null) {
			ini_set('session.gc_divisor', (int)$sessionGcDivisor);
		}
		
		if(($sessionAutostart == true) && !(bool)session_id()) {
			$this->start();
		}
		
		if(!isset($_SESSION[$this->_sessionName])) {
			$_SESSION[$this->_sessionName] = array();
			$_SESSION[$this->_sessionName]['flash'] = array();
			$_SESSION[$this->_sessionName]['flash']['new'] = array();
			$_SESSION[$this->_sessionName]['flash']['old'] = array();
		}
		$this->deleteOldFlashData();
		$this->markAsOldFlashData();
		
	}
	
	/**
	 * Metoda magiczna, sprawdza istnienie zmiennej sesji
	 * 
	 * @access public
	 * @param string Nazwa zmiennej sesji
	 * @return bool
	 * 
	 */	
	public function __isset($var) {
		return isset($_SESSION[$this->_sessionName][$var]);
	}
	
	/**
	 * Metoda magiczna, zwraca wartosc zmiennej sesji lub null jesli nie istnieje
	 * 
	 * @access public
	 * @param string Nazwa zmiennej sesji
	 * @return mixed|null
	 * 
	 */		
	public function __get($var) {
		return ($this->__isset($var)) ? $_SESSION[$this->_sessionName][$var] : null;
	}
	
	/**
	 * Metoda magiczna, ustawia wartosc zmiennej sesji
	 * 
	 * @access public
	 * @param string Nazwa zmiennej sesji
	 * @param mixed Wartosc zmiennej sesji
	 * @return void
	 * 
	 */		
	public function __set($var, $value='') {
		if(is_string($var)) {
			$_SESSION[$this->_sessionName][$var] = $value;
		}
		elseif(is_array($var) && (count($var) > 0)) {
			foreach($var AS $v=>$val) {
				$_SESSION[$this->_sessionName][$v] = $val;
			}
		}
	}
	
	/**
	 * Metoda magiczna, usuwa zmienna sesji
	 * 
	 * @access public
	 * @param string Nazwa zmiennej sesji
	 * @return void
	 * 
	 */		
	public function __unset($var) {
		if(is_string($var)) {
			if($this->__isset($var)) {
				unset($_SESSION[$this->_sessionName][$var]);
			}
		}
		elseif(is_array($var) && (count($var) > 0)) {
			foreach($var AS $v=>$val) {
				if($this->__isset($v)) {
					unset($_SESSION[$this->_sessionName][$v]);
				}
			}
		}
	}
	
	/**
	 * Zwraca tablice zmiennych sesji
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function __toArray() {
		return (is_array($_SESSION[$this->_sessionName])) ? $_SESSION[$this->_sessionName] : array();
	}
	
	/**
	 * Metoda magiczna, zwraca ciag znakow informujacy o zawartosci tablicy sesji
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function __toString() {
		$out = "";
		$out .= "<pre>";
		$out .= print_r($this->__toArray(), true);
		$out .= "</pre>";
		return $out;
	}
	
	//Countable
	
	/**
	 * Zwraca ilosc elementow w tablicy sesji
	 * 
	 * @access public
	 * @return int
	 * 
	 */		
	public function count() {
		return count($_SESSION[$this->_sessionName]);
	}
	
	//ArrayAccess
	
	/**
	 * Zwraca wartosc zmiennej sesji lub null jesli nie istnieje
	 * 
	 * @access public
	 * @param string Nazwa zmiennej sesji
	 * @return mixed|null
	 * 
	 */		
	public function offsetGet($var) {
		return $this->__get($var);
	}
	
	/**
	 * Ustawia wartosc zmiennej sesji
	 * 
	 * @access public
	 * @param string Nazwa zmiennej sesji
	 * @param mixed Wartosc zmiennej sesji
	 * @return void
	 * 
	 */		
	public function offsetSet($var, $value='') {
		$this->__set($var, $value);
	}
	
	/**
	 * Sprawdza istnienie zmiennej sesji
	 * 
	 * @access public
	 * @param string Nazwa zmiennej sesji
	 * @return bool
	 * 
	 */		
	public function offsetExists($var) {
		return $this->__isset($var);
	}
	
	/**
	 * Usuwa zmienna sesji
	 * 
	 * @access public
	 * @param string Nazwa zmiennej sesji
	 * @return void
	 * 
	 */		
	public function offsetUnset($var) {
		$this->__unset($var);
	}
	
	//Iterator
	
	/**
	 * Ustawia znacznik tablicy sesji na poczatku
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function rewind() {
		reset($_SESSION[$this->_sessionName]);
	}
	
	/**
	 * Zwraca wartosc klucza aktualnego elementu tablicy sesji
	 * 
	 * @access public
	 * @return mixed
	 * 
	 */		
	public function key() {
		return key($_SESSION[$this->_sessionName]);
	}
	
	/**
	 * Zwraca nastepny element tablicy sesji
	 * 
	 * @access public
	 * @return mixed
	 * 
	 */		
	public function next() {
		return next($_SESSION[$this->_sessionName]);
	}
	
	/**
	 * Zwraca aktualny element sesji
	 * 
	 * @access public
	 * @return mixed
	 * 
	 */		
	public function current() {
		return current($_SESSION[$this->_sessionName]);
	}
	
	/**
	 * Sprawdza czy element istenieje w tablicy sesji
	 * 
	 * @access public
	 * @return bool
	 * 
	 */		
	public function valid() {
		return ($this->current() !== false);
	}
	
	/**
	 * Zapisuje sesje
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function writeClose() {
		session_write_close();
	}
	
	/**
	 * Niszczy istniejaca sesje zachowujac dane, ustawia nowy identyfikator sesji i startuje sesje wczytujac stare dane
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function regenerateId() {
		$oldSessionId = session_id(); //skopiowanie starego id sesji
		$oldSessionData = $_SESSION; //skopiowanie danych sesji
		session_regenerate_id(); //podmiana id sesji
		$newSessionId = session_id(); //skopiowanie nowego id sesji
		session_id($oldSessionId); //przelaczenie sie na stara sesje
		session_destroy(); //Usuniecie danych starej sesji
		session_id($newSessionId); //przelaczenie sie na nowa sesje
		session_start(); //przeslanie cookie
		$_SESSION = $oldSessionData; //przypisanie danych starej sesji do nowej
	}
	
	/**
	 * Niszczy istniejaca sesje
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function destroy() {
		$_SESSION = array();
		$_SESSION[$this->_sessionName] = array();
		if(ini_get('session.use_cookies')) {
			$params = session_get_cookie_params();
			setcookie($this->_sessionName, '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
		}
		session_destroy();
	}
	
	/**
	 * Startuje sesje
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function start() {
		if(headers_sent()) {
			throw new SessionException('Nie mozna uruchomic sesji, naglowki zostaly wyslane');
		}
		session_start();
	}	
	
	/**
	 * Konczy sesje niszczac ja
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function stop() {
		$this->regenerateId();
		session_unset();
		$this->destroy();
	}
	
	/**
	 * Ustawia wartosc pewnej zmiennej sesji, ktora ma zostac zapamietana tylko dla kolejnego zadania 
	 * 
	 * @access public
	 * @param string Nazwa zmiennej
	 * @param mixed Wartosc zmiennej
	 * @return void
	 * 
	 */		
	public function setFlashData($var, $value='') {
		if(is_string($var)) {
			$_SESSION[$this->_sessionName]['flash']['new'][$var] = $value;
		}
		elseif(is_array($var) && (count($var) > 0)) {
			foreach($var AS $v=>$val) {
				$_SESSION[$this->_sessionName]['flash']['new'][$v] = $val;
			}
		}
	}
	
	/**
	 * Zwraca wartosc pewnej zmiennej, ktora jest aktualna tylko dla aktualnego zadania
	 * 
	 * @access public
	 * @param string Nazwa zmiennej
	 * @return mixed
	 * 
	 */		
	public function getFlashData($var) {
		return (isset($_SESSION[$this->_sessionName]['flash']['old'][$var])) ? $_SESSION[$this->_sessionName]['flash']['old'][$var] : null;		
	}

	/**
	 * Ustawia ponownie wartosc pewnej zmiennej sesji, ktora ma zostac zapamietana dla kolejnego zadania 
	 * 
	 * @access public
	 * @param string Nazwa zmiennej
	 * @return void
	 * 
	 */	
	public function keepFlashData($var) {
		if(isset($_SESSION[$this->_sessionName]['flash']['old'][$var])) {
			$_SESSION[$this->_sessionName]['flash']['new'][$var] = $_SESSION[$this->_sessionName]['flash']['old'][$var];
		}
	}
	
	/**
	 * Kasuje wartosci zmiennych sesji, ktore mialy byc przechowane do nastepnego zadania
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	protected function deleteOldFlashData() {
		 $_SESSION[$this->_sessionName]['flash']['old'] = array();
	}
	
	/**
	 * Oznacza pewne zmienne, ktore zostaly zachowane do aktualnego zadania jako 'zuzyte' i do usuniecia
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function markAsOldFlashData() {
		 $_SESSION[$this->_sessionName]['flash']['old'] =  $_SESSION[$this->_sessionName]['flash']['new'];
		 $_SESSION[$this->_sessionName]['flash']['new'] = array();
	}
	
}

?>
