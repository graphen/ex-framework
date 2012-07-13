<?php

/**
 * @class ExceptionHandler
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ExceptionHandler {
	
	/**
	 * Przelacznik wyswietlania wszystkie informacji o wyjatku
	 *
	 * @var bool
	 * 
	 */			
	protected $_printAll = true;

	/**
	 * Przelacznik wyswietlania ogolnej informacji o zaistnialym bledzie 
	 *
	 * @var bool
	 * 
	 */			
	protected $_printInfo = false;
	
	/**
	 * Tekst wyswietlanej informacji o bledzie
	 *
	 * @var string
	 * 
	 */			
	protected $_infoString = "Internal Error!!!";	
	
	/**
	 * Konstruktor 
	 * 
	 * @access public
	 * @param bool Czy wyswietlac wszystkie informacje o zaistnialym bledzie/wyjatku
	 * @param bool Czy wyswietlac informacje ogolna
	 * @param string Tresc komunikatu bledu
	 * @param
	 * 
	 */			
	public function __construct($printAll=true, $printInfo=false, $infoString="") {
		$this->setPrint($printAll, $printInfo);
		if(!empty($infoString)) {
			$this->setInfoString($infoString);
		}
		set_exception_handler(array($this, "handleExceptions"));
	}
	
	/**
	 * Wlacza/wylacza wyswietlanie informacji
	 * 
	 * @access public
	 * @param bool $printAll Czy wyswietlac wszystkie informacje o bledzie
	 * @param bool $printInfo Czy wyswietlac tylko ogolna informacje
	 * @return void
	 * 
	 */		
	public function setPrint($printAll=true, $printInfo=false) {
		if($printInfo == true) {
			$printAll = false;
		}
		if($printAll == false) {
			$printInfo = true;
		} 
		$this->_printAll = (bool)$printAll;
		$this->_printInfo = (bool)$printInfo;
	}
	
	/**
	 * Ustawia komunikat bledu
	 * 
	 * @access public
	 * @param string Komunikat bledu
	 * @return void
	 * 
	 */		
	public function setInfoString($infoString="") {
		$this->_infoString = $infoString;
	}	
	
	/**
	 * Wyswietla sformatowana informacje na temat rzuconego wyjatku
	 * 
	 * @access public
	 * @param Exception $e 
	 * @return void
	 * 
	 */		
	public function handleExceptions($e) {
		$code = $e->getCode();
		$message = $e->getMessage();
		if(($code == E_RECOVERABLE_ERROR) && (strstr($message, 'Catchable'))) {
			$type = "Catchable Error";
			$color = "grey";
		}		
		elseif((($code == E_USER_ERROR) || ($code == E_ERROR) || ($code == E_COMPILE_ERROR) || ($code == E_PARSE)) && (strstr($message, 'Error'))) {
			$type = "Error";
			$color = "red";
		}
		elseif((($code == E_USER_WARNING) || ($code == E_WARNING) || ($code == E_COMPILE_WARNING)) && (strstr($message, 'Warning'))) {
			$type = "Warning";
			$color = "orange";
		}		
		elseif((($code == E_USER_NOTICE) || ($code == E_NOTICE) || ($code == E_STRICT)) && (strstr($message, 'Notice'))) {
			$type = "Notice";
			$color = "yellow";
		}		
		else {
			$type = "Exception";
			$color = "grey";			
		}		
		
		if($this->_printAll == true) {
			echo "<html><head></head><body><br /><br /><br /><br />\n";
			echo "<center><table border=\"1\" width=\"600\">\n";
			echo "<tr><td width=\"600\" style=\"background: $color;\">\n<b>Code: " . $e->getCode() . "</b></td></tr>\n";
			echo "<tr><td width=\"600\" style=\"background: $color;\"><b>$type:</b> " . $e->getMessage() . "</td></tr>\n";
			echo "<tr><td width=\"600\" style=\"background: $color;\"><b>Exception in file:</b> " . $e->getFile() . "</td></tr>\n";
			echo "<tr><td width=\"600\" style=\"background: $color;\"><b>Exception on line:</b> " . $e->getLine() . "</td></tr>\n";
			if ($e instanceof ErrorHandlerException) {
				echo "<tr><td width=\"600\" style=\"background: $color;\"><b>Error in file:</b> " . $e->getErrorFile() . "</td></tr>\n";
				echo "<tr><td width=\"600\" style=\"background: $color;\"><b>Error on line:</b> " . $e->getErrorLine() . "</td></tr>\n";
				echo "<tr><td width=\"600\" style=\"background: $color;\"><b>Context:</b> <pre>" . print_r($e->getContext(),true) . "</pre></td></tr>\n";
				//echo "Context: " . $context["_SERVER"]["REMOTE_ADDR"] . "<br />\n";
			}
			if (!is_null($trace = $e->getTraceAsString())) {
				echo "<tr><td width=\"600\" style=\"background: $color;\"><b>Trace:</b><pre> " . $trace . "</pre>\n";
				echo "</td></tr>\n";
			}
			echo "</table></center>\n";
			echo "</body></html>\n";
		}
		if($this->_printInfo == true) {
			echo "<html><head></head><body><br /><br /><br /><br /><br /><br /><br /><br />\n";
			echo "<center><table border=\"1\" width=\"600\">\n";
			echo "<tr><td width=\"600\" align=\"center\" style=\"background: grey;\"><b>\n";
			echo $this->_infoString;
			echo "</b></td></tr>\n";
			echo "</table></center>\n";
			echo "</body></html>\n";			
		}
	}
	
}
