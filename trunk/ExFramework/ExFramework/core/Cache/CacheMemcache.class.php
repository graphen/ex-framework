<?php

/**
 * @class CacheMemcache
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class CacheMemcache implements ICacheMemcache {
	
	/**
	 * Czas zycia cache
	 *
	 * @var integer
	 */	
	protected $_lifeTime = 3600; //seconds	
	
	/**
	 * Obiekt Memcache
	 *
	 * @var object
	 */	
	protected $_memcacheObject = null;

	/**
	 * Adres hosta na ktorym dziala memcache
	 *
	 * @var string
	 */	
	protected $_memcacheHost = '';
	
	/**
	 * Numer portu na ktorym dziala memcache
	 *
	 * @var integer
	 */		
	protected $_memcachePort = null;

	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param int Czas zycia cache
	 * @param string Memcache Host
	 * @param int Memcache Port
	 *
	 */	
	public function __construct($cacheLifeTime=3600, $memcacheHost='', $memcachePort=null) {
		if(!extension_loaded('memcache')) {
			throw new CacheException('Brak rozszerzenia memcache');
		}
		if(!empty($memcacheHost)) {
			$this->setMemcacheHost($memcacheHost);
		}
		if(!empty($memcachePort)) {
			$this->setMemcachePort($memcachePort);
		}
		$this->setLifeTime($cacheLifeTime);
		$this->connect();
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
				throw new CacheException('Nie mozna polaczyc sie z serwerem memcache');
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
	protected function close() {
		if(is_object($this->_memcacheObject) && ($this->_memcacheObject instanceof Memcache)) {
			$this->_memcacheObject->close();
		}
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
	 * Metoda magiczna pozwala na zapis danych cache identyfikowanych przez podany identyfikator
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
	 * Metoda magiczna pozwala na odczyt danych identyfikowanych podanym identyfikatorem i zwrocenie ich. Mozna odczytac jednoczesnie takze cala tablice
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
	 * Metoda ustawia globalny czas życia cache
	 * 
	 * @access public
	 * @param int Czas zycia cache
	 * @return void
	 *  
	 */
	public function setLifeTime($lifeTime) {
		$this->_lifeTime = (int) $lifeTime;
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
	
	/**
	 * Metoda odczytuje dane identyfikowane podanym identyfikatorem i zwraca je. Mozna odczytac jednoczesnie takze cala tablice
	 * 
	 * @access public
	 * @param string Identyfikator cache, lub tablica z identyfikatorami
	 * @return mixed|false
	 *  
	 */
	public function fetch($id) {
		$this->connect();		
		return $this->_memcacheObject->get($id);
	}
	
	/**
	 * Metoda zapisuje dane identyfikowane przez podany identyfikator w cache
	 * 
	 * @access public
	 * @param string Identyfikator cache 
	 * @param mixed Dane do zapisania
	 * @param int Czas zycia cache
	 * @return void
	 *  
	 */
	public function store($id, $data, $lifeTime=null) {
		$this->connect();
		if($lifeTime === null) {
			$lifeTime = $this->_lifeTime;
		}
		if(($ret = $this->_memcacheObject->replace($id, $data, false, $lifeTime)) == false) {
			$ret = $this->_memcacheObject->set($id, $data, false, $lifeTime);
		}
		if($ret === false) {
			throw new CacheException('Nie mozna zapisac danych w cache');
		}
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
		$this->connect();		
		if(($ret = $this->_memcacheObject->get($id)) == false) {
			return false;
		}
		else {
			return true;
		}
	}
	
	/**
	 * Metoda usuwa cache zawierajacy dane identyfikowane podanym identyfikatorem 
	 * 
	 * @access public
	 * @param string Identyfikator cache
	 * @return void
	 * 
	 */	
	public function delete($id) {
		$this->connect();		
		$ret = $this->_memcacheObject->delete($id);
		if($ret === false) {
			throw new CacheException('Nie mozna usunac danych z cache');
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
		$this->connect();
		$ret = $this->_memcacheObject->flush();
		if($ret === false) {
			throw new CacheException('Nie mozna wyczyscic cache');
		}
	}
	
}

?>
