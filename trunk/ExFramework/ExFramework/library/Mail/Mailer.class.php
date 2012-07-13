<?php

/**
 * @class Mailer
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Mailer implements IMailer {
	
	/**
	 * Tablica z adresami adresatow
	 * 
	 * @var array
	 * 
	 */
	protected $_mailTo = array();
	
	/**
	 * Tablica z adresatow do ktorych beda wyslane kopie
	 * 
	 * @var array
	 * 
	 */
	protected $_mailCc = array();

	/**
	 * Tablica z adresatow do ktorych beda wyslane ukryte kopie
	 * 
	 * @var array
	 * 
	 */
	protected $_mailBcc = array();

	/**
	 * Temat wiadomosci
	 * 
	 * @var string
	 * 
	 */
	protected $_mailSubject = '';

	/**
	 * Tresc wiadomosci
	 * 
	 * @var string
	 * 
	 */
	protected $_mailBody = '';

	/**
	 * Adres na ktory mozna odpowiedziec
	 * 
	 * @var string
	 * 
	 */	 
	protected $_mailReplyTo = '';

	/**
	 * Nazwa przypisana do powyzszego adresu
	 * 
	 * @var string
	 * 
	 */	 
	protected $_mailReplyToName = '';
	
	/**
	 * Adres nadawcy
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailFrom = '';

	/**
	 * Nazwa przypisana do powyzszego adresu
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailFromName = '';

	/**
	 * Tablica z nazwami i typami plikow
	 * 
	 * @var array
	 * 
	 */	
	protected $_mailFiles = array();
	
	/**
	 * Typ wiadomosci: text/html
	 * 
	 * @var string
	 * 
	 */		
	protected $_mailTextType = 'text';

	/**
	 * Charset
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailCharset = 'utf-8';

	/**
	 * Priorytet wiadomosci, w skali 1-5
	 * 
	 * @var integer
	 * 
	 */
	protected $_mailPriority = 3;
	
	/**
	 * Priorytet wiadomosci (Lowest, Low, Normal, High, Highest)
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailMsMailPriority = 'Normal';
	
	/**
	 * Alternatywny tekst wiadomosci
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailAlternativeText = '';
	
	/**
	 * Protokol mail/smtp/sendmailyy
	 * 
	 * @var string
	 * 
	 */
	protected $_mailProtocol = 'mail';
		
	/**
	 * Znak konca linii: \n lub \r\n
	 * 
	 * @var string
	 * 
	 */	
	protected $_endString = "\r\n";
	
	/**
	 * Lamanie wierszy
	 * 
	 * @var boolean
	 * 
	 */
	protected $_wordWrap = false;	
		
	/**
	 * Wiadomosc wieloczesciowa lub jednoczesciowa
	 * 
	 * @var boolean
	 * 
	 */	
	protected $_mailMultipart = false;
	
	/**
	 * Tresc informacji wyswietlanej w kliencie jesli nie jest w stanie otworzyc wiadomosci wieloczesciowej
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailMimeInfo = 'This is multipart message in MIME format.';
	
	/**
	 * Typ wiadomosci wieloczesciowej
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailMimeTypeMultipart = 'mixed'; //related	
	
	/**
	 * Nazwa programu wysylajacego
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailMailer = 'ExMailer';
	
	/**
	 * Wersja protokolu MIME
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailMimeVersion = 'MIME-Version: 1.0';
		
	/**
	 * Domyslny typ nieznanego pliku
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailMimeFileTypeDefault = 'application/x-unknown-content-type'; //'application/octet-stream'
	
	/**
	 * Domyslny typ wiadomosci tekstowej
	 * 
	 * @var string
	 * 
	 */
	protected $_mailMimeTextTypeDefault = 'text/plain';
	
	/**
	 * Unikatowy ciag dla wiadomosci wieloczesciowej
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailAlternativeBoundary = '';
	
	/**
	 * Unikatowy cig dla wiadomosci z zalacznikami
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailFileBoundary = '';
	
	/**
	 * Sposob kodowania plikow
	 * 
	 * @var string
	 * 
	 */
	protected $_mailFileContentTransferEncoding = 'base64';
	
	/**
	 * Sposob kodowania tresci wiadomosci czysto tekstowej
	 * 
	 * @var string
	 * 
	 */
	protected $_mailTextPlainContentTransferEncoding = 'quoted-printable';
	
	/**
	 * Sposob kodowania tresci wiadomosci 
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailTextHtmlContentTransferEncoding = 'quoted-printable';
	
	/**
	 * Przetworzona i gotowa do wyslania tresc wiadomosci
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailPreparedBody = '';
	
	/**
	 * Przetworzony i gotowy do wyslania zestaw naglowkow
	 * 
	 * @var string
	 * 
	 */	
	protected $_mailPreparedHeaders = '';
	
	/**
	 * Obiekt wysylajacy poczte
	 * 
	 * @var object
	 * 
	 */
	 protected $_mailTransporter = null;
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object IMail Transporter 
	 * @param string Kodowanie tekstu
	 *
	 */
	public function __construct(IMail $mailTransporter, $textEncoding='') {
		$this->_mailTransporter = $mailTransporter;
		if($this->_mailTransporter instanceof MailPhp) {
			$this->_mailProtocol = 'mail';
		}
		elseif($this->_mailTransporter instanceof MailSendmail) {
			$this->_mailProtocol = 'sendmail';
		}
		elseif($this->_mailTransporter instanceof MailSmtp) {
			$this->_mailProtocol = 'smtp';
		}
		$this->setTextHtmlEncoding($textEncoding);
		$this->setTextPlainEncoding($textEncoding);
		$this->makeMailBoundaries();
	}

	/**
	 * Zwraca typ MIME pliku na podstawie jego rozszerzenia
	 * 
	 * @access protected
	 * @param string Rozszerzenie pliku
	 * @return string
	 *
	 */
	protected function mimeType($extension) {
		$extension = strtolower($extension);
		$mimeTypes = array(
			'ez'		=> 'application/andrew-inset',
			'hqx'		=> 'application/mac-binhex40',
			'cpt'		=> 'application/mac-compactpro',
			'doc'		=> 'application/msword',
			'bin'		=> 'application/octet-stream',
			'dms'		=> 'application/octet-stream',
			'lha'		=> 'application/octet-stream',
			'lzh'		=> 'application/octet-stream',
			'exe'		=> 'application/octet-stream',
			'class'		=> 'application/octet-stream',
			'so'		=> 'application/octet-stream',
			'dll'		=> 'application/octet-stream',
			'oda'		=> 'application/oda',
			'odc'		=> 'application/vnd.oasis.opendocument.chart',
			'odf'		=> 'application/vnd.oasis.opendocument.formula',
			'odg'		=> 'application/vnd.oasis.opendocument.graphics',
			'odi'		=> 'application/vnd.oasis.opendocument.image',
			'odm'		=> 'application/vnd.oasis.opendocument.text-master',
			'odp'		=> 'application/vnd.oasis.opendocument.presentation',
			'ods'		=> 'application/vnd.oasis.opendocument.spreadsheet',
			'odt'		=> 'application/vnd.oasis.opendocument.text',			
			'pdf'		=> 'application/pdf',
			'ai'		=> 'application/postscript',
			'eps'		=> 'application/postscript',
			'ps'		=> 'application/postscript',
			'smi'		=> 'application/smil',
			'smil'		=> 'application/smil',
			'mif'		=> 'application/vnd.mif',
			'xls'		=> 'application/vnd.ms-excel',
			'ppt'		=> 'application/vnd.ms-powerpoint',
			'wbxml'		=> 'application/vnd.wap.wbxml',
			'wmlc'		=> 'application/vnd.wap.wmlc',
			'wmlsc'		=> 'application/vnd.wap.wmlscriptc',
			'bcpio'		=> 'application/x-bcpio',
			'vcd'		=> 'application/x-cdlink',
			'pgn'		=> 'application/x-chess-pgn',
			'cpio'		=> 'application/x-cpio',
			'csh'		=> 'application/x-csh',
			'dcr'		=> 'application/x-director',
			'dir'		=> 'application/x-director',
			'dxr'		=> 'application/x-director',
			'dvi'		=> 'application/x-dvi',
			'spl'		=> 'application/x-futuresplash',
			'gtar'		=> 'application/x-gtar',
			'hdf'		=> 'application/x-hdf',
			'js'		=> 'application/x-javascript',
			'skp'		=> 'application/x-koan',
			'skd'		=> 'application/x-koan',
			'skt'		=> 'application/x-koan',
			'skm'		=> 'application/x-koan',
			'latex'		=> 'application/x-latex',
			'nc'		=> 'application/x-netcdf',
			'cdf'		=> 'application/x-netcdf',
			'sh'		=> 'application/x-sh',
			'shar'		=> 'application/x-shar',
			'swf'		=> 'application/x-shockwave-flash',
			'sit'		=> 'application/x-stuffit',
			'sv4cpio'	=> 'application/x-sv4cpio',
			'sv4crc'	=> 'application/x-sv4crc',
			'tar'		=> 'application/x-tar',
			'tcl'		=> 'application/x-tcl',
			'tex'		=> 'application/x-tex',
			'texinfo'	=> 'application/x-texinfo',
			'texi'		=> 'application/x-texinfo',
			't'			=> 'application/x-troff',
			'tr'		=> 'application/x-troff',
			'roff'		=> 'application/x-troff',
			'man'		=> 'application/x-troff-man',
			'me'		=> 'application/x-troff-me',
			'ms'		=> 'application/x-troff-ms',
			'ustar'		=> 'application/x-ustar',
			'src'		=> 'application/x-wais-source',
			'xhtml'		=> 'application/xhtml+xml',
			'xht'		=> 'application/xhtml+xml',
			'zip'		=> 'application/zip',
			'au'		=> 'audio/basic',
			'snd'		=> 'audio/basic',
			'mid'		=> 'audio/midi',
			'midi'		=> 'audio/midi',
			'kar'		=> 'audio/midi',
			'mpga'		=> 'audio/mpeg',
			'mp2'		=> 'audio/mpeg',
			'mp3'		=> 'audio/mpeg',
			'aif'		=> 'audio/x-aiff',
			'aiff'		=> 'audio/x-aiff',
			'aifc'		=> 'audio/x-aiff',
			'm3u'		=> 'audio/x-mpegurl',
			'ram'		=> 'audio/x-pn-realaudio',
			'rm'		=> 'audio/x-pn-realaudio',
			'rpm'		=> 'audio/x-pn-realaudio-plugin',
			'ra'		=> 'audio/x-realaudio',
			'wav'		=> 'audio/x-wav',
			'pdb'		=> 'chemical/x-pdb',
			'xyz'		=> 'chemical/x-xyz',
			'bmp'		=> 'image/bmp',
			'gif'		=> 'image/gif',
			'ief'		=> 'image/ief',
			'jpeg'		=> 'image/jpeg',
			'jpg'		=> 'image/jpeg',
			'jpe'		=> 'image/jpeg',
			'png'		=> 'image/png',
			'tiff'		=> 'image/tiff',
			'tif'		=> 'image/tiff',
			'djvu'		=> 'image/vnd.djvu',
			'djv'		=> 'image/vnd.djvu',
			'wbmp'		=> 'image/vnd.wap.wbmp',
			'ras'		=> 'image/x-cmu-raster',
			'pnm'		=> 'image/x-portable-anymap',
			'pbm'		=> 'image/x-portable-bitmap',
			'pgm'		=> 'image/x-portable-graymap',
			'ppm'		=> 'image/x-portable-pixmap',
			'rgb'		=> 'image/x-rgb',
			'xbm'		=> 'image/x-xbitmap',
			'xpm'		=> 'image/x-xpixmap',
			'xwd'		=> 'image/x-xwindowdump',
			'igs'		=> 'model/iges',
			'iges'		=> 'model/iges',
			'msh'		=> 'model/mesh',
			'mesh'		=> 'model/mesh',
			'silo'		=> 'model/mesh',
			'wrl'		=> 'model/vrml',
			'vrml'		=> 'model/vrml',
			'css'		=> 'text/css',
			'html'		=> 'text/html',
			'htm'		=> 'text/html',
			'asc'		=> 'text/plain',
			'txt'		=> 'text/plain',
			'rtx'		=> 'text/richtext',
			'rtf'		=> 'text/rtf',
			'sgml'		=> 'text/sgml',
			'sgm'		=> 'text/sgml',
			'tsv'		=> 'text/tab-separated-values',
			'wml'		=> 'text/vnd.wap.wml',
			'wmls'		=> 'text/vnd.wap.wmlscript',
			'etx'		=> 'text/x-setext',
			'xsl'		=> 'text/xml',
			'xml'		=> 'text/xml',
			'mpeg'		=> 'video/mpeg',
			'mpg'		=> 'video/mpeg',
			'mpe'		=> 'video/mpeg',
			'qt'		=> 'video/quicktime',
			'mov'		=> 'video/quicktime',
			'mxu'		=> 'video/vnd.mpegurl',
			'avi'		=> 'video/x-msvideo',
			'movie'		=> 'video/x-sgi-movie',
			'ice'		=> 'x-conference/x-cooltalk',
		);
		if(isset($mimeTypes[$extension])) {
			return $mimeTypes[$extension];
		}
		else {
			return $this->_mailMimeFileTypeDefault;
		}
	}

	/**
	 * Tworzy ciag adresow oddzielonych przecinkami z tablicy
	 * 
	 * @access protected
	 * @param array Tablica adresow
	 * @return string
	 *
	 */
	protected function makeAddressStringFromArray($addressArr) {
		return implode(',', $addressArr);
	}
	
	/**
	 * Tworzy unikatowy ciag poczatkowy dla alternatywnych fragmentow wiadomosci
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeAlternativeBoundaryString() {
		return '--' . $this->_mailAlternativeBoundary . $this->_endString;
	}
	
	/**
	 * Tworzy unikatowy ciag koncowy dla alternatywnych fragmentow wiadomosci
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeEndAlternativeBoundaryString() {
		return '--' . $this->_mailAlternativeBoundary . '--' . $this->_endString;
	}
	
	/**
	 * Tworzy unikatowy ciag poczatkowy dla oddzielenia zawartosci zalacznikow
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeFileBoundaryString() {
		return '--' . $this->_mailFileBoundary . $this->_endString;
	}		
	
	/**
	 * Tworzy unikatowy ciag koncowy dla oddzielenia zawartosci zalacznikow
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeEndFileBoundaryString() {
		return '--' . $this->_mailFileBoundary . '--' . $this->_endString;
	}
	
	/**
	 * Tworzy unikatowy ciag poczatkowy dla oddzielenia zawartosci zaalacznikow
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeMimeInfoString() {
		return $this->_mailMimeInfo . $this->_endString;
	}	
	
	/**
	 * Tworzy unikatowe ciagi oddzielajace fragmenty wiadomosci
	 * 
	 * @access protected
	 * @return void
	 *
	 */
	protected function makeMailBoundaries() {
		$this->_mailAlternativeBoundary = 'ALT_B_' . md5(uniqid(time()));
		$this->_mailFileBoundary = 'ATT_B_' . md5(uniqid(time()+10));
	}		
	
	/**
	 * Sprawdza poprawnosc adresu email
	 * 
	 * @access protected
	 * @param string Adres email
	 * @return bool
	 *
	 */
	protected function isEmailValid($email) {
		if(is_array($email)) {
			foreach($email as $em) {
				if(!preg_match("/^([a-z]+[a-z0-9_\+\-]*)(\.[a-z0-9_\+\-]+)*@([a-z0-9]+\.)+[a-z]{2,6}$/ix", $em)) {
					return false;
				}
			}
			return true;
		}
		else {
			if(preg_match("/^([a-z]+[a-z0-9_\+\-]*)(\.[a-z0-9_\+\-]+)*@([a-z0-9]+\.)+[a-z]{2,6}$/ix", $email)) {
				return true;
			}		
			return false;		
		}
	}
	
	/**
	 * Wyciaga adres email z pomiedzy ostrych nawiasow
	 * 
	 * @access protected
	 * @param string Adres email
	 * @return string
	 *
	 */
	protected function cleanEmail($email) {
		if(is_array($email)) {
			$cleanEmails = array();
			foreach($email as $em) {
				$cleanEmails[] = (preg_match('/\<(.*?)\>/', $em, $match)) ? $match[1] : $em;
			}
			return $cleanEmails;
		}
		else {
			$cleanEmail = (preg_match('/\<(.*?)\>/', $email, $match)) ? $match[1] : $email;
			return $cleanEmail;
		}
	}	

	/**
	 * Czesciowo z Code Igniter
	 * Przelamuje za dlugie wiersze
	 * 
	 * @access protected
	 * @param string Tekst
	 * @param int Limit dlugosci wiersza
	 * @param bool Czy zalamywac adresy internetowe
	 * @return string
	 * @todo Sprawdzic dzialanie metody
	 *
	 */
	protected function wordWrap($txt, $limit=null, $wrapLinks=false) {
		$limit = (is_null($limit)) ? 76 : $limit;
		$txt = preg_replace('/ +/', ' ', $txt);
		$txt = preg_replace('/\r\n/', '\n', $txt);
		$txt = preg_replace('/\r/', '\n', $txt);
		$txt = wordwrap($txt, $limit, $this->_endString, false); //sprawdzic
		$lines = explode('\n', $txt);
		$outputTxt = '';
		foreach($lines as $line) {
			if(strlen($line) <= $limit) {
				$outputTxt .= $line . $this->_endString;
			}
			else {
				if(preg_match('/http:|ftp:|www.|:\/\//', $line) AND $wrapLinks === false) {
					$outputTxt .= $line . $this->_endString;
				}
				else {
					$lineLength = strlen($line);
					$tempLine ='';
					while($lineLength > $limit) {
						$tempLine = substr($line, 0, $limit-1);
						$line = substr($line, $limit-1);
						$outputTxt .= $tempLine . $this->_endString;
						$lineLength = strlen($line);
					}
					$outputTxt .= $line . $this->_endString;					
				}
			}
		}
		return $outputTxt;	 
	}
	
	/**
	 * Tworzy i zwraca alternatywny tekst wiadomosci
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeAlternativeText() {
		if(!empty($this->_mailAlternativeText)) {
			return $this->wordWrap($this->_mailAlternativeText);
		}
		else {
			$body = $this->_mailBody;
			$body = strip_tags($body);
			$body = preg_replace('/\<!--(.*?)--\>/', '', $body);
			$body = preg_replace('/\t/', '', $body);
			$body = preg_replace('/(\n)+/', '\n\n', $body);
			$body = $this->wordWrap($body);
			return $body;
		}
	}
	
	/**
	 * Koduje tekst wiadomosci
	 * 
	 * @access protected
	 * @param string Tekst do zakodowania
	 * @param integer Dlugosc linii tekstu
	 * @return string
	 *
	 */
	protected function encodeTextHtml($txt, $length=null) { //sprawdzic
		if($this->_mailTextHtmlContentTransferEncoding == 'quoted-printable') {
			return $this->quotedPrintable($txt, $length);
		}
		if($this->_mailTextHtmlContentTransferEncoding == 'base64') {
			return chunk_split(base64_encode($txt), $length);
		}
		throw new MailerException('Nieznana metoda kodowania: ' . $this->_mailTextHtmlContentTransferEncoding . '.');
	}
	
	/**
	 * Czesciowo z CodeIgniter
	 * Koduje tekst wiadomosci uzywajac Quoted Printable
	 * 
	 * @access protected
	 * @param string Tekst do zakodowania
	 * @param integer Dlugosc linii tekstu
	 * @return string
	 *
	 */
	protected function quotedPrintable($txt, $lineLength=null) {
		if(function_exists('quoted_printable_encode')) {
			return quoted_printable_encode($txt);
		}
		$lineLength = (int) $lineLength;
		if($lineLength > 75 OR $lineLength <= 0) {
			$lineLength = 75;
		}
		$txt = preg_replace('/ +/', ' ', $txt);
		$txt = preg_replace('/\r\n/', $this->_endString, $txt);
		$txt = preg_replace('/\r/', $this->_endString, $txt);
		
		$escapeChar = '=';
		$tempLine = ''; //tymczasowa linia
		$lines = array(); //tymczasowa tablica do przechowywania przetwarzanych linii
		$outputTxt = ''; //zakodowany wyjsciowy tekst		
		$lines = explode($this->_endString, $txt); //wrzucenie tekstu do tabeli
		foreach($lines as $line) { //petla dla calego tekstu
			$length = strlen($line);
			for($i=0; $i<$length; $i++) { //petla dla kazdej linii
				$char = $line[$i]; //pobranie znaku
				$ascii = ord($char);
				if($i == ($length - 1)) { //konwersja tabow i spacji, ale tylko na koncach linii
					$char = ($ascii == '9' OR $ascii == '32') ? ($escapeChar . sprintf("%02s", dechex($ascii))) : $char;
				}
				if($ascii == '61') { //konwersja znakow =
					$char = $escapeChar . strtoupper(sprintf("%02s", dechex($ascii)));
				}
				if((strlen($tempLine) + strlen($char)) > $lineLength) { //jesli linia jest za dluga lamiemy ja i na koncu stawiamy znak = przed znakiem nowej linii
					$tempLine .= $escapeChar . $this->_endString;
					$outputTxt .= $tempLine;
					$tempLine = '';
				}
				$tempLine .= $char; //jesli jakis inny znak to brak konwersji
			}
			$outputTxt .= $tempLine . $this->_endString;
		}
		$outputTxt = substr($outputTxt, 0, strlen($this->_endString)*-1); //odciecie znakow konca linii
		return $outputTxt;
	}
	
	/**
	 * Czesciowo z CodeIgniter
	 * Koduje tekst tematu i innych tekstow w naglowkach uzywajac Q Encode
	 * 
	 * @access protected
	 * @param string Tekst do zakodowania
	 * @return string
	 *
	 */
	protected function qEncode($txt) {	
		$txt = preg_replace("/\r/", "", $txt);
		$txt = preg_replace("/\n/", "", $txt); //trzeba usunac znaki konca linii
		$lineLength = 75 - strlen($this->_mailCharset) - strlen("=??Q??="); //dlugosc linii nie moze przekraczac 76 znakow
		$txtLength = strlen($txt); //dlugosc tekstu
		$toConvert = array("?", "=", "_"); //znaki ktore beda konwertowane
		$line = ''; //tymczasowa linia
		$outputTxt = ''; //zakodowany wyjsciowy tekst
		for($i=0; $i<$txtLength; $i++) {
			$char = $txt[$i]; //pobrany kolejny znak 
			$ascii = ord($char); //zmiana na kod ascii
			if(($ascii <= 32) OR ($ascii > 126) OR in_array($char, $toConvert)) {
				$char = '='. dechex($ascii); //konwersja niedrukowalnych znakow oraz tych z tablicy toConvert
			}
			if(strlen($line) + strlen($char) >= $lineLength) {
				$line .= $this->_endString; //jesli dlugosc linii przekracza obliczona, rozpoczecie nowej linii
				$outputTxt .= $line;
				$line = '';
			}
			$line .= $char;
		}
		$outputTxt .= $line . $this->_endString;
		//$outputTxt .= $line;
		$outputTxt = trim(preg_replace('/^(.*?)([\r\n])$/m', ' =?' . $this->_mailCharset . '?Q?\\1?=\\2', $outputTxt));
		//$outputTxt = trim(preg_replace('/^(.*?)$/m', ' =?' . $this->_mailCharset . '?Q?\\1?=', $outputTxt));
		return $outputTxt;
	}	
	
	//Funkcje tworzace naglowki-----------------------------------------
	
	/**
	 * Tworzy naglowek z informacja o programie wysylajacym emaile
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeUserAgentHeaderString() {
		return 'User-Agent: ' . $this->_mailMailer . $this->_endString;
	}
	
	/**
	 * Czesciowo z CodeIgniter
	 * Tworzy naglowek z data
	 * 
	 * @access protected
	 * @return string
	 *
	 */ 
	protected function makeDateHeaderString() {
		$timeZone = date("Z");
		$op = ($timeZone[0] == '-') ? '-' : '+';
		$timeZone = abs($timeZone);
		$timeZone = floor($timeZone / 3600) * 100 + ($timeZone % 3600) / 60;
		$dateString = sprintf("%s %s%04d", date("D, j M Y H:i:s"), $op, $timeZone);
		return "Date: " . $dateString . $this->_endString;
	}
	
	/**
	 * Tworzy naglowek z informacja o nadawcy
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeSenderHeaderString() {
		if(!empty($this->_mailFrom)) {	
			return 'X-Sender: ' . $this->_mailFrom . $this->_endString;
		}
		return '';
	}
	
	/**
	 * Tworzy naglowek z informacja o programie wysylajacym emaile
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeMailerHeaderString() {
		return 'X-Mailer: ' . $this->_mailMailer . $this->_endString;
	}
	
	/**
	 * Tworzy naglowek z informacja o priorytecie w skali 1-5
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makePriorityHeaderString() {
		return 'X-Priority: ' . $this->_mailPriority . $this->_endString;		
	}
	
	/**
	 * Tworzy naglowek z informacja o priorytecie słownie
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeMsMailPriorityHeaderString() {
		return 'X-MSMail-Priority: ' . $this->_mailMsMailPriority . $this->_endString;
	}	
	
	/**
	 * Tworzy naglowek z unikatowym identyfikatorem wiadomosci
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeMessageIdHeaderString() {
		if(!empty($this->_mailFrom)) {
			$domain = strstr($this->_mailFrom, '@');
		}
		else {
			$domain = uniqid(time());
		}
		return 'Message-ID: <'. md5(uniqid(time())) . $domain . '>' . $this->_endString;
	}
	
	/**
	 * Tworzy naglowek z informacja o programie wysylajacym emaile
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeMimeVersionHeaderString() {
		return $this->_mailMimeVersion . $this->_endString;
	}
	
	/**
	 * Tworzy naglowek z lista adresow adresatow
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeToHeaderString() {
		if(($this->_mailProtocol == 'mail') AND (count($this->_mailTo) != 0)) {
			$addressList = implode(',', $this->_mailTo); 
			return 'To: ' . $addressList . $this->_endString;
		}
		return '';
	}
	
	/**
	 * Tworzy naglowek z lista adresow do ktorych zostanie wyslana kopia listu
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeCcHeaderString() {
		if(count($this->_mailCc) != 0) {			
			$addressList = implode(',', $this->_mailCc);			 
			return 'Cc: ' . $addressList . $this->_endString;
		}
		return '';
	}
	
	/**
	 * Tworzy naglowek z lista adresow do ktorych zostanie wyslana ukryta kopia listu
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeBccHeaderString() {
		if(($this->_mailProtocol != 'smtp') AND (count($this->_mailBcc) != 0)) {
			$addressList = implode(',', $this->_mailBcc); 
			return 'Bcc: ' . $addressList . $this->_endString;
		}
		return '';
	}
	
	/**
	 * Tworzy naglowek z informacja adresie na ktory maja byc wysylane odpowiedzi
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeReplyToHeaderString() {
		if((!empty($this->_mailReplyTo)) AND (!empty($this->_mailReplyToName))) {
			return 'Reply-To: ' . $this->_mailReplyToName . ' <' . $this->_mailReplyTo . '>' . $this->_endString;
		}
		return '';
	}
	
	/**
	 * Tworzy naglowek z informacja o nadawcy
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeFromHeaderString() {
		if((!empty($this->_mailFrom)) AND (!empty($this->_mailFromName))) {
			$fromStr = 'From: ' . $this->_mailFromName . ' <' . $this->_mailFrom . '>' . $this->_endString;
			$fromStr .= 'Return-Path: <' . $this->_mailFrom . '>' . $this->_endString;
			return $fromStr;
		}
		return '';
	}	
	
	/**
	 * Tworzy naglowek z tematem wiadomosci
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeSubjectHeaderString() {
		if(($this->_mailProtocol != 'mail') AND (!empty($this->_mailSubject))) { 
			return 'Subject: ' . $this->_mailSubject . $this->_endString;
		}
		return '';
	}	
	
	/**
	 * Tworzy naglowek z informacja o sposobie umieszczenia w wiadomosci zawartosci zalacznika
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeContentTypeMultipartWithBoundaryHeaderString() {
		return 'Content-type: multipart/' . $this->_mailMimeTypeMultipart . '; boundary="' . $this->_mailFileBoundary . '" ' . $this->_endString;
	}
	
	/**
	 * Tworzy naglowek z informacja o wiadomosci wieloczesciowej
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeContentTypeMultipartAlternativeWithBoundaryHeaderString() {
		return 'Content-type: multipart/alternative; boundary="' . $this->_mailAlternativeBoundary . '" ' . $this->_endString;
	}

	/**
	 * Tworzy naglowek z informacja o czysto tekstowym typie bloku wiadomosci
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeTextPlainContentTypeWithCharsetHeaderString() {
		return 'Content-type: text/plain; charset="' . $this->_mailCharset . '" ' . $this->_endString;
	}
	
	/**
	 * Tworzy naglowek z informacja o htmlowym typie bloku wiadomosci
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeTextHtmlContentTypeWithCharsetHeaderString() {
		return 'Content-type: text/html; charset="' . $this->_mailCharset . '" ' . $this->_endString;
	}

	/**
	 * Tworzy naglowek z informacja o sposobie kodowania wiadomosci czysto tekstowej
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeTextPlainContentTransferEncodingHeaderString() {
		return 'Content-transfer-encoding: ' . $this->_mailTextPlainContentTransferEncoding . $this->_endString;
	}
	
	/**
	 * Tworzy naglowek z informacja o sposobie kodowania wiadomosci htmlowej
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeTextHtmlContentTransferEncodingHeaderString() {
		return 'Content-transfer-encoding: ' . $this->_mailTextHtmlContentTransferEncoding . $this->_endString;
	}	

	/**
	 * Tworzy naglowek z informacja sposobie kodowania zalacznikow
	 * 
	 * @access protected
	 * @return string
	 *
	 */
	protected function makeFileContentTransferEncodingHeaderString() {
		return 'Content-transfer-encoding: ' . $this->_mailFileContentTransferEncoding . $this->_endString;
	}	
	
	/**
	 * Tworzy naglowek z informacja o typie i nazwie zalacznika
	 * 
	 * @access protected
	 * @param string Typ MIME pliku
	 * @param string Nazwa pliku
	 * @return string
	 *
	 */
	protected function makeFileContentTypeHeaderString($fileContentType, $fileName) {
		return 'Content-type: ' . $fileContentType . '; name="' . $fileName . '" ' . $this->_endString;
	}

	/**
	 * Tworzy naglowek z informacja czy plik ma byc wyalany jako zalacznik czy wewnatrz wiadomosci
	 * 
	 * @access protected
	 * @param string Nazwa pliku
	 * @param string Sposob dolaczania pliku
	 * @return string
	 *
	 */
	protected function makeFileContentDispositionHeaderString($fileName, $disposition='attachment') {
		return 'Content-Disposition: ' . $disposition . '; filename="' . $fileName . '" ' . $this->_endString;
	}	
	
	/*
	protected function makeReadReceiptHeaderString() {
		if(is_array($this->_mailFrom)) {
			$mailFrom = implode(',', $this->mailFrom);
		}
		else {
			$mailFrom = $this->_mailFrom;
		}
		$header = '';
		$header .= 'Disposition-Notification-To: ' . $mailFrom . $this->_endString;
		$header .= 'X-Confirm-Reading-To: ' . $mailFrom . $this->_endString;
		$header .= 'X-PMRQC: 1' . $this->_endString;
		return $header;
	}
	*/ 
	
	//Koniec naglowkow -------------------------------------------------
	
	/**
	 * Tworzy gotowe do wyslania ciagi naglowkow i ciala wiadomosci
	 * 
	 * @access protected
	 * @return void
	 *
	 */
	protected function buildMail() {
		if($this->_wordWrap === true AND $this->_mailTextType == 'text') {
			$this->_mailBody = $this->wordWrap($this->_mailBody);
		}
		$mailHeadersString = '';
		$mailHeadersString .= $this->makeUserAgentHeaderString();
		$mailHeadersString .= $this->makeDateHeaderString();
		$mailHeadersString .= $this->makeSenderHeaderString();
		$mailHeadersString .= $this->makeMailerHeaderString();
		$mailHeadersString .= $this->makePriorityHeaderString();		
		$mailHeadersString .= $this->makeMsMailPriorityHeaderString();
		$mailHeadersString .= $this->makeMessageIdHeaderString();	
		$mailHeadersString .= $this->makeMimeVersionHeaderString();		
		$mailHeadersString .= $this->makeToHeaderString();		
		$mailHeadersString .= $this->makeCcHeaderString();		
		$mailHeadersString .= $this->makeBccHeaderString();		
		$mailHeadersString .= $this->makeReplyToHeaderString();		
		$mailHeadersString .= $this->makeFromHeaderString();		
		$mailHeadersString .= $this->makeSubjectHeaderString();		
		
		if(($this->_mailTextType == 'text') AND (count($this->_mailFiles) == 0)) {
			$mailHeadersString .= $this->makeTextPlainContentTypeWithCharsetHeaderString();
			$mailHeadersString .= $this->makeTextPlainContentTransferEncodingHeaderString();
			if($this->_mailProtocol == 'mail') {
				$this->_mailPreparedHeaders = rtrim($mailHeadersString);
				$this->_mailPreparedBody = $this->_mailBody;
			}
			else {
				$mailHeadersString .= $this->_endString;
				$this->_mailPreparedBody = $mailHeadersString . $this->_mailBody;
			}
			return;
		}
		
		if(($this->_mailTextType == 'html') AND (count($this->_mailFiles) == 0)) {
			if($this->_mailMultipart == false) {
				$mailHeadersString .= $this->makeTextHtmlContentTypeWithCharsetHeaderString();
				$mailHeadersString .= $this->makeTextHtmlContentTransferEncodingHeaderString();
			}
			else {
				$mailHeadersString .= $this->makeContentTypeMultipartAlternativeWithBoundaryHeaderString();
				$mailHeadersString .= $this->_endString;
				$mailHeadersString .= $this->makeMimeInfoString();
				$mailHeadersString .= $this->_endString;
				$mailHeadersString .= $this->makeAlternativeBoundaryString();
				$mailHeadersString .= $this->makeTextPlainContentTypeWithCharsetHeaderString();
				$mailHeadersString .= $this->_endString;
				$mailHeadersString .= $this->makeTextPlainContentTransferEncodingHeaderString();
				$mailHeadersString .= $this->_endString;				
				$mailHeadersString .= $this->makeAlternativeText();
				$mailHeadersString .= $this->_endString;
				$mailHeadersString .= $this->_endString;
				$mailHeadersString .= $this->makeAlternativeBoundaryString();								
				$mailHeadersString .= $this->makeTextHtmlContentTypeWithCharsetHeaderString();
				$mailHeadersString .= $this->_endString;
				$mailHeadersString .= $this->makeTextHtmlContentTransferEncodingHeaderString();	
			}
			$body = $this->encodeTextHtml($this->_mailBody);
			if($this->_mailProtocol == 'mail') {
				$this->_mailPreparedHeaders = rtrim($mailHeadersString);
				$this->_mailPreparedBody = $body . $this->_endString . $this->_endString;
				if($this->_mailMultipart != false) {
					$this->_mailPreparedBody .= $this->makeEndAlternativeBoundaryString();
				}
				return;
			}
			$mailHeadersString .= $this->_endString;
			$mailHeadersString .= $body;
			$mailHeadersString .= $this->_endString . $this->_endString;
			if($this->_mailMultipart != false) {
				$mailHeadersString .= $this->makeEndAlternativeBoundaryString();
			}
			$this->_mailPreparedBody = $mailHeadersString;
			return;
		}
		
		if(($this->_mailTextType == 'text') AND (count($this->_mailFiles) > 0)) {
			$mailHeadersString .= $this->makeContentTypeMultipartWithBoundaryHeaderString();
			$mailHeadersString .= $this->_endString;
			$mailHeadersString .= $this->makeMimeInfoString();
			$mailHeadersString .= $this->_endString;
			$mailHeadersString .= $this->makeFileBoundaryString();
			$mailHeadersString .= $this->makeTextPlainContentTypeWithCharsetHeaderString();
			$mailHeadersString .= $this->makeTextPlainContentTransferEncodingHeaderString();
			if($this->_mailProtocol == 'mail') {
				$this->_mailPreparedHeaders = rtrim($mailHeadersString);
				$body = $this->_mailBody . $this->_endString . $this->_endString;
			}
			$mailHeadersString .= $this->_endString;
			$mailHeadersString .= $this->_mailBody . $this->_endString . $this->_endString; 
		}
		
		if(($this->_mailTextType == 'html') AND (count($this->_mailFiles) > 0)) {
			$mailHeadersString .= $this->makeContentTypeMultipartWithBoundaryHeaderString();
			$mailHeadersString .= $this->_endString;
			$mailHeadersString .= $this->makeMimeInfoString();
			$mailHeadersString .= $this->_endString;
			$mailHeadersString .= $this->makeFileBoundaryString();
			$mailHeadersString .= $this->makeContentTypeMultipartAlternativeWithBoundaryHeaderString();
			$mailHeadersString .= $this->_endString;
			$mailHeadersString .= $this->makeAlternativeBoundaryString();
			$mailHeadersString .= $this->makeTextPlainContentTypeWithCharsetHeaderString();
			$mailHeadersString .= $this->makeTextPlainContentTransferEncodingHeaderString();
			$mailHeadersString .= $this->_endString;
			$mailHeadersString .= $this->makeAlternativeText();
			$mailHeadersString .= $this->_endString;
			$mailHeadersString .= $this->_endString;
			$mailHeadersString .= $this->makeAlternativeBoundaryString();				
			$mailHeadersString .= $this->makeTextHtmlContentTypeWithCharsetHeaderString();
			$mailHeadersString .= $this->makeTextHtmlContentTransferEncodingHeaderString();	
			$body = $this->encodeTextHtml($this->_mailBody);
			if($this->_mailProtocol == 'mail') {
				$this->_mailPreparedHeaders .= rtrim($mailHeadersString);
				$body .= $this->_endString . $this->_endString;
				$body .= $this->makeEndAlternativeBoundaryString() . $this->_endString; 
			}
			$mailHeadersString .= $this->_endString . $this->_endString;
			$mailHeadersString .= $body . $this->_endString;
			$mailHeadersString .= $this->makeEndAlternativeBoundaryString() . $this->_endString;
		}
		/////
		$attachments = array();
		$attachmentsCount = count($this->_mailFiles);
		for($i=0; $i<$attachmentsCount; $i++) {
			$fileName = $this->_mailFiles[$i]['fileName'];
			$fileType = $this->_mailFiles[$i]['fileType'];
			$fileBaseName = basename($fileName);
			if(!file_exists($fileName)) {
				throw new MailerException('Zadany plik: ' . $fileName . ' nie istnieje');
			}
			if(!is_readable($fileName)) {
				throw new MailerException('Zadany plik: ' . $fileName . ' nie moze byc odczytany');				
			}
			$fileHeaderString = '';
			$fileHeaderString = $this->makeFileBoundaryString();
			$fileHeaderString .= $this->makeFileContentTypeHeaderString($fileType, $fileBaseName);
			$fileHeaderString .= $this->makeFileContentDispositionHeaderString($fileBaseName);
			$fileHeaderString .= $this->makeFileContentTransferEncodingHeaderString();
			$attachments[] = $fileHeaderString;
			if(!($filePointer = fopen($fileName, 'r'))) {
				throw new MailerException('Nie mozna otworzyc pliku: ' . $fileName . ' do odczytu');
			}
			$fileContent = fread($filePointer, filesize($fileName)+1);
			if($fileContent === false) {
				throw new MailerException('Blad podczas odczytywania pliku: ' . $fileName);
			}
			fclose($filePointer);
			$attachments[] = chunk_split(base64_encode($fileContent));
		}
		$attachmentsString = implode($this->_endString, $attachments);
		if($this->_mailProtocol == 'mail') {
			$this->_mailPreparedBody = $body . $attachmentsString . $this->_endString;
			$this->_mailPreparedBody .= $this->makeEndFileBoundaryString();
			return;
		}
		$this->_mailPreparedBody = $mailHeadersString . $attachmentsString . $this->_endString;
		$this->_mailPreparedBody .= $this->makeEndFileBoundaryString();
		return;	
	}
	
	//------------------------------------------------------------------
	
	/**
	 * Dodaje adres (adresy) odbiorcow
	 * 
	 * @access public
	 * @param mixed //string or array Adres lub adresy email
	 * @return void
	 *
	 */
	public function addTo($mailTo) {
		$mailTo = $this->cleanEmail($mailTo);
		if(!$this->isEmailValid($mailTo)) {
			throw new MailerException('Nieprawidlowy adres email: ' . $mailTo);
		}
		if(is_array($mailTo)) {
			for($i=0; $i< count($mailTo); $i++) {
				$mailTo[$i] = trim($mailTo[$i]);
			}
			$tmpArr = $this->_mailTo;
			$this->_mailTo = array_merge($tmpArr, $mailTo);	
		}
		else {
			$this->_mailTo[] = trim($mailTo);
		}
	}
	
	/**
	 * Dodaje adres (adresy) odbiorcow kopii listow
	 * 
	 * @access public
	 * @param mixed //string or array Adres lub adresy email
	 * @return void
	 *
	 */
	public function addCc($mailCc) {
		$mailCc = $this->cleanEmail($mailCc);
		if(!$this->isEmailValid($mailCc)) {
			throw new MailerException('Nieprawidlowy adres email: ' . $mailCc);
		}	
		if(is_array($mailCc)) {
			for($i=0; $i< count($mailCc); $i++) {
				$mailCc[$i] = trim($mailCc[$i]);
			}		
			$tmpArr = $this->_mailCc;
			$this->_mailCc = array_merge($tmpArr, $mailCc);
		}
		else {
			$this->_mailCc[] = $mailCc;
		}
	}
	
	/**
	 * Dodaje adres (adresy) odbiorcow ukrytych kopii listow
	 * 
	 * @access public
	 * @param mixed //string or array Adres lub adresy email
	 * @return void
	 *
	 */
	public function addBcc($mailBcc) {
		$mailBcc = $this->cleanEmail($mailBcc);
		if(!$this->isEmailValid($mailBcc)) {
			throw new MailerException('Nieprawidlowy adres email: ' . $mailBcc);
		}	
		if(is_array($mailBcc)) {
			for($i=0; $i< count($mailBcc); $i++) {
				$mailBcc[$i] = trim($mailBcc[$i]);
			}		
			$tmpArr = $this->_mailBcc;			
			$this->_mailBcc = array_merge($tmpArr, $mailBcc);
		}
		else {
			$this->_mailBcc[] = $mailBcc;
		}		
	}
	
	/**
	 * Dodaje adres na ktory ma przychodzic odpowiedz
	 * 
	 * @access public
	 * @param string Adres email
	 * @param string Nazwa odbiorcy
	 * @return void
	 *
	 */
	public function addReplyTo($mailReplyTo, $name=null) {
		if(preg_match('/\<(.*?)\>/', $mailReplyTo, $match)) {
			$mailReplyTo = $match[1];
		}
		if(!$this->isEmailValid($mailReplyTo)) {
			throw new MailerException('Nieprawidlowy adres email: ' . $mailReplyTo);
		}
		$this->_mailReplyTo = $mailReplyTo;
		if(!is_null($name)) {
			$name = trim($name, '"');
			$name = '"' . $name . '"';
		}
		else {
			$name = $mailReplyTo;
		}
		$this->_mailReplyToName = $name;
	}
	
	/**
	 * Dodaje temat wiadomosci
	 * 
	 * @access public
	 * @param string Temat wiadomosci
	 * @return void
	 *
	 */
	public function addSubject($mailSubject='') { 						//qEncode
		$this->_mailSubject = ($mailSubject == '') ? $mailSubject : $this->qEncode($mailSubject);
	}	

	/**
	 * Dodaje adres nadawcy
	 * 
	 * @access public
	 * @param string Adres email nadawcy
	 * @param string Nazwa nadawcy
	 * @return void
	 *
	 */
	public function addFrom($mailFrom, $name=null) {
		if(preg_match('/\<(.*?)\>/', $mailFrom, $match)) {
			$mailFrom = $match[1];
		}
		if(!$this->isEmailValid($mailFrom)) {
			throw new MailerException('Nieprawidlowy adres email: ' . $mailFrom);
		}
		$this->_mailFrom = $mailFrom;
		if(!is_null($name)) {
			$name = $this->qEncode($name);
			$this->_mailFromName = $name;	
		}
		if(empty($this->_mailReplyTo)) {
			$this->addReplyTo($mailFrom, $name);
		}
	}	
	
	/**
	 * Dodaje tresc wiadomosci
	 * 
	 * @access public
	 * @param string Tresc wiadomosci
	 * @return void
	 *
	 */
	public function addBody($mailBody) {
		$mailBody = preg_replace("/\r\n/", $this->_endString, $mailBody);
		$mailBody = preg_replace("/\r/", $this->_endString, $mailBody);		
		$this->_mailBody = trim($mailBody);
	}
	
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
	public function addFile($fileName, $disposition='attachment', $fileType=null) {
		$fileName = (string) $fileName;
		if(is_null($fileType)) {
			$parts = explode('.', $fileName);
			$fileExtension = end($parts);
			$fileType = $this->mimeType($fileExtension);
		}
		else {
			$fileType = (string) $fileType;		
		}
		$fileDisposition = ($disposition == 'inline') ? 'inline' : 'attachment';

		$mailFile = array('fileName' => $fileName, 'fileType' => $fileType, 'fileDisposition' => $fileDisposition);
		$this->_mailFiles[] = $mailFile;
	}
	
	/**
	 * Ustawia typ wiadomosci tekstowej
	 * 
	 * @access public
	 * @param string Typ wiadomosci
	 * @return void
	 *
	 */
	public function setMailTextType($mailType='text') {
		$this->_mailTextType = ($mailType == 'text') ? 'text' : 'html';
	}
	
	/**
	 * Ustawia kodowanie wiadomosci
	 * 
	 * @access public
	 * @param string Kodowanie
	 * @return void
	 *
	 */
	public function setMailCharset($mailCharset='utf-8') {
		if(!empty($mailCharset)) {
			$this->_mailCharset = (string)$mailCharset;
		}
	}
	
	/**
	 * Ustawia priorytet wiadomosci
	 * 
	 * @access public
	 * @param integer Priorytet
	 * @return void
	 *
	 */
	public function setPriority($priority) {
		$priority = (int)$priority;
		if($priority > 5 OR $priority < 1) {
			$this->_mailPriority = 3;
		}
		else {
			$this->_mailPriority = $priority;
		}
	}
	
	/**
	 * Ustawia priorytet wiadomosci tekstowo
	 * 
	 * @access public
	 * @param string Priorytet wiadomosci
	 * @return void
	 *
	 */
	public function setMsMailPriority($msMailPriority='Normal') {
		$prio = array('Highest', 'High', 'Normal' , 'Low', 'Lowest');
		if((!in_array($msMailPriority, $prio)) || (empty($msMailPriority))) {
			$this->_mailMsMailPriority = 'Normal'; 
		}
		else {
			$this->_mailMsMailPriority = (string)$msMailPriority;
		}
	}
		
	/**
	 * Ustawia sposob kodowania czystotekstowej wiadomosci 
	 * 
	 * @access public
	 * @param string Kodowanie
	 * @return void
	 *
	 */
	public function setTextPlainEncoding($encoding='quoted-printable') {
		if(!empty($encoding)) {
			$this->_mailTextPlainContentTransferEncoding = $encoding;
		}
	}
	
	/**
	 * Ustawia sposob kodowania wiadomosci html 
	 * 
	 * @access public
	 * @param string Kodowanie
	 * @return void
	 *
	 */	
	public function setTextHtmlEncoding($encoding='quoted-printable') {
		if(!empty($encoding)) {
			$this->_mailTextHtmlContentTransferEncoding = $encoding;
		}
	}
	
	/**
	 * Ustawia sposob kodowania zalacznikow 
	 * 
	 * @access public
	 * @param string Kodowanie
	 * @return void
	 *
	 */	
	public function setFileEncoding($encoding='base64') {
		if(!empty($encoding)) {
			$this->_mailFileContentTransferEncoding = $encoding;
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
	 * Ustawia tekst informacji dla klientow nie obslugujacych wiadomosci MIME
	 * 
	 * @access public
	 * @param string Informacja dla klientow o wykorzystaniu MIME
	 * @return void
	 *
	 */
	public function setMimeInfo($info='') {
		if(!empty($info)) {
			$this->_mailMimeInfo = (string)$info;
		}
	}
	
	/**
	 * Ustawia czy wiadomosc ma miec ograniczona dlugosc linii
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie lamania linii wiadomosci
	 * @return void
	 *
	 */
	public function setWordWrap($wrap=false) {
		$this->_wordWrap = (bool)$wrap;
	}

	/**
	 * Ustawia czy wiadomosc ma byc wyslana jako wieloczesciowa
	 * 
	 * @access public
	 * @param bool Wiadomosc wieloczesciowa lub nie
	 * @return void
	 *
	 */
	public function setSendMultipart($multipart=false) {
		$this->_mailMultipart = (bool)$multipart;
	}
	
	/**
	 * Ustawia alternatywny tekst dla wiadomosci
	 * 
	 * @access public
	 * @param string Tekst alternatywny wiadomosci
	 * @return void
	 *
	 */
	public function setAlternativeText($txt='') {
		if(!empty($txt)) {
			$this->_mailAlternativeText = (string)$txt;
		}
	}
	
	/**
	 * Ustawia sposob dolaczeina pliku do wiadomosci
	 * 
	 * @access public
	 * @param string Sposob dolaczenia pliku mixed lub related
	 * @return void
	 *
	 */
	public function setMimeMultipart($multipart='mixed') {
		$this->_mailMimeTypeMultipart = ($multipart == 'mixed') ? 'mixed' : 'related';
	}
	
	/**
	 * Pobiera ustawiony typ tekstowej wiadomosci
	 * 
	 * @access public
	 * @return string Typ wiadomosci
	 *
	 */
	public function getMailTextMimeType() {
		if($this->_mailTextType == 'html') {
			return 'text/html';
		}
		else {
			return $this->_mailMimeTextTypeDefault;
		}
	}	
	
	/**
	 * Wysyla wiadomosc
	 * 
	 * @access public
	 * @return void
	 *
	 */
	public function send() {
		if((count($this->_mailTo) == 0) AND (count($this->_mailCc) == 0) AND (count($this->_mailBcc) == 0)) {
			throw new MailerException('Brak zdefiniowanych odbiorcow');
		}
		if($this->_mailFrom == '') {
			throw new MailerException('Brak zdefiniowanego nadawcy');			
		}
		$this->buildMail(); //Moze rzucic wyjatkiem
		$this->_mailTransporter->send($this->makeAddressStringFromArray($this->_mailTo), $this->makeAddressStringFromArray($this->_mailCc), $this->makeAddressStringFromArray($this->_mailBcc), $this->_mailSubject, $this->_mailPreparedBody, $this->_mailPreparedHeaders, $this->_mailFrom);	
	}	

	/**
	 * Wyswietla zawartosc pewnych pol obiektu
	 * 
	 * @access public
	 * @return void
	 *
	 */	
	public function __toString() {
		echo '<pre>' . "\n <br />";
		echo '---------------------------------------------------------- ' . "\n <br />";		
		echo 'To: ' . $this->makeAddressStringFromArray($this->_mailTo) . "\n <br />";
		echo 'Cc: ' . $this->makeAddressStringFromArray($this->_mailCc) . "\n <br />";
		echo 'Bcc: ' . $this->makeAddressStringFromArray($this->_mailBcc) . "\n <br />";
		echo 'Reply-To: ' . $this->_mailReplyTo . "\n <br />";
		echo 'Reply-To Name: ' . $this->_mailReplyToName . "\n <br />";
		echo 'From: ' . $this->_mailFrom . "\n <br />";
		echo 'From Name: ' . $this->_mailFromName . "\n <br />";
		echo 'Subject: ' . $this->_mailSubject . "\n <br />";
		echo 'Body: ' . $this->_mailBody . "\n <br />";
		echo 'Body encoded QT: ' . $this->quotedPrintable($this->_mailBody) . "\n <br />";
		echo 'Body wraped: ' . $this->wordWrap($this->_mailBody,75) . "\n <br />";		
		for($i=0,$c=count($this->_mailFiles); $i<$c; $i++) {
			echo 'File ' . $i . ' Name: ' . $this->_mailFiles[$i]['fileName'] . ' Type: ' . $this->_mailFiles[$i]['fileType'] .  ' Disposition: ' . $this->_mailFiles[$i]['fileDisposition'] . "\n <br />";
		}
		echo '---------------------------------------------------------- ' . "\n <br />";
		echo 'Text Type: ' . $this->_mailTextType . "\n <br />";
		echo 'Charset: ' . $this->_mailCharset . "\n <br />";
		echo 'Priority: ' . $this->_mailPriority . "\n <br />";
		echo 'MSPriority: ' . $this->_mailMsMailPriority . "\n <br />";
		echo 'Text Plain Encoding: ' . $this->_mailTextPlainContentTransferEncoding . "\n <br />";
		echo 'Text HTML Encoding: ' . $this->_mailTextHtmlContentTransferEncoding . "\n <br />";
		echo 'File Encoding: ' . $this->_mailFileContentTransferEncoding . "\n <br />";		
		echo 'Alternative Text: ' . $this->_mailAlternativeText . "\n <br />";
		echo 'Protocol: ' . $this->_mailProtocol . "\n <br />";
		$endl = ($this->_endString == "\r\n") ? '\r\n' : '\n';
		echo 'Endl: ' . $endl . "\n <br />";
		$wp = ($this->_wordWrap === true) ? 1 : 0;
		echo 'Word Wrap: ' . $wp . "\n <br />";
		echo 'Mailer: ' . $this->_mailMailer . "\n <br />";
		echo 'Mime Version: ' . $this->_mailMimeVersion . "\n <br />";
		echo 'Mime Info: ' . $this->_mailMimeInfo . "\n <br />";
		echo 'Mime Multipart: ' . $this->_mailMimeTypeMultipart . "\n <br />";
		$ml = ($this->_mailMultipart === true) ? 1 : 0;		
		echo 'Send Multipart: ' . $ml . "\n <br />";
		echo '---------------------------------------------------------- ' . "\n <br />";
		echo 'Header String: ' . $this->_mailPreparedHeaders . "\n <br />";
		echo '---------------------------------------------------------- ' . "\n <br />";		
		echo 'Body String: ' . $this->_mailPreparedBody . "\n <br />";
		echo '---------------------------------------------------------- ' . "\n <br />";		
		echo '</pre>' . "\n <br />";
		return '';
	}
	
	/**
	 * Resetuje niektore pola obiektu, aby mozna bylo wyslac nowa wiadomosc
	 * 
	 * @access public
	 * @return void
	 *
	 */	
	public function reset() {
		$this->_mailTo = array();
		$this->_mailCc = array();
		$this->_mailBcc = array();
		$this->_mailSubject = '';
		$this->_mailBody = '';
		$this->_mailReplyTo = '';
		$this->_mailReplyToName = '';
		$this->_mailFrom = '';
		$this->_mailFromName = '';
		$this->_mailFiles = array();
		$this->_mailPreparedHeaders = '';
		$this->_mailPreparedBody = '';
	}
		
}

?>
