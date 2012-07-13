<?php

/**
 * @class Response
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Response implements IResponse {

	/**
	 * Tablica z kodami statusu
	 *
	 * @var array
	 */
	protected $_statusCodes = array('200' => 'OK',
						'201' => 'Created',
						'202' => 'Accepted',
						'203' => 'Non-Authoritative Information',
						'204' => 'No Content',
						'205' => 'Reset Content',
						'206' => 'Partial Content',
						'300' => 'Multiple Choices', 
						'301' => 'Moved Permanently', 
						'302' => 'Found',
						'304' => 'Not Modified',
						'305' => 'Use Proxy',
						'307' => 'Temporary Redirect',
						'400' => 'Bad Request',
						'401' => 'Unauthorized',
						'403' => 'Forbidden',
						'404' => 'Not Found',
						'405' => 'Method Not Allowed',
						'406' => 'Not Acceptable',
						'407' => 'Proxy Authentication Required',
						'408' => 'Request Timeout',
						'409' => 'Conflict',
						'410' => 'Gone', 
						'411' => 'Length Required',
						'412' => 'Precondition Failed',
						'413' => 'Request Entity Too Large',
						'414' => 'Request-URI Too Long',
						'415' => 'Unsupported Media Type',
						'416' => 'Requested Range Not Satisfiable',
						'417' => 'Expectation Failed',
						'500' => 'Internal Server Error',
						'501' => 'Not Implemented',
						'502' => 'Bad Gateway',
						'503' => 'Service Unavailable',
						'504' => 'Gateway Timeout',
						'505' => 'HTTP Version Not Supported'
	);
	
	/**
	 * Obiekt obslugi zadania
	 *
	 * @var object
	 */	
	protected $_request = null;
	
	/**
	 * Obiekt prostych benchmarkow
	 *
	 * @var object
	 */	
	protected $_benchmark = null;
	
	/**
	 * Obiekt obslugi plikow
	 *
	 * @var object
	 */	
	protected $_fileManager = null;
	
	/**
	 * Tablica z danymi do wyslania w naglowkach
	 *
	 * @var array
	 */	
	protected $_headers = array();
	
	/**
	 * Obiekt widoku
	 *
	 * @var object
	 */	
	protected $_outputView = null;
	
	/**
	 * Tag w szablonie do zastopienia przez czas generowania strony
	 *
	 * @var string
	 */	
	protected $_timeElapsedTag = '';
	
	/**
	 * Tag w szablonie do zastopienia przez wartosc zuzytej pamieci
	 *
	 * @var string
	 */	
	protected $_memoryUsageTag = ''; 
	
	/**
	 * Wartosc wskazuje czy jest mozliwe uzycie kompresji w czasie wysylania strony/dokumentu do przegladarki
	 *
	 * @var bool
	 */		
	protected $_compressOutput = true;
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt requestu
	 * @param object Obiekt benchmarka
	 * @param object Obiekt obslugi plikow
	 * @param bool Wlaczenie/wylaczenie kompresji
	 * @param string Tag podmieniany na czas ladowania strony
	 * @param string Tag podmieniany na ilosc pamieci zuzytej podczas renderowania strony
	 * 
	 */	
	public function __construct(IRequest $request, IBenchmark $benchmark, IFileManager $fileManager, $compressOutput=true, $timeElapsedTag='timeElapsedTag', $memoryUsageTag='memoryUsageTag') {
		$this->_request = $request;
		$this->_benchmark = $benchmark;
		$this->_fileManager = $fileManager;
		$this->_compressOutput = (bool)$compressOutput;
		$this->_timeElapsedTag = $timeElapsedTag;
		$this->_memoryUsageTag = $memoryUsageTag;
	}
	
	/**
	 * Zwraca pewne informacje o wyjsciu
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function __toString() {
		$str = get_class($this->_output);
		return $str;
	}	
	
	/**
	 * Ustawia tablice z parametrami dla naglowkow
	 * 
	 * @access public
	 * @param array Tablica z parametrami naglowkow
	 * @return void
	 * 
	 */	
	public function setHeaders($headers=array()) {
		$this->_headers = $headers;
	}	
	
	/**
	 * Ustawia wartosc wyjsciowa 
	 * 
	 * @access public
	 * @param object Obiekt widoku
	 * @return void
	 * 
	 */		
	public function setOutputView($outputView) {
		$this->_outputView = $outputView;
	}
	
	/**
	 * Zwraca zawartosc dokumentu wyjsciowego
	 * 
	 * @access public
	 * @return object
	 * 
	 */		
	public function getOutputView() {
		return $this->_outputView;
	}
	
	/**
	 * Dodaje naglowek do grupy naglowkow do wyslania
	 * 
	 * @access public
	 * @param string Wartosc naglowka
	 * @param replace Informuje czy zastapic poprzedni naglowek default=true 
	 * @return void
	 * 
	 */	
	public function addHeader($headerContent, $replace=true) {
		$this->_headers[] = array($headerContent, $replace);
	}	
	
	/**
	 * Wysyla dokument do przegladarki
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function sendOutput() {
		if($this->_outputView instanceof ViewFile) {
			$this->sendFile();
		}
		elseif(($this->_outputView instanceof ViewTemplate) || ($this->_outputView instanceof Layout) || ($this->_outputView instanceof ViewPlain)) {
			$mimeType = $this->_outputView->getMimeType();
			$headerContent = (strstr($mimeType, "Content-type")) ? $mimeType : "Content-type: " . $mimeType . ";";
			$this->addHeader($headerContent);
			if($this->_compressOutput == true) {
				if(extension_loaded('zlib')) {
					$acceptEncoding = $this->_request->server('HTTP_ACCEPT_ENCODING');
					if(($acceptEncoding != null) && (strpos($acceptEncoding, 'gzip') !== false)) {
						ob_start('gz_handler');
					}
					else {
						ob_start();
					}
				}
				else {
					ob_start();
				}
			}
			else {
				ob_start();
			}
			$result = $this->_outputView->fetch();
			$memoryUsage = (!function_exists('memory_get_usage')) ? 'NA' : round(memory_get_usage()/1024/1024, 2) . 'MB';			
			$timeElapsed = $this->_benchmark->getElapsedTime();			
			$result = str_replace($this->_memoryUsageTag, $memoryUsage, $result);
			$result = str_replace($this->_timeElapsedTag, $timeElapsed, $result);		
			$this->sendHeaders();
			echo $result;
			$buffer = ob_get_contents();
			ob_end_clean();	
			echo $buffer;
		}
		else {
			$this->sendHeaders();
		}
			
	}
	
	/**
	 * Wysyla kod statusu
	 * 
	 * @access public
	 * @param string Liczbowy kod statusu
	 * @param string Wiadomosc wysylana razem z kodem statusu 
	 * @return void
	 * 
	 */		
	public function sendStatusHeader($code='200', $message='') {
		if(empty($code) || !is_numeric($code)) {
			throw new ResponseException('Musisz podac poprawny kod statusu');
		}
		else {
			if(empty($message)) { 
				if(!isset($this->_statusCodes[$code])) {
					throw new ResponseException('Musisz podac tresc komunikatu statusu');
				}
				else {
					$message = $this->_statusCodes[$code];
				}
			}
		}		
		
		$serverProtocol = $this->_request->server('SERVER_PROTOCOL');
		if(substr(php_sapi_name(), 0, 3) == 'cgi') {
			header("Status: " . $code . " " . $message, true);
		}
		if(($serverProtocol !== null) && ($serverProtocol == 'HTTP/1.0')) {
			header("HTTP/1.0 " . $code . " " . $message, true, $code);
		}
		else {
			header("HTTP/1.1 " . $code . " " . $message, true, $code);		
		}
	}
	
	/**
	 * Wysyla naglowki
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function sendHeaders() {
		if(count($this->_headers) > 0) {
			foreach($this->_headers AS $header) {
				header($header[0], $header[1]);
			}
		}
	}	

	/**
	 * Wysyla plik
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	//czesciowo z dokumentacji PHP
	protected function sendFile() {
		$filePath = $this->_outputView->fetch();
		$resume=true;
		
		$fileSize = filesize($filePath);
		$fileName = basename($filePath);
		$dirName = dirname($filePath);
		if(strstr($this->_request->server('HTTP_USER_AGENT'), 'MSIE')) {
			$fileName = preg_replace("/(\.([^\.]*))$/", "##\\2", $fileName);
			$fileName = preg_replace("/\./", "_", $fileName);
			$fileName = preg_replace("/(\##([^\.]*))$/", ".\\2", $fileName);			
		}
	
		$mimeType = '';
		$mimeType = $this->_outputView->getMimeType();	
		if(($mimeType == 'application/x-unknown-content-type') || ($mimeType == 'application/force-download')) {
			//$mimeType = 'application/force-download';
			$mimeType = $this->_fileManager->getMime($filePath);
			if(($mimeType == 'application/x-unknown-content-type') || ($mimeType == 'application/force-download')) {
				$mimeType = 'application/octet-stream';
			}
		}		
		
		$httpRange = null;
		$range = null;
		$httpRange = $this->_request->server('HTTP_RANGE');
		if($resume && $httpRange) { //Czy HTTP_RANGE zostalo wyslane przez przegladarke
			list($sizeUnit, $rangeOrig) = explode('=', $httpRange, 2);
			if($sizeUnit == 'bytes') {
				list($range, $extraRanges) = explode(',', $rangeOrig, 2);
			}
			else {
				$range = '';
			}
		}
		else {
			$range = '';
		}
		
		if(!empty($range)) {
			list($seekStart, $seekEnd) = explode('-', $range, 2); //pobranie poczatku i konca z HTTP_RANGE lub jesli nie ma ustawienie wartosci domyslnych
		}
		$seekEnd = (empty($seekEnd)) ? ($fileSize - 1) : min(abs(intval($seekEnd)),($fileSize - 1));
		$seekStart = (empty($seekStart) || $seekEnd < abs(intval($seekStart))) ? 0 : max(abs(intval($seekStart)),0);

		if($resume) { //ustawienie naglowkow jesli mozna wznawiac sciaganie
			if($seekStart > 0 || $seekEnd < ($fileSize - 1)) { //to dla IE
				header('HTTP/1.1 206 Partial Content');
			}
			header('Accept-Ranges: bytes');
			header('Content-Range: bytes ' . $seekStart . '-' . $seekEnd . '/' . $fileSize);			
		}
		
		//ponizsze dwa naglowki: czy konieczne?
		header("Cache-Control: cache, must-revalidate");   
		header("Pragma: public");
		
		if(is_array($mimeType)) {
			foreach($mimeType AS $type) {
				header('Content-Type: ' . $type);
			}
		}
		else {
			header('Content-Type: ' . $mimeType);
		}
		header('Content-Disposition: attachment; filename="' . $fileName . '"');
		header('Content-Length: ' . ($seekEnd - $seekStart + 1));
		
		/*
		header('Content-Description: File Transfer');
		header('Content-Type: '.$mimeType);
		header('Content-Disposition: attachment; filename='.$fileName);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . $fileSize);
		header('X-Content-Type-Options: nosniff');
		*/
		
		ob_clean();
		flush();		
		
		
		$fileObject = $this->_fileManager->getHandler(); //zaladowanie pliku do obiektu
		$fileObject->open($filePath, 'rb'); //otwarcie pliku
		$fileObject->seek($seekStart); //ustawienie pozycji na poczatku niedokonczonego fragmentu
		$fileObject->readToBuffer(); //download z buforowaniem
		$fileObject->close(); //zamkniecie pliku
		exit(0);
	}
	
}

?>
