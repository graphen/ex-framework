<?php

/**
 * @class SessionHandlerDb
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 * 	Example Table definition:
 * 
 *	CREATE TABLE `sessions` ( 
 *	  `session_id` varchar(255) binary NOT NULL default '', 
 *	  `session_updated` int(10) unsigned NOT NULL default '0', 
 *	  `session_data` text, 
 *	  PRIMARY KEY  (`session_id`) 
 *	) TYPE=InnoDB; 
 * 
 * 
 */
class SessionHandlerDb implements ISessionHandler {
	
	/**
	 * Obiekt zarzadzajacy baza danych
	 * 
	 * @var object
	 * 
	 */
	protected $_db = null;
	
	/**
	 * Nazwa tabeli w ktorej przecowywane sa sesje uzytkownikow
	 * 
	 * @var string
	 * 
	 */	
	protected $_sessionTable = null;
	
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
	 * @param object Obiekt bazy danych 
	 * @param string Nazwa tabeli sesji
	 * @param int Maksymalny czas zycia sesji
	 * 
	 */	
	public function __construct(IDb $db, $sessionTable='sessions', $sessionMaxLifeTime=1440) {
		$this->_db = $db;
		$this->_sessionTable = $sessionTable;
		$this->_sessionMaxLifeTime = $sessionMaxLifeTime;
		//register_shutdown_function('session_write_close');
	}

	/**
	 * Destruktor
	 * 
	 * @access public
	 * 
	 */		
	public function __destruct() {
		session_write_close(); //Dane sesji musza zostac zapisane zanim jeszcze zniszczone zostana odpowiedzialne za to obiekty
	}
	
	/**
	 * Wywolywana podczas otwarcia sesji
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @param string Nazwa sesji
	 * @return bool
	 * 
	 */ 	
	public function open($sessionSavePath, $sessionName) {
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
		$query = "SELECT `session_data` FROM `" . $this->_sessionTable . "` WHERE `session_id`=:sessionId";
		$result = $this->_db->prepare($query);
		
		$result->execute(array(':sessionId'=>$sessionId));
		$data = $result->fetchAll();
		if(is_array($data) && isset($data[0]['session_data'])) {
			return (string)$data[0]['session_data'];
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
		$query = "SELECT * FROM `" . $this->_sessionTable . "` WHERE `session_id`=:sessionId";
		$result = $this->_db->prepare($query);
		$result->execute(array(':sessionId'=>$sessionId));
		$data = $result->fetchAll();
		if(is_array($data) && (count($data) > 0)) {
			$query = "UPDATE `" . $this->_sessionTable . "` SET `session_updated`=:sessionUpdated, `session_data`=:sessionData WHERE `session_id`=:sessionId"; 
		}	
		else {
			$query = "INSERT INTO `" . $this->_sessionTable . "` (`session_id`, `session_updated`, `session_data`) VALUES(:sessionId, :sessionUpdated, :sessionData)";
		}
		$result = $this->_db->prepare($query);
		return $result->execute(array(':sessionId'=>$sessionId, ':sessionUpdated'=>time(), ':sessionData'=>$sessionData));
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
		$query = "DELETE FROM `" . $this->_sessionTable . "` WHERE `session_id`=:sessionId";
		$result = $this->_db->prepare($query);
		return $result->execute(array(':sessionId'=>$sessionId));		
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
		$query = "DELETE FROM `". $this->_sessionTable . "` WHERE `session_updated` < :timestamp";  
		$result = $this->_db->prepare($query);
		$timestamp = time() - $maxLifeTime;
		return $result->execute(array(':timestamp'=>$timestamp));		
	}
	
}

?>
