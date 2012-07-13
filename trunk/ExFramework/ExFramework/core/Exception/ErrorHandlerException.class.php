<?php

/**
 * @class ErrorHandlerException
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ErrorHandlerException extends AppException {
	
	/**
	 * Kontekst bledu
	 *
	 * @var mixed
	 * 
	 */		
	protected $_context;

	/**
	 * Plik, w ktorym pojawil sie blad
	 *
	 * @var string
	 * 
	 */			
	protected $_errorFile;

	/**
	 * Linia, w ktorej pojawil sie blad
	 *
	 * @var string
	 * 
	 */			
	protected $_errorLine;
		
	/**
	 * Konstruktor
	 * 
	 * @access public
	 *
	 * @param string $message Komunikat
	 * @param int $code Kod bledu 
	 * @param string $file Plik, w ktorym doszko do bledu
	 * @param string $line Linia, w ktorej doszlo do bledu
	 * @param mixed $context Kontekst bledu
	 * @return void
	 * 
	 */		
	public function __construct($message=null, $code=0, $file=null, $line=null, $context=null) {
		parent::__construct($message, $code);
		if (!is_null($context)) {
			$this->_context = $context;
		}
		if (!is_null($file)) {
			$this->_errorFile = $file;
		}
		if (!is_null($line)) {
			$this->_errorLine = $line;
		}		
	}
	
	/**
	 * Zwraca kontekst bledu
	 * 
	 * @access public
	 * @return mixed
	 * 
	 */	
	public function getContext() {
		return $this->_context;
	}	

	/**
	 * Zwraca nazwe pliku
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getErrorFile() {
		return $this->_errorFile;
	}
	
	/**
	 * Zwraca linie bledu
	 * 
	 * @access public
	 * @return line
	 * 
	 */	
	public function getErrorLine() {
		return $this->_errorLine;
	}
	
}

?>
