<?php

/**
 * @class Upload
 *
 * @author Przemyslaw Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Upload implements IUpload {

	/**
	 * Obiekt obslugi plikow
	 *
	 * @var object
	 */	
	protected $_fileManager = null;

	/**
	 * Obiekt filtra Xss
	 *
	 * @var object
	 */	
	protected $_xssFilter = null;
	
	
	/**
	 * Dozwolone rozszerzenia plikow
	 *
	 * @var array
	 */	
	protected $_allowedFileExt = array(); //tutaj tabela z typami np avi mov txt itp

	/**
	 * Wlaczenie/wylaczenie pozwolenia uzywania wszystkich typow MIME //true jesli tak
	 *
	 * @var bool
	 */	
	protected $_allMimeAllowed = false; //wszystkie mime dozwolone

	/**
	 * Maksymalna wielkosc przesylanego pliku
	 *
	 * @var integer
	 */	
	protected $_maxFileSize = 0; //max wielkosc dla wysylanego pliku
	
	/**
	 * Maksymalna dlugosc nazwy pliku
	 *
	 * @var integer
	 */		
	protected $_maxFileNameLength = 0; //max dlugosc nazwy pliku

	/**
	 * Wlaczenie/wylaczenie sprawdzania plikow //true jesli tak
	 *
	 * @var bool
	 */		
	protected $_checkImage = false; //sprawdzanie czy plik jest obrazkiem
	
	/**
	 * Maksymalna szerokosc obrazka
	 *
	 * @var integer
	 */		
	protected $_maxImageWidth = 0; //wielkosc maksymalna dla zdjec
	
	/**
	 * Maksymanla wysokosc obrazka
	 *
	 * @var integer
	 */		
	protected $_maxImageHeight = 0;
	
	/**
	 * Sciezka do katalogu upload
	 *
	 * @var string
	 */		
	protected $_uploadPath = ''; //sciezka do uploadu
	
	/**
	 * Wlaczenie/wylaczenie nadpisywania plikow
	 *
	 * @var bool
	 */		
	protected $_overwrite = false; //czy nadpisac istniejacy plik przy uploadowaniu	
	
	/**
	 * Czy usuwac spacje z nazw plikow
	 *
	 * @var bool
	 */		
	protected $_stripSpaces = false; //usunac spacje

	/**
	 * Wlaczenie/wylaczenie sprawdzania i czyszczenia plikow z groznych danych
	 *
	 * @var bool
	 */		
	protected $_cleanXss = false; //czyszczenie przed xss
	
	/**
	 * Wlaczenie/wylaczenie generowania losowych nazw dla plikow
	 *
	 * @var bool
	 */		
	protected $_randomName = false; //losowa nazwa pliku
	
	/**
	 * Dozwolone Typy MIME dla obrazkow
	 *
	 * @var array
	 */		
	protected $_imageMimeType = array('image/gif', 'image/png', 'image/jpg', 'image/jpe', 'image/jpeg', 'image/pjpeg');


	/**
	 * Tablica bledow p oprzeslaniu plikow
	 *
	 * @var array
	 */		
	protected $_errors = array();
	
	/**
	 * Tablica bledow dla ostatnio przetworzonego pliku
	 *
	 * @var array
	 */		
	protected $_elementError = array();
	
	/**
	 * Tablica z tymczasowymi nazwami przetwarzanych plikow
	 *
	 * @var array
	 */		
	protected $_fileTempName = array();
	
	/**
	 * Tablica z nazwami plikow
	 *
	 * @var array
	 */		
	protected $_fileName = array();
	
	/**
	 * Tablica z rozmiarami plikow
	 *
	 * @var array
	 */		
	protected $_fileSize = array();
	
	/**
	 * Tablica z rozszerzeniami plikow
	 *
	 * @var array
	 */		
	protected $_fileExt = array();
	
	/**
	 * Tablica z typami plikow
	 *
	 * @var array
	 */		
	protected $_fileType = array();
	
	/**
	 * Tablica z rozdzielczoscia pozioma obrazkow
	 *
	 * @var array
	 */		
	protected $_imageWidth = array();
	
	/**
	 * Tablica z rozdzielczoscia pionowa obrazkow
	 *
	 * @var array
	 */		
	protected $_imageHeight = array();
	
	/**
	 * Numer aktualnie przetwarzanego pliku, od zera
	 *
	 * @var array
	 */		
	protected $_actualKey = 0;

	// Metody publiczne
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt obslugi plikow FileManager
	 * @param object Obiekt filtra FilterXss
	 * @param string Sciezka do katalogu upload
	 * @param array|string Dozwolone rozszerzenia plikow
	 * @param integer Maksymalna wielkosc plikow
	 * @param integer Maksymalna dlugosc nazwy plikow
	 * @param integer Maksymalna szerokosc obrazkow
	 * @param integer Maksymalna wysokosc obrazkow
	 * @param bool Wlaczenie/wylaczenie losowych nazw dla plikow
	 * @param bool Wlaczenie/wylaczenie nadpisywania plikow
	 * @param bool Wlaczenie/wylaczenie dopuszczenia wszystkich typow MIME
	 * @param bool Wlaczenie/wylaczenie usuwania spacji z nazw plikow
	 * @param bool Wlaczenie/wylaczenie sprawdzania i czyszczenia plikow z niebezpiecznej zawartosci
	 * 
	 */
	public function __construct(FileManager $fileManager, IFilter $xssFilter, $uploadPath='', $allowedFileExt=null, $maxFileSize=0, $maxFileNameLength=0, $maxImageWidth=0, $maxImageHeight=0, $randomName=false, $overwrite=false, $allMimes=false, $stripSpaces=false, $cleanXss=false) {
		//najpierw automatyczne ustawienie z pliku konfiguracyjnego to dopisac!!!!!!!!!!!!! szczegolnie tabela mime
		$this->_fileManager = $fileManager;
		$this->_xssFilter = $xssFilter;
		if($allowedFileExt !== null && is_array($allowedFileExt)) {
			$this->setAllowedFileExt($allowedFileExt);
		}
		$this->setMaxFileSize($maxFileSize);
		$this->setMaxFileNameLength($maxFileNameLength);
		$this->setMaxImageWidth($maxImageWidth);
		$this->setMaxImageHeight($maxImageHeight);
		$this->setRandomName($randomName);
		$this->setOverwrite($overwrite);
		$this->setAllMimeAllowed($allMimes);	
		$this->setStripSpaces($stripSpaces);
		$this->setCleanXss($cleanXss);
		if($uploadPath != '') {
			$this->setUploadPath($uploadPath);
		}
	}
	
	/**
	 * Ustawia tablice dopuszczalnych rozszerzen dla uploadowanych plikow
	 * 
	 * @access public
	 * @param array|string Tablica rozszerzen
	 * @return void
	 * 
	 */	
	public function setAllowedFileExt($ext) { 
		if(is_array($ext)) {
			$this->_allowedFileExt = $ext;
		}
		elseif(is_string($ext)) {
			str_replace(';', ',', $ext);
			$extArr = explode(',', $ext);
			$this->_allowedFileExt = $extArr;
		}
		else {
			$this->_allowedFileExt = array();
		}
		
	}
	
	/**
	 * Ustawia mozliwosc uzywania wszystkich typow MIME
	 * 
	 * @access public
	 * @param bool //true jesli wszystkie maja byc uzywane
	 * @return void
	 * 
	 */	
	public function setAllMimeAllowed($allMime) {
		$this->_allMimeAllowed = ($allMime === true) ? true : false;
	}
	
	/**
	 * Ustawia maksymalna wielkosc plikow
	 * 
	 * @access public
	 * @param integer Wielkosc plikow
	 * @return void
	 * 
	 */	
	public function setMaxFileSize($size) {
		$this->_maxFileSize = ($size < 0) ? 0 : $size;
	}
	
	/**
	 * Ustawia maksymalna dlugosci nazw plikow
	 * 
	 * @access public
	 * @param integer Dlugosc nazwy pliku
	 * @return void
	 * 
	 */
	public function setMaxFileNameLength($length) {
		$this->_maxFileNameLength = ($length < 0) ? 0 : $length;
	}
	
	/**
	 * Ustawia sprawdzanie czy przeslany plik jest obrazkiem
	 * 
	 * @access public
	 * @param bool //true jesli ma byc sprawdzany
	 * @return void
	 * 
	 */	
	public function setCheckImage($check) {
		$this->_checkImage = ($check === true) ? true : false;
	}
	
	/**
	 * Ustawia maksymalna szerokosci obrazkow
	 * 
	 * @access public 
	 * @param integer Szerokosc obrazka
	 * @return void
	 * 
	 */	
	public function setMaxImageWidth($width) {
		$this->_maxImageWidth = ($width < 0) ? 0 : $width;		
	}
	
	/**
	 * Ustawia maksymalna wysokosc obrazka
	 * 
	 * @access public
	 * @param integer Wysokosc obrazka
	 * @return void
	 * 
	 */	
	public function setMaxImageHeight($height) {
		$this->_maxImageHeight = ($height < 0) ? 0 : $height;		
	}
	
	/**
	 * Ustawia sciezki do katalogu uploadu
	 * 
	 * @access public
	 * @param string Sciezka do katalogu uploadu 
	 * @return void
	 * 
	 */	
	public function setUploadPath($path) {
		$this->_uploadPath = (substr($path, -1) == '/') ? $path : $path . '/';
		$this->isValidUploadPath(); //moze rzucic wyjatkiem
	}
	
	/**
	 * Ustawia mozliwosc nadpisywania plikow
	 * 
	 * @access public
	 * @param bool //true jesli maja byc nadpisywane
	 * @return void
	 * 
	 */	
	public function setOverwrite($overwrite) {
		$this->_overwrite = ($overwrite  === true) ? true : false;		
	}	
	
	/**
	 * Ustawia kasowanie spacji z nazw plikow
	 * 
	 * @access public
	 * @param bool //true jesli maja byc usuwane
	 * @return void
	 * 
	 */	
	public function setStripSpaces($stripSpaces) {
		$this->_stripSpaces = ($stripSpaces  === true) ? true : false;		
	}	
	
	/**
	 * Ustawia sprawdzanie i czyszczenie zawartosci plikow
	 * 
	 * @access public
	 * @param bool //true jesli sprawdzanie i czyszczenie ma byc wlaczone
	 * @return void
	 * 
	 */		
	public function setCleanXss($cleanXss) {
		$this->_cleanXss = ($cleanXss  === true) ? true : false;		
	}
	
	/**
	 * Ustawia losowe nazwy dla plikow
	 * 
	 * @access public
	 * @param bool //true jesli nazwy maja byc losowe
	 * @return void
	 * 
	 */	
	public function setRandomName($random) {
		$this->_randomName = ($random  === true) ? true : false;		
	}
	
	/**
	 * Ustawia dozwole typy MIME dla obrazkow
	 * 
	 * @access public
	 * @param array Tablica z typami
	 * @return void
	 * 
	 */		
	public function setImageMimeType(Array $images) {
		$this->_imageMimeType = $images;		
	}	
	
	/**
	 * Zwraca tablice z bledami
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getErrors() {
		return $this->_errors;
	}
	
	/**
	 * Zwraca tablice z nazwami, tymczasowymi nazwami, wielkosciami, typami itd dla przetworzonych plikow
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getData() {
		if(!isset($this->_fileName[$this->_actualKey]) && !isset($this->_fileTempName[$this->_actualKey]) && !isset($this->_size[$this->_actualKey]) && !isset($this->_fileType[$this->_actualKey])) {
			return null;
		}
		$data = array();
		$cnt = $this->_actualKey;
		do {
			$data['name'][$cnt] = (isset($this->_fileName[$cnt])) ? $this->_fileName[$cnt] : null;			
			$data['temp_name'][$cnt] = (isset($this->_fileTempName[$cnt])) ? $this->_fileTempName[$cnt] : null;
			$data['error'][$cnt] = (isset($this->_errors[$cnt])) ? $this->_errors[$cnt] : null;
			$data['size'][$cnt] = (isset($this->_fileSize[$cnt])) ? $this->_fileSize[$cnt] : null;
			$data['type'][$cnt] = (isset($this->_fileType[$cnt])) ? $this->_fileType[$cnt] : null;
			$data['ext'][$cnt] = (isset($this->_fileExt[$cnt])) ? $this->_fileExt[$cnt] : null;	
			$data['imgW'][$cnt] = (isset($this->_imageWidth[$cnt])) ? $this->_imageWidth[$cnt] : null;
			$data['imgH'][$cnt] = (isset($this->_imageHeight[$cnt])) ? $this->_imageHeight[$cnt] : null;
			$cnt--;
		} while($cnt > -1);
		return $data;
	}
	
	/**
	 * Pobiera dane z tablicy $_FILES i uruchamia uplodowanie plikow 
	 * 
	 * @access public
	 * @param string Nazwa pola formularza ktorym przesylany jest plik
	 * @return bool|-1
	 * 
	 */	
	public function upload($field) {
		if(!isset($_FILES[$field])) {
			return -1;
		}
		if(is_array($_FILES[$field]['tmp_name'])) {
			foreach($_FILES[$field]['tmp_name'] AS $key => $value) {
				$this->_actualKey = $key; //klucz bedzie wykorzystywany przez wiekszosc metod
				if(!isset($_FILES[$field]['error'][$this->_actualKey])) { //jesli nie ma nic w tablicy bledow, ustawienie bledu o nieprzeslaniu pliku
					$_FILES[$field]['error'][$this->_actualKey] = 4;
				}
				if((!is_uploaded_file($value)) OR ($_FILES[$field]['error'][$this->_actualKey] != UPLOAD_ERR_OK)) { //jesli wystapil blad podczas przesylania lub plik nie zostal wyslany z formularza
					$this->_elementError[] = $this->getUploadError($_FILES[$field]['error'][$this->_actualKey]); //zgloszenie bledu
				}
				else {
					$this->_fileName[$this->_actualKey] = $_FILES[$field]['name'][$this->_actualKey]; //przypisanie nazwy aktualnie przetwarzanego pliku
					$this->_fileTempName[$this->_actualKey] = $_FILES[$field]['tmp_name'][$this->_actualKey]; //przypisanie nazwy tymczasowej aktualnie przetwarzanego pliku
					$this->_fileSize[$this->_actualKey] = $_FILES[$field]['size'][$this->_actualKey]; //przypisanie wielkosci aktualnie przetwarzanego pliku
					$this->_fileType[$this->_actualKey] = $_FILES[$field]['type'][$this->_actualKey]; //przypisanie typu pliku 
					$this->_imageHeight[$this->_actualKey] = 0;
					$this->_imageWidth[$this->_actualKey] = 0;
					$this->_fileExt[$this->_actualKey] = '';
					
					$this->prepareAndUpload(); //ta metoda robi cala prace dla kazdego pliku
					
				}
				if(count($this->_elementError) > 0) { //jesli wystapily bledy podczas przesylania pliku zgloszenie ich do tabeli bledow
					$this->_errors[$this->_actualKey] = array();
					$this->_errors[$this->_actualKey] = $this->_elementError;
					$this->_elementError = array();
				}
			}
		}
		else {
			$this->_actualKey = $key = 0; //klucz bedzie wykorzystywany przez wiekszosc metod
			if(!isset($_FILES[$field]['error'])) {
				$_FILES[$field]['error'] = 4;
			}
			if((!is_uploaded_file($_FILES[$field]['tmp_name'])) OR ($_FILES[$field]['error'] != UPLOAD_ERR_OK)) { //jesli wystapil blad podczas przesylania lub plik nie zostal wyslany z formularza
				$this->_elementError[] = $this->getUploadError($_FILES[$field]['error']); //zgloszenie bledu
			}
			else {
				$this->_fileName[$this->_actualKey] = $_FILES[$field]['name']; //przypisanie nazwy aktualnie przetwarzanego pliku
				$this->_fileTempName[$this->_actualKey] = $_FILES[$field]['tmp_name']; //przypisanie nazwy tymczasowej aktualnie przetwarzanego pliku
				$this->_fileSize[$this->_actualKey] = $_FILES[$field]['size']; //przypisanie wielkosci aktualnie przetwarzanego pliku
				$this->_fileType[$this->_actualKey] = $_FILES[$field]['type']; //przypisanie typu pliku 
				$this->_imageHeight[$this->_actualKey] = 0;
				$this->_imageWidth[$this->_actualKey] = 0;
				$this->_fileExt[$this->_actualKey] = '';

				$this->prepareAndUpload();
			}
			if(count($this->_elementError) > 0) {
				$this->_errors[$this->_actualKey] = array();
				$this->_errors[$this->_actualKey] = $this->_elementError;
				$this->_elementError = array();
			}
		}
		if(count($this->_errors) > 0) {
			return false;
		}
		else {
			return true;
		}
	}
	
	// Metody chronione

	/**
	 * Wykonuje testy na uplodowanym pliku i zapisuje go w docelowym katalogu
	 * 
	 * @access protected
	 * @return void
	 * 
	 */
	protected function prepareAndUpload() {		
		$this->_fileName[$this->_actualKey] = str_replace(array("\'", "\"", '<', '>', '!', ',', '/', "\\", ';', ':', '?', '+', '=', '@', '#', '$', '%', '&', '*', '(', ')', '{', '}', '[', ']', '|', '~', '`'), '', $this->_fileName[$this->_actualKey]); //usuniecie niebezpiecznych i niedozwolonych znakow z nazwy pliku
		if($this->_stripSpaces === true) {
			$this->_fileName[$this->_actualKey] = preg_replace('/ +/', '', $this->_fileName[$this->_actualKey]); //usuniecie spacji jesli tak ustawiono
		}
		$this->_fileType[$this->_actualKey] = strtolower(trim(stripslashes(preg_replace("/^(.+?);.*?$/", "\\1", $this->_fileType[$this->_actualKey])), '"')); //przypisanie typu pliku 

		if(strstr($this->_fileName[$this->_actualKey], '.')) { //jesli w nazwie pliku jest kropka
			$fileParts = explode('.', $this->_fileName[$this->_actualKey]);
			$this->_fileExt[$this->_actualKey] = end($fileParts); //pobieranie rozszerzenia
		}			
		if($this->isValidFileType() === false) { //sprawdzenie typu pliku
			$this->_elementError[] = 'Plik: ' . $this->_fileName[$this->_actualKey] . ' ma niedozwolony typ'; //jesli typ nieprawidlowy zgloszenie bledu
			return;
		}
		
		if($this->isValidFileSize() === false) { //sprawdzenie wielkosci pliku 
			$this->_elementError[] = 'Niedozwolona wielkosc pliku: ' . $this->_fileName[$this->_actualKey]; //jesli wielkosc nieprawidlowa zgloszenie bledu
			return;
		}
							
		if($this->_checkImage === true AND strstr($this->_fileType[$this->_actualKey], 'image')) {
			if($this->isImage() !== false) { //wykonuje sie jesli ustawiono sprawdzanie zdjec 
				if((!$this->isValidImageWidth()) OR (!$this->isValidImageHeight())) { //jesli wysokosc zdjecia jest niedozwolony
					$this->_elementError[] = 'Niedozwolona rozdzielczosc zdjecia. Plik: ' . $this->_fileName[$this->_actualKey]; //ustawienie bledu			
					return;
				}
				if(($this->_imageHeight[$this->_actualKey] == 0) OR ($this->_imageWidth[$this->_actualKey] == 0)) { //jesli szerokosc niedozwolona
					$this->_elementError[] = 'Przesylany plik: ' . $this->_fileName[$this->_actualKey] . ' nie jest zdjeciem';	//zgloszenie bledu
					return;
				}
			}
			else {
				$this->_elementError[] = 'Plik: ' . $this->_fileName[$this->_actualKey] . ' nie jest zdjeciem lub ma nieprawidlowy typ';
				return;
			}
		}
				
		if($this->_randomName === false) { //jesli nazwa pliku nie ma byc generowana losowo
			if($this->isValidFileNameLength() === false) { //sprawdzenie dlugosci nazwy pliku
				$baseFileName = '';
				$ext = '';
				if(strstr($this->_fileName[$this->_actualKey], '.')) { //jesli w nazwie jest kropka
					$fileParts = explode('.', $this->_fileName[$this->_actualKey]); 
					$ext = array_pop($fileParts); //pobranie rozszerzenia
					$baseFileName = implode('.', $fileParts); //pobranie nazwy pliku
				}
				$newLength = $this->_maxFileNameLength - strlen($ext) - 1; //nowa dlugosc nazwy pliku
				$baseFileName = substr($baseFileName, 0, $newLength); //ustawienie dozwolonej dlugosci nazwy
				$this->_fileName[$this->_actualKey] = $baseFileName . '.' . $ext; //i dodanie rozszerzenia
			}
			if($this->_overwrite === false) { //jesli nie nie mozna wyslac ponownie pliku nadpisujac go 
				if(file_exists($this->_uploadPath . $this->_fileName[$this->_actualKey])) { //sprawdzenie czy plik o ustawionej juz nazwie istnieje
					$this->_fileName[$this->_actualKey] = $this->generateRandomName(); //jesli tak wygenerowanie nazwy losowej
				}
			}	
		}
		else {
			$this->_fileName[$this->_actualKey] = $this->generateRandomName(); //generowanie nazwy losowej
		}
		if($this->_cleanXss === true) {
			$this->cleanXss();
		}
		$this->isValidUploadPath();
		if(!@copy($this->_fileTempName[$this->_actualKey], $this->_uploadPath . $this->_fileName[$this->_actualKey])) { //proba skopiowania pliku
			if(!@rename($this->_fileTempName[$this->_actualKey], $this->_uploadPath . $this->_fileName[$this->_actualKey])) { //jesli kopiowanie nieudane proba przeniesienia
				if(!@move_uploaded_file($this->_fileTempName[$this->_actualKey], $this->_uploadPath . $this->_fileName[$this->_actualKey])) { //jesli przeniesienie nieudane proba kopiowania dedykowana funkcja
					$this->_elementError[] = 'Nie mozna przeslac pliku: ' . $this->_fileName[$this->_actualKey] . ' do katalogu docelowego';//zgloszenie bledu w przypadku niepowodzenia
					return;
				}
			}
		}
	}
	
	/**
	 * Generuje losowa nazwe pliku, z oryginalnym rozszerzeniem
	 * 
	 * @access protected
	 * @return string
	 * 
	 */	
	protected function generateRandomName() {
		$name = md5(uniqid(mt_rand()));
		if($this->_maxFileNameLength != 0) {
			$name = substr($name, 0, $this->_maxFileNameLength - strlen($this->_fileExt[$this->_actualKey]) - 1);
		}
		$name .= '.' . $this->_fileExt[$this->_actualKey];
		return $name;
	}
	
	/**
	 * Zwraca komunikat na podstawie kodu bledu
	 * 
	 * @access protected
	 * @param integer Numer bledu
	 * @return string
	 * 
	 */	
	protected function getUploadError($errorNumber) {
		$errorMessage = '';
		switch($errorNumber) {
			case 1:
				$errorMessage = 'Wielkosc przesylanego pliku przekracza wartosc dyrektywy upload_max_filesize w php.ini';
				break;
			case 2:
				$errorMessage = 'Wielkosc przesylanego pliku przekracza wartosc dyrektywy MAX_FILE_SIZE wyspecyfikowanej w formularzu HTML';
				break;
			case 3:
				$errorMessage = 'Plik zostal przesleny tylko czesciowo';
				break;
			case 4:
				$errorMessage = 'Plik nie zostal przeslany';
				break;
			case 6:
				$errorMessage = 'Wystapil problem z folderem tymczasowym';										
				break;
			case 7:
				$errorMessage = 'Wystapil problem podczas zapisu pliku na dysku';
				break;
			case 8:
				$errorMessage = 'Rozszerzenie PHP zatrzymalo przesylanie pliku';
				break;
			default:
				$errorMessage = 'Nie przeslano zadnego pliku';				
				break;
		}
		return $errorMessage;
	}	
	
	/**
	 * Sprawdza na podstawie rozszerzenia czy typ pliku jest dopuszczalny 
	 * 
	 * @access protected
	 * @return bool
	 * 
	 */	
	protected function isValidFileType() {
		if($this->_allMimeAllowed === true) {
			return true;
		}
		if((count($this->_allowedFileExt) == 0) AND ($this->_allMimeAllowed !== true)) {
			return false;
		}
		if((!in_array($this->_fileExt[$this->_actualKey], $this->_allowedFileExt)) AND ($this->_allMimeAllowed !== true)) {
			return false;
		}
		$mimeType = $this->_fileManager->getMime($this->_fileName[$this->_actualKey]);
		if(is_array($mimeType)) {
			if(in_array($this->_fileType[$this->_actualKey], $mimeType)) {
				return true;
			}
		}
		elseif($mimeType == $this->_fileType[$this->_actualKey]) {
			return true;
		}
		return false;
	}
	
	/**
	 * Sprawdza czy wielkosc pliku jest dopuszczalna
	 * 
	 * @access protected
	 * @return bool
	 * 
	 */	
	protected function isValidFileSize() {
		if(($this->_maxFileSize != 0) AND ($this->_fileSize[$this->_actualKey] > $this->_maxFileSize)) {
			return false;
		}
		return true;
	}
	
	/**
	 * Sprawdza czy dopuszczalna jest dlugosc nazwy pliku
	 * 
	 * @access protected
	 * @return bool
	 * 
	 */	
	protected function isValidFileNameLength() {
		if(($this->_maxFileNameLength != 0) AND (strlen($this->_fileName[$this->_actualKey]) > $this->_maxFileNameLength)) {
			return false;
		}
		return true;
	}
	
	/**
	 * Sprawdza czy obrazek ma dopuszczony typ MIME
	 * 
	 * @access protected
	 * @return bool
	 * 
	 */	
	protected function isImage() {
		if(in_array($this->_fileType[$this->_actualKey], $this->_imageMimeType)) {
			return true;
		}
		return false;
	}
	
	/**
	 * Sprawdza czy szerokosc obrazka jest dopuszczalna
	 * 
	 * @access protected
	 * @return bool
	 * 
	 */	
	protected function isValidImageWidth() {
		if(($this->_imageWidth[$this->_actualKey] == 0) OR ($this->_imageHeight[$this->_actualKey] == 0)) {
			$tmpD = getimagesize($this->_fileTempName[$this->_actualKey]);
			if($tmpD === false) {
				return false;
			}
			$this->_imageWidth[$this->_actualKey] = $tmpD[0];
			$this->_imageHeight[$this->_actualKey] = $tmpD[1];
		}
		if(($this->_maxImageWidth != 0) AND ($this->_imageWidth[$this->_actualKey] > $this->_maxImageWidth)) {
			return false;
		}
		return true;		
	}
	
	/**
	 * Sprawdza czy wysokosc obrazka jest dopuszczalna
	 * 
	 * @access protected
	 * @return bool
	 * 
	 */	
	protected function isValidImageHeight() {
		if(($this->_imageWidth[$this->_actualKey] == 0) OR ($this->_imageHeight[$this->_actualKey] == 0)) {
			$tmpD = getimagesize($this->_fileTempName[$this->_actualKey]);
			if($tmpD === false) {
				return false;
			}			
			$this->_imageWidth[$this->_actualKey] = $tmpD[0];
			$this->_imageHeight[$this->_actualKey] = $tmpD[1];
		}
		if(($this->_maxImageHeight != 0) AND ($this->_imageHeight[$this->_actualKey] > $this->_maxImageHeight)) {
			return false;
		}
		return true;
	}
	
	/**
	 * Sprawdza czy sciezka uploadu jest prawidlowa
	 * 
	 * @access protected
	 * @return bool
	 * 
	 */	
	protected function isValidUploadPath() {
		if($this->_uploadPath == '') {
			throw new UploadException('Nie ustawiono sciezki do katalogu uploadu');			
		}
		if(!is_dir($this->_uploadPath)) {
			throw new UploadException('Sciezka: ' . $this->_uploadPath . ' nie prowadzi do katalogu');
		}
		if(!is_writable($this->_uploadPath)) {
			throw new UploadException('W katalogu uploadu: ' . $this->_uploadPath . ' nie mozna zapisywac');
		}
	}
	
	/**
	 * Wykonuje sprawdzanie i czyszczenie z niebezpiecznych tresci
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function cleanXss() {
	 	$filePath = $this->_fileTempName[$this->_actualKey];
		try {
			$data = $this->_fileManager->readToString($filePath);
			$data = $this->_xssFilter->filter($data);
			$this->_fileManager->write($filePath, $data);
		} catch(DirAndFileManagerException $e) {
			throw $e;
		}
	}
	
}

?>
