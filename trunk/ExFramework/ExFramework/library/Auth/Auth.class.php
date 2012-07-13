<?php

/**
 * @class Auth
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Auth implements IAuth {
	
	/**
	 * Obiekt zadania
	 *
	 * @var object
	 * 
	 */		
	protected $_request = null;
	
	/**
	 * Obiekt sesji
	 *
	 * @var object
	 * 
	 */		
	protected $_session = null;
	
	/**
	 * Obiekt adaptera uwierzytelniania
	 *
	 * @var object
	 * 
	 */		
	protected $_authAdapter = null;
	
	/**
	 * Obiekt tworzacy adaptery uwierzytelniania
	 *
	 * @var object
	 * 
	 */		
	protected $_authAdapterFactory = null;
	
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
	 * 
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt zadania
	 * @param object Obiekt sesji
	 * @param object Obiekt adaptera uwierzytelniania
	 * @param object Obiekt fabryczny dla adapterow uwierzytelniania
	 * 
	 */		
	public function __construct(IRequest $request, ISession $session, IAuthAdapter $authAdapter, IFactory $authAdapterFactory) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_authAdapter = $authAdapter;
		$this->_authAdapterFactory = $authAdapterFactory;
	}
	
	/**
	 * 
	 * Przeprowadza logowanie uzytkownika
	 * 
	 * @access public
	 * @param string login
	 * @param string haslo
	 * @param string Metoda szyfrowania hasla 
	 * @return bool
	 * 
	 */		
	public function login($userLogin, $userPass=null, $authMethod=null) {
		$this->_errorCode = null;
		$this->_errorMessage = null;
		$ret = false;
		if($authMethod == null) {
			$ret = $this->_authAdapter->auth($userLogin, $userPass);
		}
		else {
			$this->_authAdapter = $this->_authAdapterFactory->create('AuthAdapter'.ucfirst($authMethod));
			$ret = $this->_authAdapter->auth($userLogin, $userPass);
		}
		if($ret === true) {
			$this->_session->userLogin = $this->_authAdapter->getUserLogin();
			$this->_session->userData = $this->_authAdapter->getUserData();
			$this->_session->userIp = $this->_request->getIp(); 
			$this->_session->userAgent = $this->_request->getUserAgent(); 
			$this->_session->userSessionStartTime = time(); 
			$this->_session->userIsAuth = true;
			$this->_session->userInLocalDb = true;
			$this->_session->userHash = md5($this->_authAdapter->getUserLogin() . $this->_request->getIp());
			return true;
		}
		else {
			$this->_errorCode = $this->_authAdapter->getErrorCode();
			$this->_errorMessage = $this->_authAdapter->getErrorMessage();
			return false;
		}
	} 
	
	/**
	 * 
	 * Przeprowadza weryfikacje zalogowanego uzytkownika
	 * 
	 * @access public
	 * @return bool
	 * 
	 */		
	public function check() {
		if((isset($this->_session->userIsAuth)) && ($this->_session->userIsAuth === true) && isset($this->_session->userLogin)) {
			if(($this->_session->userIp === $this->_request->getIp()) && ($this->_session->userAgent === $this->_request->getUserAgent())) {
				$this->_session->userIsAuth = true; //jesli nie zapisze cos w sesji to moze zostac zniszczona przez garbage collector kiedy uplynie czas sesji
				return true;				
			}
			$this->_errorCode = 6;
			$this->_errorMessage = 'User has changed their connection properities';
			$this->_session->destroy();
			$this->_session->userIsAuth = false;
			return false;
		}
		$this->_errorCode = 5;
		$this->_errorMessage = 'User is not authorized';
		return false;
	}
	
	/**
	 * 
	 * Wylogowuje uzytkownika
	 * 
	 * @access public
	 * @return bool
	 * 
	 */		
	public function logout() {
		$this->_session->destroy();
		return true;
	}
	
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
	 * Zwraca komunikat bledu
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
	 * Zwraca obiekt adaptera uwierzytelniania
	 * 
	 * @access public
	 * @return object
	 * 
	 */			
	public function getAuthAdapter() {
		return $this->_authAdapter;
	}
	
	/**
	 * 
	 * Zwraca login zalogowanego zuywtkownika
	 * 
	 * @access public
	 * @return string
	 * 
	 */			
	public function getUserLogin() {
		return (!empty($this->_session->userLogin)) ? $this->_session->userLogin : null;
	}
	
	/**
	 * 
	 * Zwraca obiekt z danymi zalogowanego uzytkownika
	 * 
	 * @access public
	 * @return object
	 * 
	 */			
	public function getUserData() {
		return (!empty($this->_session->userData)) ? $this->_session->userData : null;
	}	
	
}

?>
