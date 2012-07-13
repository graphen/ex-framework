<?php

/**
 * @class AuthAdapterLdap
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class AuthAdapterLdap extends AuthAdapterAbstract implements IAuthAdapter {
	
	/**
	 * Identyfikator polaczenia
	 *
	 * @var resource
	 * 
	 */		
	protected $_connectionId = null;
	
	/**
	 * Adres serwera LDAP
	 *
	 * @var string
	 * 
	 */		
	protected $_ldapServer = null;
	
	/**
	 * Port serwera LDAP
	 *
	 * @var int
	 * 
	 */		
	protected $_ldapServerPort = null;
	
	/**
	 * Wersja protokolu LDAP
	 *
	 * @var string
	 * 
	 */		
	protected $_ldapProtocolVersion = null;
	
	/**
	 * Limit czasu serwera
	 *
	 * @var int
	 * 
	 */		
	protected $_ldapTimeLimit = null;
	
	/**
	 * BaseDn
	 *
	 * @var string
	 * 
	 */		
	protected $_ldapBaseDn = null;

	/**
	 * 
	 * Konstruktor
	 * 
	 * @access public
	 * @param string Adres serwera LDAP
	 * @param int Port serwera LDAP
	 * @param string Wersja protokolu LDAP
	 * @param int Czas oczekiwania serwera LDAP
	 * @param string BaseDn
	 * @param string Metoda szyfrowania hasla
	 * 
	 */			
	public function __construct($ldapServer, $ldapServerPort=389, $ldapProtoVer=3, $ldapTimeLimit=10, $ldapBaseDn='dc=localhost,dc=com', $passwordCryptMethod='plain') {
		$this->_ldapServer = $ldapServer;
		$this->_ldapServerPort = $ldapServerPort;
		$this->_ldapProtocolVersion = $ldapProtoVer;
		$this->_ldapTimeLimit = $ldapTimeLimit;
		$this->_ldapBaseDn = $ldapBaseDn;
		$this->_passwordCryptMethod = $passwordCryptMethod;
		
		if(!function_exists('ldap_connect')) {
			throw new AuthException('Brak rozszerzen obslugi uslug katalogowych LDAP');
		}
	}
	
	/**
	 * 
	 * Destruktor
	 * 
	 * @access public
	 * 
	 */		
	public function __destruct() {
		$this->close();
	}
	
	/**
	 * 
	 * Nawiazuje polaczenie z serwerem LDAP
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function connect() {
		if(!($this->_connectionId = ldap_connect($this->_ldapServer, $this->_ldapServerPort))) {
			throw new AuthException('Nie mozna nawiazac polaczenia z serwerem LDAP');
		}
		ldap_set_option($this->_connectionId, LDAP_OPT_PROTOCOL_VERSION, $this->_ldapProtocolVersion);
		ldap_set_option($this->_connectionId, LDAP_OPT_TIMELIMIT, $this->_ldapTimeLimit);
	}
	
	/**
	 * 
	 * Zamyka polaczenie z serwerem LDAP
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function close() {
		if(is_resource($this->_connectionId)) {
			ldap_unbind($this->_connectionId);
		}
	}
	
	/**
	 * 
	 * Przeprowadza uwierzytelnianie
	 * 
	 * @access public
	 * @param string login
	 * @param string haslo
	 * @return bool
	 * 
	 */		
	public function auth($login, $password=null) {
		if(($this->_ldapServer == '') || ($this->_ldapServerPort == '') || ($this->_ldapProtocolVersion == '') || ($this->_ldapTimeLimit == '') || ($this->_ldapBaseDn == '')) {
			throw new AuthException('Obiekt AuthAdapter nie zostal poprawnie zainicjowany');			
		}
		$this->connect();
		$this->_errorCode = null;
		$this->_errorMessage = null;		
		if(empty($login)) {
			$this->_errorCode = 1;
			$this->_errorMessage = "User does not exist";
			return false;			
		}
		if(empty($password)) {
			$this->_errorCode = 2;
			$this->_errorMessage = "Invalid password";
			return false;			
		}
		switch($this->_passwordCryptMethod) {
			case 'md5':
				$psw = md5($password);
				break;
			case 'sha1':
				$psw = sha1($password);
				break;
			case 'plain':
			default:
				$psw = $password;
				break;
		}
		if(($bind = ldap_bind($this->_connectionId)) == false){
			throw new AuthException('Nie mozna polaczyc sie anonimowo z serwerem LDAP');
		}		
		if(!($result = ldap_search($this->_connectionId, $this->_ldapBaseDn, "uid=$login"))) {
			$this->_errorCode = 1;
			$this->_errorMessage = "User does not exist";
			return false;
		}
		if(!(ldap_count_entries($this->_connectionId, $result)) != 1) {
			throw new AuthException('Nie moze istniec dwoch uzytkownikow o tym samym loginie');
		}
		if(($userId = ldap_first_entry($this->_connectionId, $result)) == false) {
			throw new AuthException('Nie mozna pobrac rekordu');			
		}
		if(($userDn = ldap_get_dn($this->_connectionId, $result)) == false) {
			throw new AuthException('Nie mozna pobrac rekordu');			
		}		
		if(($bdn = ldap_bind($this->_connectionId, $userDn, $psw)) == false) {
			$this->_errorCode = 2;
			$this->_errorMessage = "Invalid password";
			return false;
		}
		$user = new StdClass();
		$user->login = $login;
		$user->password = $psw;
		
		$this->_userLogin = $login;
		$this->_userData = $user;
		return true;	
	}

}

?>
