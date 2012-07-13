<?php

/**
 * @class CacheApc
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class CacheApc implements ICache {
	
	/**
	 * Czas zycia cache
	 *
	 * @var integer
	 */	
	protected $_lifeTime = 3600; //seconds	
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param int Czas zycia cache
	 *
	 */	
	public function __construct($cacheLifeTime=3600) {
		if(!extension_loaded('apc')) {
			throw new CacheException('Brak rozszerzenia APC');
		}
		$this->setLifeTime($cacheLifeTime);
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
	 * Metoda magiczna pozwala na usuniecie wpisu cache zawierajacego dane identyfikowane podanym identyfikatorem 
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
	 * Metoda odczytuje dane identyfikowane podanym identyfikatorem i zwraca je. Mozna odczytac jednoczesnie takze cala tablice
	 * 
	 * @access public
	 * @param string Identyfikator cache, lub tablica z identyfikatorami
	 * @return mixed|false
	 *  
	 */
	public function fetch($id) {
		return @unserialize(apc_fetch($id));
	}
	
	/**
	 * Metoda zapisuje dane cache identyfikowane przez podany identyfikator
	 * 
	 * @access public
	 * @param string Identyfikator cache 
	 * @param mixed Dane do zapisania
	 * @param int Czas zycia cache
	 * @return void
	 *  
	 */
	public function store($id, $data, $lifeTime=null) {
		if($lifeTime === null) {
			$lifeTime = $this->_lifeTime;
		}
		$ret = apc_store($id, serialize($data), $lifeTime);
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
		$ret = apc_exists($id);
		if($ret === false) {
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
		$ret = apc_delete($id);
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
		$ret = apc_clear_cache('user');
		if($ret === false) {
			throw new CacheException('Nie mozna wyczyscic cache');
		}
	}
	
}

?>
