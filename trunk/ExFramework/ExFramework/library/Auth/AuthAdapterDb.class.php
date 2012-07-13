<?php

/**
 * @class AuthAdapterDb
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class AuthAdapterDb extends AuthAdapterAbstract implements IAuthAdapter {
	
	/**
	 * Obiekt dostepu do bazy danych
	 *
	 * @var object
	 * 
	 */		
	protected $_db = null;
	
	/**
	 * Nazwa tabeli z uzytkownikami
	 *
	 * @var string
	 * 
	 */		
	protected $_userTableName = null;
	
	/**
	 * Nazwa dla loginu
	 *
	 * @var string
	 * 
	 */		
	protected $_loginColumnName = null;
	
	/**
	 * Nazwa kolumny dla hasla
	 *
	 * @var string
	 * 
	 */		
	protected $_passwordColumnName = null;
	
	/**
	 * Nazwa kolumny dla statusu
	 *
	 * @var string
	 * 
	 */		
	protected $_statusColumnName = null;
	
	/**
	 * 
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt dostepu do bazy danych
	 * @param string Nazwa tabeli uzytkownikow
	 * @param string Nazwa kolumny dla loginu
	 * @param string Nazwa kolumny dla hasla
	 * @param string Nazwa kolumny dla statusu
	 * @param string Metoda szyfrowania hasla
	 * 
	 */		
	public function __construct(IDb $db, $userTableName, $loginColumnName, $passwordColumnName, $statusColumnName='status', $passwordCryptMethod='plain') {
		$this->_db = $db;
		$this->_userTableName = $userTableName;
		$this->_loginColumnName = $loginColumnName;
		$this->_passwordColumnName = $passwordColumnName;
		$this->_statusColumnName = $statusColumnName;
		$this->_passwordCryptMethod = $passwordCryptMethod;
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
		if(($this->_userTableName == '') || ($this->_loginColumnName == '') || ($this->_passwordColumnName == '') || ($this->_statusColumnName == '')) {
			throw new AuthException('Obiekt AuthAdapter nie zostal poprawnie zainicjowany');			
		}
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
		$sql = "SELECT * FROM `" . $this->_userTableName . "` WHERE `" . $this->_loginColumnName . "`=:login";
		$stm = $this->_db->prepare($sql);
		$stm->execute(array(':login'=>$login));
		$dataCount = $stm->rowCount();

		if($dataCount == 0) {
			$this->_errorCode = 1;
			$this->_errorMessage = "User does not exist";
			return false;			
		}
		elseif(($dataCount != 1)) {
			throw new AuthException('Nie moze istniec dwoch uzytkownikow o tym samym loginie');
		}
		else {	
			$row = $stm->fetchAll();
			$data = $row[0];
			if($this->_statusColumnName != null) {
				$status = $data[$this->_statusColumnName]; 
				if($status == 0) {
					$this->_errorCode = 3;
					$this->_errorMessage = "User acount is not activated";
					return false;			
				}				
			}
			if($data[$this->_passwordColumnName] === $psw) {
				$user = new StdClass();
				foreach($data AS $index => $value) {
					$user->{$index} = $value;
				}
				$this->_userLogin = $data[$this->_loginColumnName];
				$this->_userData = $user;
				return true;
			}
			else {
				$this->_error = 2;
				$this->_errorMessage = "Invalid password";
				return false;				
			}
		}
	}
	
}

?>
