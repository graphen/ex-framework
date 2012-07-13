<?php

/**
 * @class AppException
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class AppException extends Exception {
	
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
	protected $_printInfo = true;
	
	/**
	 * Tekst wyswietlanej informacji o bledzie
	 *
	 * @var string
	 * 
	 */	
	protected $_infoString = "Przepraszamy! Wystąpił błąd. Odśwież stronę lub wróć poźniej.";
	
	/**
	 * Konstruktor 
	 * 
	 * @access public
	 * @param string Tresc komunikatu bledu
	 * @param int Kod bledu
	 * @param object Obiekt wyjatku
	 * @param
	 * 
	 */		
	public function __construct($message, $code=0, Exception $e=null) {
		parent::__construct($message, $code, $e);
	}
	
	/**
	 * Wyswietla sformatowana informacje na temat rzuconego wyjatku
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function printAll() {
		echo "<html>";
		echo "<head>";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
		echo "</head>";
		echo "<body style=\"background: #666\">";
		echo "<br /><br /><br /><br />\n";
		echo "<center>";
		echo "<table border=\"1\" cellpading=\"0\" cellspacing=\"0\" width=\"600\">\n";
		if(($this->_printAll == true)) {
			echo "<tr><td width=\"600\" style=\"background: red;\">" . parent::getMessage() . "</td></tr>\n";
			echo "<tr><td width=\"600\" style=\"background: red;\">" . parent::getCode() . "</td></tr>\n";
			if (!is_null($trace = parent::getTraceAsString())) {
				echo "<tr><td width=\"600\" style=\"background: red;\"><b>Trace:</b><pre> " . $trace . "</pre>\n";
				echo "</td></tr>\n";
			}

		}
		if(($this->_printInfo == true) && ($this->_printAll == false)) {
			echo "<tr><td width=\"600\" align=\"center\" style=\"background: grey; \">\n";
			echo "<b>". $this->_infoString . "</b>";
			echo "</td></tr>\n";
		}	
		echo "</table>\n";
		echo "</body></html>\n";
	}
	
}

?>
