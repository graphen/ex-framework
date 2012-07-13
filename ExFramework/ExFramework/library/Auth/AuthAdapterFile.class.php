<?php

/**
 * @class AuthAdapterFile
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class AuthAdapterFile extends AuthAdapterAbstract implements IAuthAdapter {
	
	/**
	 * Sciezka do pliku
	 *
	 * @var string
	 * 
	 */			
	protected $_filePath = null;
	
	/**
	 * Numer kolumny dla loginu
	 *
	 * @var int
	 * 
	 */		
	protected $_loginColumnNumber = null;
	
	/**
	 * Numer kolumny dla hasla
	 *
	 * @var int
	 * 
	 */		
	protected $_passwordColumnNumber = null;
	
	/**
	 * Numer kolumny dla statusu
	 *
	 * @var int
	 * 
	 */		
	protected $_statusColumnNumber = null;
	
	/**
	 * Znak rozdzielajacy kolumny
	 *
	 * @var string
	 * 
	 */		
	protected $_delimiter = null;
	
	/**
	 * 
	 * Konstruktor
	 * 
	 * @access public
	 * @param string Sceizka do pliku
	 * @param string Numer kolumny dla loginu
	 * @param string Numer kolumny dla hasla
	 * @param string Numer kolumny dla statusu
	 * @param string Znak odstepu
	 * @param string Metoda szyfrowania hasla
	 * 
	 */			
	public function __construct($filePath, $loginColumnNumber, $passwordColumnNumber, $statusColumnNumber=null, $delimiter=':', $passwordCryptMethod='plain') {
		$this->_filePath = $filePath;
		$this->_loginColumnNumber = $loginColumnNumber;
		$this->_passwordColumnNumber = $passwordColumnNumber;
		$this->_statusColumnNumber = $idColumnNumber;
		$this->_delimiter = $delimiter;
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
		if(($this->_filePath == '') || ($this->_loginColumnNumber == '') || ($this->_passwordColumnNumber == '') || ($this->_statusColumnNumber == '') || ($this->_delimiter == '')) {
			throw new AuthException('Obiekt AuthAdapter nie zostal poprawnie zainicjowany');
		}
		$this->_errorCode = null;
		$this->_errorMessage = null;		
		if(empty($login)) {
			$this->_error = 1;
			$this->_errorMessage = "User does not exist";
			return false;			
		}
		if(empty($password)) {
			$this->_error = 2;
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
		if(!($filePointer = fopen($filePath, 'r'))) {
			throw new AuthException('Nie mozna otworzyc do odczytu pliku: ' . $this->_filePath);
		}
		while(($data = fgetcsv($filePointer, 1024, $this->_delimiter)) !== false) {
			if($data[$this->_loginColumnNumber] === $login) {
				if($data[$this->_passwordColumnNumber] === $psw) {
					fclose($filePointer);
					$user = new StdClass();
					$user->login = $data[$this->_loginColumnNumber];
					$user->password = $data[$this->_passwordColumnNumber];
					if($this->_statusColumnNumber != null) {
						if($data[$this->_statusColumnNumber] != 1) {
							$this->_errorCode = 3;
							$this->_errorMessage = "User acount is not activated";
							return false;
						}				
					}
					else {
						$user->status = $data[$this->_statusColumnNumber];
					}
					$this->_userData = $user;
					$this->_userLogin = $data[$this->_loginColumnNumber];
					return true;
				}
				else {
					fclose($filePointer);
					$this->_errorCode = 2;
					$this->_errorMessage = "Invalid password";
					return false;
				}
			}
		}
		fclose($filePointer);
		$this->_errorCode = 1;
		$this->_errorMessage = "User does not exist";
		return false;
	}
	
}

?>
