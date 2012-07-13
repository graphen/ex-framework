<?php

/**
 * @class MailPhp
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class MailPhp implements IMail {
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 *
	 */
	public function __construct() {	
		//
	}
	
	/**
	 * Wysyla wiadomosc wykorzystujac funkcje PHP mail()
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
		if(is_array($mailTo)) {
			$mailTo = implode(',', $mailTo);
		}
		if(!mail($mailTo, $mailSubject, $mailBody, $mailHeaders)) {
			throw new MailerException('List e-mail nie moze zostac wyslany');
		}
	}
	
}

?>
