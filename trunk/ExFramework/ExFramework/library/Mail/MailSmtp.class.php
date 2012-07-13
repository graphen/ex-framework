<?php

/**
 * @class MailSmtp
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class MailSmtp implements IMail {
	
	/**
	 * Adres serwera smtp
	 * 
	 * @var string
	 * 
	 */	
	protected $_smtpHost = '';
	
	/**
	 * Nazwa uzytkownika serwera smtp
	 * 
	 * @var string
	 * 
	 */	
	protected $_smtpUser = '';
	
	/**
	 * Haslo uzytkownika smtp
	 * 
	 * @var string
	 * 
	 */	
	protected $_smtpPass = '';
	
	/**
	 * Czas oczekiwania na odpowiedz serwera smtp
	 * 
	 * @var integer
	 * 
	 */	
	protected $_smtpTimeout = 45;
	
	/**
	 * Port na ktorym nasluchuje serwer smtp
	 * 
	 * @var integer
	 * 
	 */	
	protected $_smtpPort = 587;
	
	/**
	 * Czy serwer smtp wymaga autoryzacji
	 * 
	 * @var boolean
	 * 
	 */	
	protected $_smtpAuth = false;
	
	/**
	 * Zasob polaczenia z serwerem smtp
	 * 
	 * @var resource
	 * 
	 */	
	protected $_smtpConnectionLink = null;

	/**
	 * Sposob kodowania email
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailEncoding = 'quoted-printable';
	
	/**
	 * Znak konca linii
	 * 
	 * @var string
	 * 
	 */
	 protected $_endString = "\r\n";
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie autentykacji SMTP
	 * @param string Nazwa uzytkownika 
	 * @param string Haslo do serwera
	 * @param int Czas oczekiwania
	 * @param string Adres hosta SMTP
	 * @param int Numer portu SMTP
	 * @param string Kodowanie tekstu
	 * 
	 */
	public function __construct($smtpAuth=false, $smtpUser='', $smtpPass='', $smtpTimeout=45, $smtpHost='', $smtpPort=587, $textEncoding='') {
		 $this->setSmtpAuth($smtpAuth);
		 $this->setSmtpUser($smtpUser);
		 $this->setSmtpPass($smtpPass);
		 $this->setSmtpTimeout($smtpTimeout);
		 $this->setSmtpHost($smtpHost);
		 $this->setSmtpPort($smtpPort);
		 $this->setMailEncoding($textEncoding);
	}
	
	
	/**
	 * Ustawia wymaganie lub brak koniecznosci logowania dla uslugi SMTP
	 * 
	 * @access public
	 * @param bool //Ustawic na true jelsi SMTP wymaga logowania
	 * @return void
	 *
	 */
	public function setSmtpAuth($auth=false) {
		$this->_smtpAuth = (bool)$auth;
	}
	
	/**
	 * Ustawia adres hosta dla uslugi SMTP
	 * 
	 * @access public
	 * @param string Adres hosta
	 * @return void
	 *
	 */
	public function setSmtpHost($host='') {
		if(!empty($host)) {
			$this->_smtpHost = $host;
		}
	}
	
	/**
	 * Ustawia nazwe uzytkownika dla uslugi SMTP
	 * 
	 * @access public
	 * @param string Nazwa uzytkownika
	 * @return void
	 *
	 */
	public function setSmtpUser($user='') {
		if(!empty($user)) {
			$this->_smtpUser = $user;
		}
	}
	
	/**
	 * Ustawia haslo uzytkownika dla uslugi SMTP
	 * 
	 * @access public
	 * @param string Haslo
	 * @return void
	 *
	 */
	public function setSmtpPass($pass='') {
		if(!empty($pass)) {
			$this->_smtpPass = $pass;
		}
	}
	
	/**
	 * Ustawia czas oczekiwania dla uslugi SMTP
	 * 
	 * @access public
	 * @param integer Czas oczekiwania
	 * @return void
	 *
	 */
	public function setSmtpTimeout($timeout=45) {
		$this->_smtpTimeout = (int)$timeout;
	}
	
	/**
	 * Ustawia port dla uslugi SMTP
	 * 
	 * @access public
	 * @param integer Port
	 * @return void
	 *
	 */
	public function setSmtpPort($port=587) {
		$this->_smtpPort = (int)$port;
	}	
	
	/**
	 * Ustawia sposob kodowania wiadomosci
	 * 
	 * @access public
	 * @param string sposob kodowania wiadomosci
	 * @return void
	 *
	 */
	public function setMailEncoding($enc='quoted-printable') {
		if(!empty($encoding)) {		
			$this->_mailEncoding = (string)$enc;
		}
	}		
	
	/**
	 * Ustawia znak konca linii
	 * 
	 * @access public
	 * @param string Znak konca linii
	 * @return void
	 *
	 */
	public function setEndString($endl='rn') {
		if($endl='rn') {
			$this->_endString = "\r\n"; 
		}
		elseif($endl='n') {
			$this->_endString = "\n";
		}
		elseif($endl='r') {
			$this->_endString = "\r";
		}
		else {
			$this->_endString = "\r\n";
		}
	}	
	
	/**
	 * Pobiera nazwe hosta
	 * 
	 * @access public
	 * @return string Nazwa hosta
	 *
	 */	
	public function getHostName() {
		if(isset($_SERVER['SERVER_NAME'])) {
			return $_SERVER['SERVER_NAME']; 
		}
		else {
			return 'localhost.localdomain';
		}
	}
	
	/**
	 * Wysyla wiadomosc wykorzystujac SMTP
	 * 
	 * @access public
	 * @param mixed Adres odbiorcy lub tablica adresow odbiorcow
	 * @param mixed Adres odbiorcy lub tablica adresow odbiorcow gdzie zostana wyslane kopie
	 * @param mixed Adres odbiorcy lub tablica adresow odbiorcow gdzie zostana wyslane kopie ukryte
	 * @param string Temat listu
	 * @param string Cialo listu
	 * @param string Naglowki o ile sa oddzielnie
	 * @param string Adres nadawcy
	 * @return void
	 *
	 */
	public function send($mailTo, $mailCc, $mailBcc, $mailSubject, $mailBody, $mailHeaders, $mailFrom) {
		if(is_string($mailTo)) {
			$mailToArr = explode(',', $mailTo);
		}
		else {
			$mailToArr = $mailTo;
		}
		if(is_string($mailCc)) {
			$mailCcArr = explode(',', $mailCc);
		}
		else {
			$mailCcArr = $mailCc;
		}
		if(is_string($mailBcc)) {
			$mailBccArr = explode(',', $mailBcc);
		}
		else {
			$mailBccArr = $mailBcc;
		}
		$recipients = array_merge($mailToArr, $mailCcArr, $mailBccArr);
		foreach($recipients AS $index=>$value) {
			if(empty($value)) {
				unset($recipients[$index]);
			}
		}
		$body = preg_replace("/^\./", "..\\1", $mailBody); //przed kazda linia zaczynajaca sie od kropki dodajemy kropke
		
		$this->smtpConnect(); //Moze rzucic wyjatkiem
		$this->smtpAuthenticate(); //Moze rzucic wyjatkiem
		$this->smtpSendCommand('FROM', $mailFrom); //Moze rzucic wyjatkiem
		foreach($recipients as $email) {
			$this->smtpSendCommand('TO', $email); //Moze rzucic wyjatkiem
		}
		$this->smtpSendCommand('DATA'); //Moze rzucic wyjatkiem
		$this->smtpSendData($mailHeaders . $body); //Moze rzucic wyjatkiem
		$this->smtpSendData('.'); //Moze rzucic wyjatkiem
		$result = $this->smtpGetData(); 
		if(substr($result, 0, 3) != 250) {
			throw new MailerException('Nie mozna wyslac listu e-mail poprzez serwer SMTP');
		}
		//$this->smtpSendCommand('RESET'); //Moze rzucic wyjatkiem
		$this->smtpSendCommand('QUIT'); //Moze rzucic wyjatkiem
	}
	
	/**
	 * Nawiazuje polaczenie z serwerem SMTP
	 * 
	 * @access protected
	 * @return void
	 *
	 */
	protected function smtpConnect() {
		if($this->_smtpHost == '') {
			throw new MailerException('Brak danych do polaczenia z serwerem SMTP');
		}
		$this->_smtpConnectionLink = fsockopen($this->_smtpHost, $this->_smtpPort, $errno, $err, $this->_smtpTimeout);
		$result = $this->smtpGetData();
		if(!is_resource($this->_smtpConnectionLink)) {
			throw new MailerException('Nie mozna nawiazac polaczenia z serwerem SMTP (' . $result . '). Blad: '. $err, $errno);
		}
		$this->smtpSendCommand('HELLO'); //Moze rzucic wyjatkiem
	}
	
	/**
	 * Przeprowadza autentykacje na serwerze sMTP
	 * 
	 * @access protected
	 * @return void
	 *
	 */
	protected function smtpAuthenticate() {
		if($this->_smtpUser == '' OR $this->_smtpPass == '') {
			if($this->_smtpAuth == false) {
				return;
			}
			else {
				throw new MailerException('Brak nazwy uzytkownika lub hasla');
			}
		}
		$this->smtpSendData('AUTH LOGIN'); //Moze rzucic wyjatkiem
		$result = $this->smtpGetData(); 
 		if(substr($result, 0, 3) != 334) {
			throw new MailerException('Nie mozna sie zalogowac do serwera SMTP');
		}
		$this->smtpSendData(base64_encode($this->_smtpUser)); //Moze rzucic wyjatkiem
		$result = $this->smtpGetData();
		if(substr($result, 0, 3) != 334) {
			throw new MailerException('Nie mozna sie zalogowac do serwera SMTP. Bledny login');
		}
		$this->smtpSendData(base64_encode($this->_smtpPass)); //Moze rzucic wyjatkiem
		$result = $this->smtpGetData();
		if(substr($result, 0, 3) != 235) {
			throw new MailerException('Nie mozna sie zalogowac do serwera SMTP. Bledne haslo');
		}
	}
	
	/**
	 * Wysyla komende do serwera SMTP
	 * 
	 * @access protected
	 * @param string Komenda
	 * @param string Dane do wyslania
	 * @return void
	 *
	 */
	protected function smtpSendCommand($command, $addData='') {
		if($command == 'HELLO') {
			$hostName = $this->getHostname();
			if($this->_smtpAuth === true || $this->_mailEncoding == '8bit') {
				$this->smtpSendData('EHLO ' . $hostName); //Moze rzucic wyjatkiem
			}
			else {
				$this->smtpSendData('HELO ' . $hostName); //Moze rzucic wyjatkiem
			}
			$resultCode = 250;
		}
		elseif($command == 'FROM') {
			$this->smtpSendData('MAIL FROM: <' . $addData . '>'); //Moze rzucic wyjatkiem
			$resultCode = 250;
		}
		elseif($command == 'TO') {
			$this->smtpSendData('RCPT TO: <' . $addData . '>'); //Moze rzucic wyjatkiem
			$resultCode = 250;
		}
		elseif($command == 'RESET') {
			$this->smtpSendData('RSET '); //Moze rzucic wyjatkiem
			$resultCode = 250;
		}		
		elseif($command == 'DATA') {
			$this->smtpSendData('DATA'); //Moze rzucic wyjatkiem
			$resultCode = 354;
		}
		elseif($command == 'QUIT') {
			$this->smtpSendData('QUIT '); //Moze rzucic wyjatkiem
			$resultCode = 221;
		}
		else {
			throw new MailerException('Nieznane polecenie');			
		}
		$result = $this->smtpGetData();
		if(substr($result, 0, 3) != $resultCode) {
			throw new MailerException('Serwer SMTP zwrocil blad: ' . (int)$result . ', podczas wykonywania polecenia', (int)$result);
		}
		if($command == 'QUIT') {
			fclose($this->_smtpConnectionLink);
		}		
	}
	
	/**
	 * Wysyla dane do serwera SMTP
	 * 
	 * @access protected
	 * @param string Dane do wyslania
	 * @return void
	 *
	 */
	protected function smtpSendData($data) {
		$data .= $this->_endString;
		if(!fwrite($this->_smtpConnectionLink, $data)) {
			throw new MailerException('Nie mozna wyslac danych do serwera SMTP');
		}
	}
	
	/**
	 * Pobiera dane z serwera SMTP
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function smtpGetData() {
		$data = '';
		while($line = fgets($this->_smtpConnectionLink, 512)) {
			if($line === false) {
				throw new MailerException('Nie mozna uzyskac odpowiedzi serwera SMTP');
			}
			$data .= $line;
			if(substr($line, 3, 1) == ' ') { //jesli jest wiecej linii czwarty znak to '-'
				break;
			}
		}
		return $data;
	}
	
}

?>
