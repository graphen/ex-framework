 <?php
 
/**
 * @class LoggerFiles
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class LoggerFiles implements ILoggerFiles {

	/**
	 * Wskaznik do pliku
	 *
	 * @var resource
	 */

	protected $_filePointer = null;

	/**
	 * Sciezka do pliku logow
	 *
	 * @var string
	 */
	protected $_logFilePath = null;

	/**
	 * Czy logowanie wlaczone
	 *
	 * @var bool
	 */	
	protected $_enabled = true;

	/**
	 * Poziomy logowania
	 *
	 * @var array
	 */		
	protected $_levels = array('Error', 'Warning', 'Info', 'All');
	
	/**
	 * Konstuktor
	 *
	 * @access public
	 * @param string Sciezka do pliku logow
	 * @param bool Wlaczenie/wylaczenie logowania
	 * 
	 */
	public function __construct($logFilePath, $logEnabled=true) {
		$this->setLogFilePath($logFilePath);
		$this->setLogEnabled($logEnabled);	
	}
	
	/**
	 * Ustawia sciezke do pliku logow
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */	
	public function setLogFilePath($filePath) {
		if(!file_exists($filePath)) {
			if(($created = @touch($filePath)) == false) {
				throw new LoggerException('Nie mozna otworzyc pliku logow: ' . $filePath . ' do zapisu');
			}
			chmod($filePath, 0666);
			if(!file_exists($filePath)) {
				throw new LoggerException('Plik logow: ' . $filePath . ' nie istnieje');
			}
		}
		if(!is_writable($filePath) OR !is_readable($filePath)) {
			throw new LoggerException('Do pliku logow: ' . $filePath . ' nie mozna zapisywac lub z niego czytac');
		}			
		$this->_logFilePath = (string)$filePath;
	}
	
	/**
	 * Wlacza lub wylacza logowanie
	 *
	 * @access public
	 * @param bool Wlaczenie lub wylaczeine logowania
	 * @return void
	 * 
	 */		
	public function setLogEnabled($logEnabled) {
		$this->_enabled = (bool)$logEnabled;
	}
	
	/**
	 * Destruktor
	 *	
	 * @access public
	 * 
	 */
	public function __destruct() {
		if(is_resource($this->_filePointer)) {
			fclose($this->_filePointer);	
		}
	}
	
	/**
	 * @todo: TO jest nie sprawdzone 
	 * Loguje wyjatki
	 * 
	 * @access public
	 * @param object Objekt wyjatku
	 * @return void
	 * 
	 */
	public function logException(Exception $exception) {
		if($this->_enabled === false) {
			return;
		}
		$this->openFile();
		$date = date("Y-m-d H:i:s");
		$level = "Error";
		$code = $exception->getCode();
		$file = $exception->getFile();
		$line = $exception->getLine();
		$message = $exception->getMessage();
		$logString = $date . " [" . $level . "] " . " - (" . $code . ") " . $message;
		$logString .= " in " .$file;
		$logString .= " on line " . $line;
		$logString .= "\n";
		flock($this->_filePointer, LOCK_EX);		
		if(!fwrite($this->_filePointer, $logString)) {
			throw new LoggerException('Nie mozna zapisac do pliku logow: ' . $this->_logFilePath);
		}
		flock($this->_filePointer, LOCK_UN);
	}
	
	/**
	 * Loguje zdarzenia do pliku
	 * 
	 * @access public
	 * @param string Informacja o zdarzeniu
	 * @param string Poziom logowania
	 * @param string Nazwa pliku w ktorym doszlo do zdarzenia
	 * @param string Numer linii w ktorej nastapilo zdarzenie
	 * @return void
	 * 
	 */
	public function log($message, $level='Error', $file=null, $line=null) {
		if($this->_enabled === false) {
			return;
		}
		$this->openFile();
		$date = date("Y-m-d H:i:s");
		$level = ucfirst(strtolower($level));
		$level = (in_array($level, $this->_levels)) ? $level : "Error";
		$logString = $date . " [" . $level . "] " . " - " . $message;
		$logString .= (!$file) ? "" : " in $file";
		$logString .= (!$line) ? "" : " on line $line";
		$logString .= "\n";
		flock($this->_filePointer, LOCK_EX);		
		if(!fwrite($this->_filePointer, $logString)) {
			throw new LoggerException('Nie mozna zapisac do pliku logow: ' . $this->_logFilePath);
		}
		flock($this->_filePointer, LOCK_UN);	
	}
	
	/**
	 * Zwaca zawartosc pliku logow
	 *
	 * @access public
	 * @return string
	 * 
	 */
	public function getLogs() {
		$content = file_get_contents($this->_logFilePath);
		return $content;
	}
	
	/**
	 * Otwiera plik logow
	 *
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function openFile() {
		if(is_resource($this->_filePointer)) {
			return;
		}
		if(empty($this->_logFilePath)) {
			throw new LoggerException('Nie podano sciezki do pliku logow');
		}
		if(!file_exists($this->_logFilePath)) {
			if(($created = @touch($this->_logFilePath)) == false) {
				throw new LoggerException('Nie mozna otworzyc pliku logow: ' . $this->_logFilePath . ' do zapisu');
			}
			chmod($this->_logFilePath, 0666);
			if(!file_exists($this->_logFilePath)) {
				throw new LoggerException('Plik logow: ' . $this->_logFilePath . ' nie istnieje');
			}
		}		
		if(!($this->_filePointer = fopen($this->_logFilePath, 'a+'))){
			throw new LoggerException('Nie mozna otworzyc pliku logow: ' . $this->_logFilePath . ' do zapisu');
		}
		if(!is_writable($this->_logFilePath) OR !is_readable($this->_logFilePath)) {
			throw new LoggerException('Do pliku logow: ' . $this->_logFilePath . ' nie mozna zapisywac lub z niego czytac');
		}		
	}	
	
}

?>
