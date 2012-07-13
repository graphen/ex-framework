<?php

/**
 * @class DbMysql
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class DbMysql extends DbAbstract implements IDb {

	/**
	 * Obiekt fabryczny wytwarzajacy obiekty przechowujace wyniki zapytan 
	 * 
	 * @var object
	 * 
	 */ 	
	protected $_dbResultFactory = null;
	
	/**
	 * Identyfikator polaczenia
	 * 
	 * @var resource
	 * 
	 */ 	
	protected $_connectionId = null;
	
	/**
	 * Adres serwera
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_host = '';
	
	/**
	 * Nazwa uzytkownika bazy danych
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_user = '';
	
	/**
	 * Haslo do bazy danych
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_pass = '';
	
	/**
	 * Nazwa bazy danych
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_dbName = '';
	
	/**
	 * Wlaczenie lub wylaczenie stalego polaczenia z baza danych
	 * 
	 * @var bool
	 * 
	 */ 	
	protected $_persist = false;
	
	/**
	 * Prefix dla tabel w bazie
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_prefix = '';
	
	/**
	 * Opcje sterownika
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_driverOptions = array();
	
	/**
	 * Atrybuty polaczenia
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_attributes = array();
	
	/**
	 * Wskazuje czy aktualnie wlaczona jets transakcja
	 * 
	 * @var bool
	 * 
	 */ 	
	protected $_inTransaction = false;
	
	/**
	 * Wskazuje czy juz nie ma nie pobranych jeszcze rekordow w zbiorze wynikow
	 * 
	 * @var bool
	 * 
	 */ 
	protected $_cursorClosed = true;
	
	/**
	 * Obiekt reprezentujacy zbior wynikow
	 * 
	 * @var object
	 * 
	 */ 	
	protected $_resultObject = null;	
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Fabryka obiektow przechowujacych wyniki zapytan
	 * @param string Nazwa hosta
	 * @param string Nazwa uzytkownika bazy danych
	 * @param string Haslo uzytkownika bazy danych
	 * @param string Nazwa abeli w bazie danych
	 * @param bool Wlaczenie/wylaczenie stalego polaczenia z baza danych
	 * @param string Prefix dla tabel w bazie danych
	 * @param array Opcje dla sterownika
	 * 
	 */	
	public function __construct(IFactory $dbResultFactory, $host, $user, $pass, $dbName, $persist=false, $prefix=null, Array $driverOptions=array()) {
		$this->_dbResultFactory = $dbResultFactory;
		$this->_host = $host;
		$this->_user = $user;
		$this->_pass = $pass;
		$this->_dbName = $dbName;
		$this->_persist = $persist;
		$this->_prefix = $prefix;
		$this->_driverOptions = $driverOptions;
		
		$this->_attributes = array(	DbAbstract::ATTR_AUTOCOMMIT => true, //teraz nie wykorzystywane
									DbAbstract::ATTR_DRIVER_NAME => "mysql", 
									DbAbstract::ATTR_PERSISTENT => $this->_persist
									);
	}

	/**
	 * Destruktor
	 * Zamyka polaczenie z baza danych
	 * 
	 * @access public
	 * 
	 */ 
	public function __destruct() {
		$this->close();
	}

	/**
	 * Funkcja zwraca ciag informacyjny o bledzie ostatniej operacji
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 
	public function getLastError() {
		return @mysql_error($this->_connectionId);
	}
	
	/**
	 * Funkcja zwraca kod bledu ostatniej operacji
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getLastErrorNumber() {
		return @mysql_errno($this->_connectionId);
	}

	/**
	 * Funkcja zamyka polaczenie z baza danych
	 * 
	 * @access protected
	 * @return void
	 * 
	 */
	protected function close() {
		if($this->_persist !== null) {
			return;
		}
		if($this->_connectionId !== null) {
			if (!@mysql_close($this->_connectionId)) {
				throw new DbException('Nie mozna zamknac polaczenia z baza danych', $this->getLastError(), $this->getLastErrorNumber());				
			}
		}
	}

	/**
	 * Funkcja sprawdza, czy jest polaczenie z baza danych
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 
	public function isConnected() {
		return (is_resource($this->_connectionId)) ? true : false;
	}
	
	/**
	 * Funkcja odpowiada za nawiazywanie polaczenia z baza danych
	 * 
	 * @access public
	 * @return void
	 * 
	 */ 	
	public function connect() {
		if($this->isConnected() === true) {
			return;
		}
		if(($this->_user === '') || ($this->_pass === '') || ($this->_host === '') || ($this->_dbName === '')){
			throw new DbException('Nie mozna nawiazac polaczenia. Bledne dane', 'Connection failed', 0);
		}
		if ($this->_persist == true) {
			$this->_connectionId = @mysql_pconnect($this->_host, $this->_user, $this->_pass);
		}
		else {
			$this->_connectionId = @mysql_connect($this->_host, $this->_user, $this->_pass);
			if (!$this->_connectionId) {
				$attemptSum = 0;
				while ((!$this->_connectionId) && ($attemptSum <= 5)) {
					$this->_connectionId = @mysql_connect($this->_host, $this->_user, $this->_pass);
					sleep(5);
					$attemptSum++;
				}
			}
		}
		if (!$this->_connectionId) {
			throw new DbException('Nie mozna nawiazac polaczenia', 'Connection failed', 0);
		}
		else {
			$dbSelected = @mysql_select_db($this->_dbName, $this->_connectionId);
			if (!$dbSelected) {
				throw new DbException('Nie mozna wybrac bazy danych', $this->getLastError(), $this->getLastErrorNumber());
			}
		}
		$this->_attributes[DbAbstract::ATTR_CLIENT_VERSION] = mysql_get_client_info();
		$this->_attributes[DbAbstract::ATTR_SERVER_VERSION] = mysql_get_server_info();
	}

	/**
	 * Funkcja wylacza tryb autocommit, inicjuje transakcje
	 * 
	 * @access public
	 * @return bool 
	 * 
	 */ 
	public function beginTransaction() {
		$this->connect();
		$this->_inTransaction = true;
		if(!$ret = @mysql_query('BEGIN', $this->_connectionId)) {
			throw new DbException('Nie mozna zainicjowac transakcji', $this->getLastError(), $this->getLastErrorNumber());			
		}
		return (bool) $ret;
	}

	/**
	 * Funkcja wlacza tryb autocommit, konczy transakcje zatwierdzajac zmiany
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 
	public function commit() {
		if(!$this->_inTransaction) {
			throw new DbException('Nie mozna zatwierdzic transakcji. Transakcja nie istnieje', 'Commit failed', '01000');
		}
		$this->_transaction = false;
		$this->connect();
		if(!$ret = @mysql_query('COMMIT', $this->_connectionId)) {
			throw new DbException('Nie mozna zatwierdzic transakcji', $this->getLastError(), $this->getLastErrorNumber());				
		}
		return (bool) $ret;
	}
	
	/**
	 * Funkcja wystepuje tylko dla kompatybilnosci
	 * 
	 * @access public
	 * @return mixed
	 * 
	 */ 	
	public function errorCode() {
		return null;
	}	
	
	/**
	 * Funkcja zwraca rozszerzona informacje zwiazana z ostatnia operacja
	 * array(pusty ciag - dla kompatybilnosci, kod bledu specyficzny dla sterownika, informacja specyficzna dla sterownika)  
	 * 
	 * @access public
	 * @return array|null
	 * 
	 */ 	
	public function errorInfo() {
		if($this->isConnected()) {
			$errorInfo = array();
			$errorInfo[0] = null;
			$errorInfo[1] = $this->getLastErrorNumber();
			$errorInfo[2] = $this->getLastError();
			return $errorInfo;
		}
		return null;
	}	

	/**
	 * Funkcja wykonuje zapytanie sql i zwraca liczbe wierszy ktorych zapytanie dotknelo
	 * 
	 * @access public
	 * @param string Zapytanie SQL
	 * @return int
	 * 
	 */ 		
	public function exec($stm) {
		$this->connect();
		if(!strstr($stm, 'DELETE') && !strstr($stm, 'UPDATE') && !strstr($stm, 'INSERT') && !strstr($stm, 'SET')) {
			throw new DbException('Nie mozna uzywac tej metody do zapytan SELECT, SHOW, DESCRIBE, EXPLAIN i innych zwracajacych zbior wynikow zapytania', 'Cannot use for SELECT, DESCRIBE, EXPLAIN etc.', '0098');			
		}
		$result = mysql_query($stm, $this->_connectionId);
		if ($result === false) {
			throw new DbException('Nie mozna wykonac zapytania', $this->getLastError(), $this->getLastErrorNumber());
		}		
		if(is_resource($result)) {
			mysql_free_result($result);
			throw new DbException('Nie mozna uzywac tej metody do zapytan SELECT, SHOW, DESCRIBE, EXPLAIN i innych zwracajacych zbior wynikow zapytania', 'Cannot use for SELECT, DESCRIBE, EXPLAIN etc.', '0098');
		}
		$affectedRows = mysql_affected_rows($this->_connectionId);
		if($affectedRows === -1) {
			throw new DbException('Nie mozna zwrocic ilosci wierszy, ktorych dotyczylo zapytanie', $this->getLastError(), $this->getLastErrorNumber());
		}			
		return $affectedRows;
	}

	/**
	 * Funkcja zwraca wartosc atrybutu
	 * 
	 * @access public
	 * @param int Atrybut podany poprzez stala
	 * @return mixed
	 * 
	 */
	public function getAttribute($attribute) {
		$this->connect();
		if(isset($this->_attributes[$attribute])) {
			return $this->_attributes[$attribute];
		}
		else {
			return null;
		}
	}
	
	/**
	 * Funkcja zwraca tablice dostepnych sterownikow
	 * 
	 * @access public
	 * @return array
	 * @todo sprawdzic jakie sterowniki sa dostepne w bierzacym katalogu
	 */		
	public function getAvailableDrivers() {
		$drivers = array();
		$drivers[0] = 'mysql';
		return $drivers;
	}	
	
	/**
	 * Funkcja zwraca ID ostatnio zapisanego rekordu
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function lastInsertId() {
		$this->connect();
		if(!$lastInsertId = mysql_insert_id($this->_connectionId)) {
			throw new DbException('Nie mozna pobrac ID ostatnio wstawionego rekordu', $this->getLastError(), $this->getLastErrorNumber());			
		}
		return (string)$lastInsertId;		
	}	

	/**
	 * Funkcja przygotowuje zapytanie i zwraca obiekt zapytania
	 * 
	 * @access public
	 * @param string Zapytanie SQL
	 * @return object
	 * 
	 */
	public function prepare($query, $driverOptions=array()) {
		$this->connect();
		$this->_driverOptions = array_merge($this->_driverOptions, $driverOptions);
		//$resultObject = new DbMysqlResult($query, null, $this);
		$resultObject = $this->_dbResultFactory->create('DbResult');
		$resultObject->setResultAndQuery(null, $query);
		$resultObject->setDbObject($this);		
		$this->_resultObject = $resultObject;
		return $resultObject;		
	}

	/**
	 * Funkcja wykonuje zapytanie i zwraca obiekt zapytania
	 * 
	 * @access public
	 * @param string Zapytanie SQL
	 * @return object
	 * 
	 */	
	public function query($query) {
		if(strstr($query, 'DELETE') || strstr($query, 'UPDATE') || strstr($query, 'INSERT')) {
			throw new DbException('Nie mozna uzywac tej metody do zapytan INSERT, UPDATE, DELETE i innych nie zwracajacych zbioru wynikow zapytania', 'Cannot use for SELECT, DESCRIBE, EXPLAIN etc.', '0098');			
		}
		$this->connect();
		if($this->_cursorClosed == false) {
			if(is_object($this->_resultObject) && $this->_resultObject instanceof DbResultMysql) {
				$this->_resultObject->closeCursor();
			}
			$this->_cursorClosed = true;
		}		
		$result = @mysql_query($query, $this->_connectionId);
		if ($result === false) {
			throw new DbException('Nie mozna wykonac zapytania', $this->getLastError(), $this->getLastErrorNumber());
		}
		$this->_cursorClosed = false;
		//$resultObject = new DbResultMysql($query, $result, $this);
		$resultObject = $this->_dbResultFactory->create('DbResult');
		$resultObject->setResultAndQuery($result, $query);
		$resultObject->setDbObject($this);
		$this->_resultObject = $resultObject;
		return $resultObject;
	}	
	
	/**
	 * Funkcja przyjmuje jako arg. ciag znakow i zwraca go z zabezpieczonymi znakami specjalnymi, caly string otacza apostrofami
	 * 
	 * @access public
	 * @param string Ciag znakow do zabezpieczenia
	 * @param int Typ
	 * @return string
	 * 
	 */		
	public function quote($str, $parameterType=DbAbstract::PARAM_STR) {
		$this->connect();
		if($parameterType == DbAbstract::PARAM_INT) {
			return (int) $str;
		}		
		elseif($parameterType == DbAbstract::PARAM_BOOL) {
			return (bool) $str;
		}
		elseif($parameterType == DbAbstract::PARAM_LOB) {
			return $str;
		}
		else {
			if(function_exists('mysql_real_escape_string')) {
				if(!$quotedString = @mysql_real_escape_string($str, $this->_connectionId)) {
					throw new DbException('Nie mozna zabezpieczyc ciagu znakow', $this->getLastError(), $this->getLastErrorNumber());			
				}
			}
			elseif(function_exists('mysql_escape_string')) {
				if(!$quotedString = @mysql_escape_string($str)) {
					throw new DbException('Nie mozna zabezpieczyc ciagu znakow', $this->getLastError(), $this->getLastErrorNumber());						
				}
			}
			else {
				$quotedString = $str;
			}
			return "'".$quotedString."'";
		}
	}
	
	/**
	 * Funkcja wycofuje wprowadzone zmiany w obrebie transakcji, nie dotyczy operacji na strukturze bazy danych w bazie MySQL 
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 	
	public function rollBack() {
		if(!$this->_inTransaction) {
			throw new DbException('Nie mozna cofnac wprowadzonych zmian. Brak istniejacych transakcji', 'RollBack failed', '010010');
		}
		$this->_transaction = false;
		$this->connect();
		if(!$ret = @mysql_query('ROLLBACK', $this->_connectionId)) {
			throw new DbException('Nie mozna cofnac wprowadzonych zmian', $this->getLastError(), $this->getLastErrorNumber());			
		}
	}

	/**
	 * Funkcja ustawia wartosc atrybutu
	 * 
	 * @access public
	 * @param int Atrybut podany poprzez stala
	 * @param mixed Wartosc atrybutu
	 * @return bool
	 * 
	 */
	public function setAttribute($attribute, $value) {
		$this->connect();
		if(array_key_exists($attribute, $this->_attributes)) {
			$this->_attributes[$attribute] = $value;
			return true;
		}
		else {
			return false;
		}		
	}
	
	/**
	 * Funkcja ustawia istnienie kursora
	 * 
	 * @access public
	 * @param bool Czy kursor jest obecny
	 * @return void
	 * 
	 */	
	public function setCursorClosed($var) {
		$this->_cursorClosed = $var;
	}
	
	/**
	 * Funkcja sprawdza istnienie kursora
	 * 
	 * @access public
	 * @return bool
	 * 
	 */		
	public function getCursorClosed() {
		return $this->_cursorClosed;
	}

	/**
	 * Zwraca id polaczenia
	 * 
	 * @access public
	 * @return resource
	 * 
	 */		
	public function getConnectionId() {
		return $this->_connectionId;
	}	
	
}

?>
