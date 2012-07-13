<?php

/**
 * @class AuthAdapterAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class AuthAdapterAbstract implements IAuthAdapter {
	
	/**
	 * Zmienna przechowujaca kod bledu
	 *
	 * @var int
	 * 
	 */		
	protected $_errorCode = null;
	
	/**
	 * Zmienna przechowujaca komunikat bledu
	 *
	 * @var string
	 * 
	 */		
	protected $_errorMessage = null;
	
	/**
	 * Metoda szyfrowania hasla
	 *
	 * @var string
	 * 
	 */		
	protected $_passwordCryptMethod = 'md5'; //plain, md5, sha1
	
	/**
	 * Login uzytkownika
	 *
	 * @var string
	 * 
	 */		
	protected $_userLogin = null;
	
	/**
	 * Dane uzytkownika
	 *
	 * @var null/object
	 * 
	 */		
	protected $_userData = null;
	
	/**
	 * 
	 * Zwraca kod bledu
	 * 
	 * @access public
	 * @return int
	 * 
	 */		
	public function getErrorCode() {
		return $this->_errorCode;
	}
	
	/**
	 * 
	 * Zwraca Komunikat bledu
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getErrorMessage() {
		return $this->_errorMessage;
	}	
	
	/**
	 * 
	 * Zwraca metode szyfrowania hasla
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getPasswordCryptMethod() {
		return $this->_passwordCryptMethod;
	}
	
	/**
	 * 
	 * Ustawia metode szyfrowania hasla
	 * 
	 * @access public
	 * @param string Metoda szyfrowania
	 * @return void
	 * 
	 */		
	public function setPasswordCryptMethod($cryptMethod) {
		$this->_passwordCryptMethod = $cryptMethod;
	}
	
	/**
	 * 
	 * Zwraca login zalogowanego uzytkownika
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getUserLogin() {
		return $this->_userLogin;
	}
	
	/**
	 * 
	 * Zwraca obiekt zalogowanego uzytkownika
	 * 
	 * @access public
	 * @return object
	 * 
	 */			
	public function getUserData() {
		return $this->_userData;
	}			
	
}

?>
