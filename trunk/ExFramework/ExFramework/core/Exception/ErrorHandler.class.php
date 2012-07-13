<?php

/**
 * @class ErrorHandler
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ErrorHandler {
	
	/**
	 * Przelacznik wyrzucania wyjatkow po napotkaniu informacji
	 *
	 * @var bool
	 * 
	 */		
	protected $_noticeThrowException = false;
	
	/**
	 * Przelacznik wyrzucania wyjatkow po napotkaniu ostrzezenia
	 *
	 * @var bool
	 * 
	 */		
	protected $_warningThrowException = false;
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 *
	 */		
	public function __construct($noticeThrowException=false, $warningThrowException=false) {
		$this->_noticeThrowException = (bool)$noticeThrowException;
		$this->_warningThrowException = (bool)$warningThrowException;
		set_error_handler(array($this, "handleErrors")); //Ustawia automatyczna metode zglaszajaca wyjatek po napotkaniu bledu PHP
	}

	/**
	 * Rzuca wyjatek z parametrami bledu
	 * 
	 * @access public
	 * @param int $code Kod bledu
	 * @param string $message Komunikat
	 * @param string $file Plik, w ktorym doszko do bledu
	 * @param string $line Linia, w ktorej doszlo do bledu
	 * @param mixed $context Kontekst bledu
	 * @return void
	 * 
	 */	
	public function handleErrors($code, $message, $file, $line, $context) {
		if(($this->_noticeThrowException == false) && (($code == E_USER_NOTICE) || ($code == E_NOTICE) || ($code == E_STRICT))) {
			return;
		}
		if(($this->_warningThrowException == false) && (($code == E_USER_WARNING) || ($code == E_WARNING))) {
			return;
		}			
		if(!empty($code)) {
			switch($code) {
				case E_USER_ERROR:
					$type = 'User Error';
					break;				
				case E_ERROR:
				case E_COMPILE_ERROR:
				case E_PARSE:
					$type = 'Error';
					break;
				case E_USER_WARNING:
					$type = 'User Warning';
					break;				
				case E_WARNING:
				case E_COMPILE_WARNING:
					$type = 'Warning';
					break;
				case E_USER_NOTICE:
					$type = 'User Notice';
					break;				
				case E_NOTICE:
				case @E_STRICT:
					$type = 'Notice';
					break;
				case @E_RECOVERABLE_ERROR:
					$type = 'Catchable Error';
					break;
				default:
					$type = 'Unknown Error';
					break;
			}		
			$message = $type . ": " . $message;
			throw new ErrorHandlerException($message, $code, $file, $line, $context);
		}
		else {
			if(empty($message)) {
				$message = "Unknown PHP error";
			}
			throw new ErrorHandlerException($message, $code, $file, $line, $context);
		}	
	}
	
}

?>
