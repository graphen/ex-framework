<?php

/**
 * @interface IMailer
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IMailer {

	/**
	 * Dodaje adres (adresy) odbiorcow
	 * 
	 * @access public
	 * @param mixed //string or array Adres lub adresy email
	 * @return void
	 *
	 */
	public function addTo($mailTo);
	
	/**
	 * Dodaje adres (adresy) odbiorcow kopii listow
	 * 
	 * @access public
	 * @param mixed //string or array Adres lub adresy email
	 * @return void
	 *
	 */
	public function addCc($mailCc);
	
	/**
	 * Dodaje adres (adresy) odbiorcow ukrytych kopii listow
	 * 
	 * @access public
	 * @param mixed //string or array Adres lub adresy email
	 * @return void
	 *
	 */
	public function addBcc($mailBcc);
	
	/**
	 * Dodaje adres na ktory ma przychodzic odpowiedz
	 * 
	 * @access public
	 * @param string Adres email
	 * @param string Nazwa odbiorcy
	 * @return void
	 *
	 */
	public function addReplyTo($mailReplyTo, $name=null);
	
	/**
	 * Dodaje temat wiadomosci
	 * 
	 * @access public
	 * @param string Temat wiadomosci
	 * @return void
	 *
	 */
	public function addSubject($mailSubject='');

	/**
	 * Dodaje adres nadawcy
	 * 
	 * @access public
	 * @param string Adres email nadawcy
	 * @param string Nazwa nadawcy
	 * @return void
	 *
	 */
	public function addFrom($mailFrom, $name=null);
	
	/**
	 * Dodaje tresc wiadomosci
	 * 
	 * @access public
	 * @param string Tresc wiadomosci
	 * @return void
	 *
	 */
	public function addBody($mailBody);
	
	/**
	 * Dodaje zalacznik do wiadomosci
	 * 
	 * @access public
	 * @param string Nazwa pliku
	 * @param string Sposob dolaczenia pliku - zalacznik lub w ciele wiadomosci
	 * @param string Typ pliku
	 * @return void
	 *
	 */
	public function addFile($fileName, $disposition='attachment', $fileType=null);
	
	/**
	 * Ustawia typ wiadomosci tekstowej
	 * 
	 * @access public
	 * @param string Typ wiadomosci
	 * @return void
	 *
	 */
	public function setMailTextType($mailType='text');
	
	/**
	 * Ustawia kodowanie wiadomosci
	 * 
	 * @access public
	 * @param string Kodowanie
	 * @return void
	 *
	 */
	public function setMailCharset($mailCharset='utf-8');
	
	/**
	 * Ustawia priorytet wiadomosci
	 * 
	 * @access public
	 * @param integer Priorytet
	 * @return void
	 *
	 */
	public function setPriority($priority);
	
	/**
	 * Ustawia priorytet wiadomosci tekstowo
	 * 
	 * @access public
	 * @param string Priorytet wiadomosci
	 * @return void
	 *
	 */
	public function setMsMailPriority($msMailPriority='Normal');
		
	/**
	 * Ustawia sposob kodowania czystotekstowej wiadomosci 
	 * 
	 * @access public
	 * @param string Kodowanie
	 * @return void
	 *
	 */
	public function setTextPlainEncoding($encoding='quoted-printable');
	
	/**
	 * Ustawia sposob kodowania wiadomosci html 
	 * 
	 * @access public
	 * @param string Kodowanie
	 * @return void
	 *
	 */	
	public function setTextHtmlEncoding($encoding='quoted-printable');
	
	/**
	 * Ustawia sposob kodowania zalacznikow 
	 * 
	 * @access public
	 * @param string Kodowanie
	 * @return void
	 *
	 */	
	public function setFileEncoding($encoding='base64');
	
	/**
	 * Ustawia znak konca linii
	 * 
	 * @access public
	 * @param string Znak konca linii
	 * @return void
	 *
	 */
	public function setEndString($endl='rn');
	
	/**
	 * Ustawia tekst informacji dla klientow nie obslugujacych wiadomosci MIME
	 * 
	 * @access public
	 * @param string Informacja dla klientow o wykorzystaniu MIME
	 * @return void
	 *
	 */
	public function setMimeInfo($info='');
	
	/**
	 * Ustawia czy wiadomosc ma miec ograniczona dlugosc linii
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie lamania linii wiadomosci
	 * @return void
	 *
	 */
	public function setWordWrap($wrap=false);

	/**
	 * Ustawia czy wiadomosc ma byc wyslana jako wieloczesciowa
	 * 
	 * @access public
	 * @param bool Wiadomosc wieloczesciowa lub nie
	 * @return void
	 *
	 */
	public function setSendMultipart($multipart=false);
	
	/**
	 * Ustawia alternatywny tekst dla wiadomosci
	 * 
	 * @access public
	 * @param string Tekst alternatywny wiadomosci
	 * @return void
	 *
	 */
	public function setAlternativeText($txt='');
	
	/**
	 * Ustawia sposob dolaczeina pliku do wiadomosci
	 * 
	 * @access public
	 * @param string Sposob dolaczenia pliku mixed lub related
	 * @return void
	 *
	 */
	public function setMimeMultipart($multipart='mixed');
	
	/**
	 * Pobiera ustawiony typ tekstowej wiadomosci
	 * 
	 * @access public
	 * @return string Typ wiadomosci
	 *
	 */
	public function getMailTextMimeType();
	
	/**
	 * Wysyla wiadomosc
	 * 
	 * @access public
	 * @return void
	 *
	 */
	public function send();
	
	/**
	 * Resetuje niektore pola obiektu, aby mozna bylo wyslac nowa wiadomosc
	 * 
	 * @access public
	 * @return void
	 *
	 */	
	public function reset();
	
}

?>
