<?php

/**
 * @class SessionHandlerMemcache
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class SessionHandlerMemcache implements ISessionHandler {
	
	/**
	 * Obiekt Memcache
	 *
	 * @var object
	 */	
	protected $_memcacheObject = null;	
	
	/**
	 * Adres hosta memcached
	 * 
	 * @var string
	 * 
	 */
	protected $_memcacheHost = null;
	
	/**
	 * Port na ktorym nasluchuje usluga memcached
	 * 
	 * @var int
	 * 
	 */	
	protected $_memcachePort = null;
	
	/**
	 * Maksymalny czas zycia sesji
	 * 
	 * @var int
	 * 
	 */	
	protected $_sessionMaxLifeTime = 1440;
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param string Sciezka do katalogu sesji
	 * @param int Maksymalny czas zycia sesji
	 * 
	 */	
	public function __construct($memcacheHost, $memcachePort, $sessionMaxLifeTime=1440) {
		if(!extension_loaded('memcache')) {
			throw new SessionException('Brak rozszerzenia memcache');
		}
		if(!empty($memcacheHost)) {
			$this->setMemcacheHost($memcacheHost);
		}
		if(!empty($memcachePort)) {
			$this->setMemcachePort($memcachePort);
		}
		if(!empty($sessionMaxLifeTime)) {
			$this->_sessionMaxLifeTime = $sessionMaxLifeTime;
		}
		$this->connect();
	}
	
	/**
	 * Destruktor
	 * 
	 * @access public
	 * 
	 */		
	public function __destruct() {
		session_write_close(); //Dane sesji musza zostac zapisane zanim jeszcze zniszczone zostana odpowiedzialne za to obiekty
		$this->closeConnection();
	}	
	
	/**
	 * Metoda pozwala na nawiazanie polaczenia z serwerem memcache
	 * 
	 * @access protected
	 * @return void
	 *  
	 */	
	protected function connect() {
		if((is_object($this->_memcacheObject) && (!($this->_memcacheObject instanceof Memcache))) || $this->_memcacheObject == null) {
			$this->_memcacheObject = new Memcache();
			if(!$this->_memcacheObject->connect($this->_memcacheHost, $this->_memcachePort)) {
				throw new SessionException('Nie mozna polaczyc sie z serwerem memcache');
			}
		}
	}

	/**
	 * Metoda pozwala na zamkniecie polaczenia z serwerem memcache
	 * 
	 * @access protected
	 * @return void
	 *  
	 */	
	protected function closeConnection() {
		if(is_object($this->_memcacheObject) && ($this->_memcacheObject instanceof Memcache)) {
			$this->_memcacheObject->close();
		}
	}	
	
	/**
	 * Wywolywana podczas otwarcia sesji
	 * 
	 * @access public
	 * @param string Sciezka do katalogu sesji
	 * @param string Nazwa sesji
	 * @return bool
	 * 
	 */ 	
	public function open($sessionSavePath, $sessionName) {
		return true;
	}
	
	/**
	 * Wywolywana podczas zamkniecia sesji
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 	
	public function close() {
		return $this->gc($this->_sessionMaxLifeTime);
	}
	
	/**
	 * Odczytuje zmienne sesji
	 * 
	 * @access public
	 * @param string Identyfikator sesji
	 * @return string
	 * 
	 */ 	
	public function read($sessionId) {
		$data = $this->fetch('sessions/' . $sessionId);
		if($data !== false) {
			return (string)$data;
		}
		else {
			return '';
		}
	}
	
	/**
	 * Zapisuje dane sesji
	 * 
	 * @access public
	 * @param string Identyfikator sesji
	 * @param string Dane
	 * @return bool
	 * 
	 */ 	
	public function write($sessionId, $sessionData) {
		try {
			$this->store('sessions/' . $sessionId, $sessionData, $this->_sessionMaxLifeTime);
		}
		catch(SessionExcepion $e) {
			return false;
		}
		return true;
	}
	
	/**
	 * Niszczy sesje o okreslonym identyfikatorze
	 * 
	 * @access public
	 * @param string Identyfikator sesji
	 * @return bool
	 * 
	 */ 	
	public function destroy($sessionId) {
		try {
			$this->delete('sessions/' . $sessionId);
		}
		catch(SessionExcepion $e) {
			return false;
		}
		return true;
	}
	
	/**
	 * Garbage collector
	 * 
	 * @access public
	 * @param int Maksymalny czas zycia sesji
	 * @return bool
	 * 
	 */ 	
	public function gc($maxLifeTime) {
		return true;
	}
	
	/**
	 * Metoda ustawia adres serwera memcached
	 * 
	 * @access public
	 * @param string
	 * @return void
	 *  
	 */
	public function setMemcacheHost($memcacheHost) {
		$this->_memcacheHost = (string)$memcacheHost;
	}
	
	/**
	 * Metoda zwraca adres serwera memcached
	 * 
	 * @access public
	 * @return string
	 *  
	 */
	public function getMemcacheHost() {
		return $this->_memcacheHost; 
	}

	/**
	 * Metoda ustawia posrt na ktorym dziala serwer memcache
	 * 
	 * @access public
	 * @param integer
	 * @return void
	 *  
	 */
	public function setMemcachePort($memcachePort) {
		$this->_memcachePort = (int)$memcachePort;
	}
	
	/**
	 * Metoda zwraca port na ktorym dziala serwer memcached
	 * 
	 * @access public
	 * @return int
	 *  
	 */
	public function getMemcachePort() {
		return $this->_memcachePort; 
	}
	
	//Metody prywatne
	
	/**
	 * Metoda odczytuje dane identyfikowane podanym identyfikatorem i zwraca je. Mozna odczytac jednoczesnie takze cala tablice
	 * 
	 * @access protected
	 * @param string Identyfikator zmiennej, lub tablica z identyfikatorami
	 * @return mixed|false
	 *  
	 */
	protected function fetch($id) {
		$this->connect();		
		return $this->_memcacheObject->get($id);
	}
	
	/**
	 * Metoda zapisuje dane identyfikowane przez podany identyfikator w pamieci
	 * 
	 * @access protected
	 * @param string Identyfikator 
	 * @param mixed Dane do zapisania
	 * @param int Czas zycia zmiennej w pamieci
	 * @return void
	 *  
	 */
	protected function store($id, $data, $lifeTime=null) {
		$this->connect();
		if($lifeTime === null) {
			$lifeTime = $this->_lifeTime;
		}
		if(($ret = $this->_memcacheObject->replace($id, $data, false, $lifeTime)) == false) {
			$ret = $this->_memcacheObject->set($id, $data, false, $lifeTime);
		}
		if($ret === false) {
			throw new SessionException('Nie mozna zapisac danych w pamieci');
		}
	}
	
	/**
	 * Metoda usuwa wszystkie zmienne zawierajace dane identyfikowane podanym identyfikatorem 
	 * 
	 * @access public
	 * @param string Identyfikator zmiennych
	 * @return void
	 * 
	 */	
	protected function delete($id) {
		$this->connect();		
		$ret = $this->_memcacheObject->delete($id);
		if($ret === false) {
			throw new SessionException('Nie mozna usunac danych z pamieci');
		}		
	}
	
}
	
?>
