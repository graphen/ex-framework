<?php

/**
 * @class SessionHandlerFiles
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class SessionHandlerFiles implements ISessionHandler {
	
	/**
	 * Sciezka do katalogu sesji
	 * 
	 * @var string
	 * 
	 */
	protected $_sessionSavePath = null;
	
	/**
	 * Nazwa sesji
	 * 
	 * @var string
	 * 
	 */	
	protected $_sessionName = null;
	
	/**
	 * Maksymalny czas zycia sesji
	 * 
	 * @var int
	 * 
	 */	
	protected $_sessionMaxLifeTime = null;
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param string Sciezka do katalogu sesji
	 * @param int Maksymalny czas zycia sesji
	 * 
	 */	
	public function __construct($sessionSavePath=null, $sessionMaxLifeTime=1440) {
		if($sessionSavePath !== null) {
			$sessionSavePath = rtrim($sessionSavePath, '/') . '/';
			session_save_path($sessionSavePath);
			$this->_sessionSavePath = $sessionSavePath;
		}
		$this->_sessionMaxLifeTime = $sessionMaxLifeTime;
	}
	
	/**
	 * Destruktor
	 * 
	 * @access public
	 * 
	 */		
	public function __destruct() {
		session_write_close(); //Dane sesji musza zostac zapisane zanim jeszcze zniszczone zostana odpowiedxzialne za to obiekty
	}	
	
	/**
	 * Wywolywana podczas otwarcia sesji
	 * 
	 * @access public
	 * @param string Sciezka do katalogu sesji
	 * @param string Nazwa sesji
	 * @return bool
	 * 
	 */ 	
	public function open($sessionSavePath, $sessionName) {
		$this->_sessionSavePath = $sessionSavePath;
		$this->_sessionName = $sessionName;
		return true;
	}
	
	/**
	 * Wywolywana podczas zamkniecia sesji
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 	
	public function close() {
		return $this->gc($this->_sessionMaxLifeTime);
	}
	
	/**
	 * Odczytuje zmienne sesji
	 * 
	 * @access public
	 * @param string Identyfikator sesji
	 * @return string
	 * 
	 */ 	
	public function read($sessionId) {
		$sessionSavePath = rtrim($this->_sessionSavePath, '/');
		$file = $sessionSavePath . '/sess_' . $sessionId;
		if(file_exists($file)) {
			return (string) @file_get_contents($file);
		}
		else {
			return '';
		}
	}
	
	/**
	 * Zapisuje dane sesji
	 * 
	 * @access public
	 * @param string Identyfikator sesji
	 * @param string Dane
	 * @return bool
	 * 
	 */ 	
	public function write($sessionId, $sessionData) {
		$sessionSavePath = rtrim($this->_sessionSavePath, '/');
		$file = $sessionSavePath . '/sess_' . $sessionId;
		if(@file_put_contents($file, $sessionData)) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * Niszczy sesje o okreslonym identyfikatorze
	 * 
	 * @access public
	 * @param string Identyfikator sesji
	 * @return bool
	 * 
	 */ 	
	public function destroy($sessionId) {
		$sessionSavePath = rtrim($this->_sessionSavePath, '/');
		$file = $sessionSavePath . '/sess_' . $sessionId;
		return (@unlink($file));
	}
	
	/**
	 * Garbage collector
	 * 
	 * @access public
	 * @param int Maksymalny czas zycia sesji
	 * @return bool
     * @see session.gc_divisor      100
     * @see session.gc_maxlifetime 1440
     * @see session.gc_probability    1
     * @usage execution rate 1/100
     *        (session.gc_probability/session.gc_divisor)
	 * 
	 */ 	
	public function gc($maxLifeTime) {
		$sessionSavePath = rtrim($this->_sessionSavePath, '/');
		$files = glob($sessionSavePath . '/sess_*');
		$ret = true;
		foreach($files AS $fileName) {
			if(filemtime($fileName) + $maxLifeTime < time()) {
				if(!@unlink($fileName)) {
					$ret = false;
				}
			}
		}
		return $ret;
	}
	
}

?>
