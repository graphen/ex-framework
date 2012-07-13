<?php

/**
 * @interface IBrowser
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IBrowser {
	
	/**
	 * Zwraca surowy ciag identyfikujacy przegladarke
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getClientUserAgentString();
	
	/**
	 * Zwraca system operacyjny klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientOS();
	
	/**
	 * Zwraca nazwe+wersje przegladarki klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */
	public function getClientUserAgentNameVer();
	
	/**
	 * Zwraca nazwe przegladarki klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientUserAgentName();
	
	/**
	 * Zwraca wersje przegladarki klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientUserAgentVersion();
	
	/**
	 * Zwraca numer glowny przegladarki klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientUserAgentMajorVersion();
	
	/**
	 * Zwraca numer poboczny przegladarki klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientUserAgentMinorVersion();
	
	/**
	 * Zwraca nazwe urzadzenia telefonicznego
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientMobileDevice();
	
	/**
	 * Zwraca tablice akceptowanych jezykow
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getClientUserAgentAcceptedLanguages();
	
	/**
	 * Zwraca mozliwy podstawowy jezyk klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientLanguage();
	
	/**
	 * Zwraca tablice akceptowanych stron kodowych klienta
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getClientUserAgentAcceptedCharsets();
	
	/**
	 * Zwraca mozliwa glowna strone kodowa klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientCharset();
	
	/**
	 * Zwraca adres strony z ktorej przybyl klient
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getReferer();
	
	/**
	 * Sprawdza czy klient to przegladarka
	 * 
	 * @access public
	 * @return bool
	 * 
	 */	
	public function isBrowser();
	
	/**
	 * Sprawdza czy klient to robot
	 * 
	 * @access public
	 * @return bool
	 * 
	 */	
	public function isRobot();
	
	/**
	 * Sprawdza czy klient to telefon
	 * 
	 * @access public
	 * @return bool
	 * 
	 */	
	public function isMobile();
	
	/**
	 * Sprawdza czy podany jezyk jest akceptowany przez klienta
	 * 
	 * @access public
	 * @param string Dwuliterowy skrot jezyka
	 * @return bool
	 * 
	 */	
	public function isAcceptedLanguage($language='pl');
	
	/**
	 * Sprawdza czy podana strona kodowa jest akceptowana przez klienta
	 * 
	 * @access public
	 * @param string Kodowanie klienta
	 * @return bool
	 * 
	 */
	public function isAcceptedCharset($charset='iso8859-2');
	
	/**
	 * Sprawdza czy klient przybyl z innej strony internetowej
	 * 
	 * @access public
	 * @return bool
	 * 
	 */	
	public function isReferer();
	
	/**
	 * Metoda ulatwiajaca testowanie
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function reset();
	
}

?>
