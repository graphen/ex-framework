<?php

/**
 * @interface ICacheMemcache
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface ICacheMemcache extends ICache {
	
	/**
	 * Metoda ustawia adres serwera memcache
	 * 
	 * @access public
	 * @param string Adres serwera memcache
	 * @return void
	 *  
	 */		
	public function setMemcacheHost($memcacheHost);
	
	/**
	 * Metoda zwraca adres serwera memcache
	 * 
	 * @access public
	 * @return string
	 *  
	 */			
	public function getMemcacheHost();
	
	/**
	 * Metoda ustawia port serwera memcache
	 * 
	 * @access public
	 * @param int Numer portu serwera memcache
	 * @return void
	 *  
	 */		
	public function setMemcachePort($memcachePort);
	
	/**
	 * Metoda zwraca port serwera memcache
	 * 
	 * @access public
	 * @return int
	 *  
	 */			
	public function getMemcachePort();
	
}

?>
