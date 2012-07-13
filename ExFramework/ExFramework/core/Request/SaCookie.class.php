<?php

/**
 * @class SaCookie
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class SaCookie extends SaAbstract implements ISaCookie {

	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt impl. interfejs IFilterComposite
	 * @param object Obiekt filtra FilterStripSlashes
	 * 
	 */		
	public function __construct(IFilterComposite $filterComposite, IFilter $stripSlashesFilter) {
		parent::init('COOKIE', $filterComposite);
		$this->addFilter($stripSlashesFilter);		
	}
	
	/**
	 * Ustawia nowe ciasteczko
	 * 
	 * @access public
	 * @param string Nazwa zmiennej ktora ma zostac uniewazniona
	 * @param string Wartosc zmiennej
	 * @param integer Czas, podana wartosc 
	 * @param string Sciezka
	 * @param string Domena
	 * @param bool Ustawienie bezpiecznego dostepu, true jesli ma byc wlaczony dostep tylko przez SSL
	 * @param bool Dostep tylko przez HTTP
	 * @return void
	 * 
	 */
	public function set($name, $value='', $expired=3600, $path=null, $domain=null, $secure=false, $httponly=false) {
		if (!is_null($expired)) {
			$expired = time() + $expired;
		}
		if (headers_sent()) {
			throw new SaCookieException('Naglowki zostaly wyslane');
		}
		if (!setcookie($name, $value, $expired, $path, $domain, $secure, $httponly)) {
			throw new SaCookieException('Ciasteczko nie zostalo wyslane');
		}

	}

	/**
	 * Uniewaznia, kasuje ciasteczko
	 * 
	 * @access public
	 * @param string Nazwa zmiennej ktora ma zostac uniewazniona
	 * @param string Sciezka
	 * @param string Domena
	 * @param bool Ustawienie bezpiecznego dostepu, true jesli ma byc wlaczony dostep tylko przez SSL
	 * @param bool Dostep tylko przez HTTP
	 * @return void
	 * 
	 */	
	public function delete($name, $path=null, $domain=null, $secure=false, $httponly=false) {
		self::set($name, '', -3600, $path, $domain, $secure, $httponly);
	}
	
	/**
	 * Zwraca wartosc podanej zmiennej z tablicy $_COOKIE filtrujac ja
	 * 
	 * @access public
	 * @param string Nazwa zmiennej
	 * @return string|null
	 * 
	 */	
	public function get($var=null) {
		if(is_null($var)) {
			$tmpArr = $this->_data;
			array_walk_recursive($tmpArr, array(&$this, 'arrayWalkHelper'));
			return $tmpArr;
		}		
		if(isset($this->_data[$var])) {
			return $this->_filterComposite->filter($this->_data[$var]);
		}
		else {
			return null;
		}
	}

	/**
	 * Zwraca surowa wartosc podanej zmiennej z tablicy $_COOKIE
	 * 
	 * @access public
	 * @param string Nazwa zmiennej
	 * @return string|null
	 * 
	 */	
	public function getRaw($name=null) {
		if(is_null($name)) {
			return $this->_data;
		}		
		if(isset($this->_data[$name])) {
			return $this->_data[$name];
		}
		else {
			return null;
		}
	}
	
}

?>
