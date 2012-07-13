<?php

/**
 * @class MailSendmail
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class MailSendmail implements IMail {
	
	/**
	 * Sciezka do Sendmaila
	 * 
	 * @var string
	 * 
	 */	
	protected $_sendmailPath = '/usr/sbin/sendmail';
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param string Sceizka do serwera Sendmail
	 *
	 */
	public function __construct($sendmailPath='') {
		$this->setSendmailPath($sendmailPath);
	}	
	
	/**
	 * Ustawia sciezke do pliku wykonywalnego sendmaila
	 * 
	 * @access public
	 * @param string Sciezka do uslugi Sendmail
	 * @return void
	 *
	 */
	public function setSendmailPath($path=null) {
		if(!empty($path)) {
			$this->_sendmailPath = $path;
		}
	}
		
	/**
	 * Wysyla wiadomosc wykorzystujac serwer Sendmail
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
		if(!file_exists($this->_sendmailPath)) {
			throw new MailerException('Sendmail jest niedostepny');			
		}
		if(!$pointer = @popen($this->_sendmailPath . ' -oi -f ' . $mailFrom . ' -t', 'w')) {
			throw new MailerException('Email nie moze zostac wyslany poprzez serwer Sendmail');
		}
		fputs($pointer, $mailHeaders);
		fputs($pointer, $mailBody);
		$result = pclose($pointer);
		if($result != 0) {
			throw new MailerException('Email nie moze zostac wyslany poprzez serwer Sendmail');			
		}	
	}		
	
}

?>
