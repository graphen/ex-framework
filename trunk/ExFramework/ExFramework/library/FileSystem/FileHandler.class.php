<?php

/**
 * @class FileHandler
 *
 * @author Przemyslaw Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FileHandler implements IFileHandler {
	
	/**
	 * Sciezka do pliku
	 *
	 * @var string
	 * 
	 */
	protected $_filePath = null;
	
	/**
	 * Wskaznik do pliku
	 *
	 * @var resource
	 * 
	 */
	protected $_fileHandler = null;
	
	/**
	 * Tryb otwacia pliku
	 *
	 * @var string
	 * 
	 */
	protected $_mode = null;
		
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
	 * Destruktor
	 * 
	 * @access public
	 *
	 */
	public function __destruct() {
		$this->close();
	}	
	
	/**
	 * Ustawia sciezke do pliku
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */
	public function setFilePath($filePath) {
		$this->_filePath = $filePath;
	}

	/**
	 * Ustawia tryb otwarcia pliku
	 *
	 * @access public
	 * @param string Tryb otwarcia pliku
	 * @return void
	 * 
	 */
	public function setMode($mode) {
		$this->_mode = $mode;
	}
	
	/**
	 * Otwiera plik
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @param string Tryb
	 * @return void
	 * 
	 */
	public function open($filePath=null, $mode='r') {
		if(($this->_fileHandler !== null) && is_resource($this->_fileHandler)) {
			$this->close();
		}
		if($this->_mode === null) {
			$this->_mode = $mode;
		}
		$mode = $this->_mode;
		$binary = false;
		if(strstr($mode, 'b')) {
			$binary = true;
			$mode = str_replace('b', '', $mode);
		}
		if(!is_null($filePath)) {
			$this->_filePath = $filePath;
		}
		if(is_null($this->_filePath)) {
			throw new FileHandlerException('Nie podano sciezki do pliku');
		}
		if((file_exists($this->_filePath)) && ($mode == 'x' || $mode == 'x+')) {
			throw new FileHandlerException('Plik juz istnieje');
		}
		if((!file_exists($this->_filePath)) && ($mode == 'x' || $mode == 'x+')) {
			if($binary === true) {
				$mode = $mode . 'b';
			}
			if(!($this->_fileHandler = @fopen($this->_filePath, $mode))) {
				throw new FileHandlerException('Nie mozna utwozyc i otworzyc pliku: ' . $this->_filePath);
			}
			return;
		}
		
		if((!file_exists($this->_filePath)) && ($mode == 'r' || $mode == 'r+')) {
			throw new FileHandlerException('Plik: ' . $this->_filePath . ' nie istnieje, trzeba go najpiew utworzyc');
		}
	
		if((!file_exists($this->_filePath)) && ($mode == 'w' || $mode == 'w+' || $mode == 'a' || $mode == 'a+')) {
			if($binary) $mode = $mode . 'b';
			if(!($this->_fileHandler = @fopen($this->_filePath, $mode))) {
				throw new FileHandlerException('Nie mozna utworzyc i otworzyc pliku: ' . $this->_filePath);
			}
			return;
		}
		
		if(!is_file($this->_filePath)) {
			throw new FileHandlerException('Plik: ' . $this->_filePath . ' nie jest zwyklym plikiem');
		}
		if($mode == 'r') {
			if(!is_readable($this->_filePath)) {
				throw new FileHandlerException('Nie mozna czytac pliku: ' . $this->_filePath);
			}
		}
		if($mode == 'r+' || $mode == 'w' || $mode == 'w+' || $mode == 'a' || $mode == 'a+') {
			if((!is_readable($this->_filePath)) || (!is_writeable($this->_filePath))) {
				throw new FileHandlerException('Nie mozna czytac lub pisac do pliku: ' . $this->_filePath);
			}
		}
		if($binary === true) {
			$mode = $mode . 'b';
		}
		if(!($this->_fileHandler = @fopen($this->_filePath, $mode))) {
			throw new FileHandlerException('Nie mozna otworzyc pliku: ' . $this->_filePath);
		}
	}
	
	/**
	 * Czyta z pliku i zwraca odczytany ciag
	 *
	 * @access public
	 * @param integer Dlugosc czytanego ciagu
	 * @return string
	 * 
	 */
	public function read($length=null) {
		if(is_null($this->_fileHandler) || !is_resource($this->_fileHandler)) {
			$this->open();
		}		
		if(!flock($this->_fileHandler, LOCK_SH)) {
			throw new FileHandlerException('Nie mozna zablokowac pliku: ' . $this->_filePath . ' na czas odczytu');
		}
		$data = '';
		if(!($fileSize = filesize($this->_filePath))) {
			throw new FileHandlerException('Nie mozna pobrac wielkosci pliku: ' . $this->_filePath);
		}
		$length = ($length === null) ? $fileSize : $length;
		while(!feof($this->_fileHandler)) {
			$toRead = (8192 > $length) ? 8192 : $length;
			if(false === ($data .= fread($this->_fileHandler, $toRead))) {
				throw new FileHandlerException('Nie mozna czytac pliku: ' . $this->_filePath);
			}
			$length = $length - 8192;
		}
		if(!flock($this->_fileHandler, LOCK_UN)) {
			throw new FileHandlerException('Nie mozna odblokowac pliku: ' . $this->_filePath);
		}
		return $data;	
	}
	
	/**
	 * Czyta z pliku porcje danych do bufora i oproznia go. Funkcja uzywana do buforowanego sciagania plikow
	 *
	 * @access public
	 * @param integer Transfer
	 * @return void
	 * 
	 */
	public function readToBuffer($maxSpeed=100) {
		if(is_null($this->_fileHandler) || !is_resource($this->_fileHandler)) {
			$this->open(null, 'rb');
		}
		if(!flock($this->_fileHandler, LOCK_SH)) {
			throw new FileHandlerException('Nie mozna zablokowac pliku: ' . $this->_filePath . ' na czas odczytu');
		}
		while((!feof($this->_fileHandler)) && (connection_status() == 0)) {
			set_time_limit(0); //resetowanie zegara, dla duzych plikow
			$data = fread($this->_fileHandler, 1024*$maxSpeed);
			print($data);
			flush();
			ob_flush();
			sleep(1);
		}
		if(!flock($this->_fileHandler, LOCK_UN)) {
			throw new FileHandlerException('Nie mozna odblokowac pliku: ' . $this->_filePath);
		}
	}	
	
	/**
	 * Odczytuje plik linia po linii i zwraca zawartosc w tablicy
	 *
	 * @access public
	 * @param integer Dlugosc linii
	 * @param integer Ilosc linii do odczytania
	 * @return array
	 * 
	 */
	public function readLines($lineLength=1024, $linesNumber=null) {
		if(is_null($this->_fileHandler) || !is_resource($this->_fileHandler)) {
			$this->open();
		}
		$line = '';
		$data = array();
		if(!flock($this->_fileHandler, LOCK_SH)) {
			throw new FileHandlerException('Nie mozna zablokowac pliku: ' . $this->_filePath . ' na czas odczytu');
		}
		$counter = 0;
		while(false !== ($line = fgets($this->_fileHandler, $lineLength))) {
			$data[] = $line;
			if(!is_null($linesNumber)) {
				$counter++;
				if ($counter == $linesNumber)
					break;
			}
		}
		if(!flock($this->_fileHandler, LOCK_UN)) {
			throw new FileHandlerException('Nie mozna odblokowac pliku: ' . $this->_filePath);
		}		
		return $data;
	}
	
	/**
	 *  Czyta plik znak po znaku i zwraca odczytany ciag
	 *
	 * @access public
	 * @param integer Dlugosc ciagu do odczytania
	 * @return string
	 * 
	 */
	public function readChars($length=null) {
		if(is_null($this->_fileHandler) || !is_resource($this->_fileHandler)) {
			$this->open();
		}
		$char = null;
		$data = '';	
		if(!flock($this->_fileHandler, LOCK_SH)) {
			throw new FileHandlerException('Nie mozna zablokowac pliku: ' . $this->_filePath . ' na czas odczytu');
		}
		while(!feof($this->_fileHandler)) {
			if(($length != null) && (strlen($data) == $length)) {
				break;
			}
			if(false === ($char = fgetc($this->_fileHandler))) {
				throw new FileHandlerException('Nie mozna czytac pliku:' . $this->_filePath);
			}
			$data .= $char;
		}
		if(!flock($this->_fileHandler, LOCK_UN)) {
			throw new FileHandlerException('Nie mozna odblokowac pliku: ' . $this->_filePath);
		}		
		return $data;
	}
	
	/**
	 * @Czyta pliki CSV i zwraca zawartosc w formie tablicy
	 *
	 * @access public
	 * @param integer Dlugosc linii
	 * @param string Delimiter
	 * @return array
	 * 
	 */
	public function readCsv($length=null, $delimiter=',') {
		if(is_null($this->_fileHandler) || !is_resource($this->_fileHandler)) {
			$this->open();
		}
		if($length === null) {
			$length = 8192;
		}
		$line = array();
		$csv = array();	
		if(!flock($this->_fileHandler, LOCK_SH)) {
			throw new FileHandlerException('Nie mozna zablokowac pliku: ' . $this->_filePath . ' na czas odczytu');
		}
		while(false !== ($line = fgetcsv($this->_fileHandler, $length, $delimiter))) {
			$csv[] = $line;
		}
		if(!flock($this->_fileHandler, LOCK_UN)) {
			throw new FileHandlerException('Nie mozna odblokowac pliku: ' . $this->_filePath);
		}		
		return $csv;
	}

	/**
	 * Zapisuje dane do pliku CSV
	 *
	 * @access public
	 * @param array Dane
	 * @return void
	 * 
	 */
	public function writeCsv($data) {
		if(is_null($this->_fileHandler) || !is_resource($this->_fileHandler)) {
			$this->open();
		}
		if(!flock($this->_fileHandler, LOCK_EX)) {
			throw new FileHandlerException('Nie mozna zablokowac pliku: ' . $this->_filePath . ' do zapisu');
		}
		foreach($data as $index => $value) {
			$v = array();
			if(is_string($value)) {
				$v = explode(',', $value);
			}
			elseif(is_array($value)) {
				$v = $value;
			}
			else {
				throw new FileHandlerException('Dane o nieprawidlowym formacie');
			}
			if (!fputcsv($this->_fileHandler, $v)) {
				throw new FileHandlerException('Nie mozna pisac do pliku CSV: ' . $this->_filePath);
			}
		}
		if(!flock($this->_fileHandler, LOCK_UN)) {
			throw new FileHandlerException('Nie mozna odblokowac pliku: ' . $this->_filePath);
		}
	}
	
	/**
	 * Zapisuje dane do pliku
	 *
	 * @access public
	 * @param string Dane
	 * @param int Dlugosc zapisywanego ciagu
	 * @return void
	 * 
	 */
	public function write($data=null, $length=null) {
		if($length === null) {
			$length = strlen($data);
		}
		if(is_null($this->_fileHandler) || !is_resource($this->_fileHandler)) {
			$this->open();
		}
		if(!flock($this->_fileHandler, LOCK_EX)) {
			throw new FileHandlerException('Nie mozna zablokowac pliku: ' . $this->_filePath . ' na czas zapisu');
		}
		if(!fwrite($this->_fileHandler, (string) $data, $length)) {
			throw new FileHandlerException('Nie mozna pisac do pliku: ' . $this->_filePath);
		}
		if(!flock($this->_fileHandler, LOCK_UN)) {
			throw new FileHandlerException('Nie mozna odblokowac pliku: ' . $this->_filePath);
		}
	}

	/**
	 * Ustawia wskaznik na poczatku pliku
	 *
	 * @access public
	 * @return void
	 * 
	 */
	public function rewind() {
		if(is_null($this->_fileHandler) || !is_resource($this->_fileHandler)) {
			$this->open();
		}
		if(!(rewind($this->_fileHandler))) {
			throw new FileHandlerException('Nie mozna przesunac wskaznika na poczatek pliku: ' . $this->_filePath);
		}
	}
	
	/**
	 * Zamyka plik
	 *
	 * @access public
	 * @return void
	 */
	public function close() {
		if(!is_null($this->_fileHandler) && is_resource($this->_fileHandler)) {
			if (!fclose($this->_fileHandler)) {
				throw new FileHandlerException('Nie mozna zamknac pliku: ' . $this->_filePath);
			}
			$this->_fileHandler = null;
		}
	}
	
	/*
	 * Zwraca pozycje wskaznika
	 * Jesli $mode ustawiony jest na 'a' zawsze zwroci int(0)
	 * 
	 * @acess public
	 * @return int
	 * 
	 */
	public function tell() {
		if(is_null($this->_fileHandler) || !is_resource($this->_fileHandler)) {
			$this->open();
		}		
		return ftell($this->_fileHandler);
	}

	/*
	 * Ustawia wskaznik na danej pozycji
	 * Jesli $mode ustawiony jest na 'a' nie dziala bo fseek zawsze zwroci int(0)
	 * 
	 * @acess public
	 * @param integer Nowa pozycja wskaznika
	 * @param string Wskazuje odkad liczyc. Moze byc 'set', 'end', 'cur' 
	 * @return void
	 * 
	 */
	public function seek($offset, $w='') {
		if(is_null($this->_fileHandler) || !is_resource($this->_fileHandler)) {
			$this->open();
		}		
		$wh = '';
		if($w == 'cur') {
			$wh = SEEK_CUR;
		}
		elseif($w == 'end') {
			$wh = SEEK_END;
		}
		elseif($w == 'set') {
			$wh = SEEK_SET;
		}
		else {
			$wh = SEEK_SET;
		}
		
		if(fseek($this->_fileHandler, $offset, $wh) == -1) {
			throw new FileHandlerException('Nie mozna ustawic pozycji wskaznika w pliku: ' . $this->_filePath);
		}
	}	
	
	/**
	 * Wyswietla zawartosc pliku
	 *
	 * @access public
	 * @return void
	 * 
	 */
	public function show() {
		if(is_null($this->_fileHandler) || !is_resource($this->_fileHandler)) {
			$this->open();
		}
		if($this->tell() != 0) {
			$this->rewind();
		}
		if(!(fpassthru($this->_fileHandler))) {
			throw new FileHandlerException('Nie mozna czytac pliku: ' . $this->_filePath);
		}
	}
	
	/**
	 * Zwraca tablice z wlasciwosciami pliku
	 *
	 * @access public
	 * @return array
	 * 
	 */
	public function stats() {
		$stats = array();
		if(is_null($this->_fileHandler) || !is_resource($this->_fileHandler)) {
			$this->open();
		}
		if (!($stats = fstat($this->_fileHandler))) {
			throw new FileHandlerException('Nie mozna pobrac statystyki pliku: ' . $this->_filePath);
		}
		return array_slice($stats, 13);
	}	
	
}

?>
