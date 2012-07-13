<?php

/**
 * @class DbPdo
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class DbPdo extends DbAbstract implements IDb {
	
	/**
	 * Obiekt fabryczny wytwarzajacy obiekty przechowujace wyniki zapytan 
	 * 
	 * @var object
	 * 
	 */ 	
	protected $_dbResultFactory = null;	
	
	/**
	 * Objekt PDO
	 * 
	 * @var object
	 * 
	 */ 
	protected $_pdo = null;
	
	/**
	 * Ciag DSN
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_dsn = '';
	
	/**
	 * Nazwa uzytkownika bazy danych
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_user = '';
	
	/**
	 * Haslo uzytkownika do bazy danych
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_pass = '';
	
	/**
	 * Wlaczenie/wylaczenie polaczenia trwalego
	 * 
	 * @var bool
	 * 
	 */ 	
	protected $_persist = false;
	
	/**
	 * Tablica atrybutow
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_attributes = array();
	
	/**
	 * Tablica opcji sterownika
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_driverOptions = array();
	
	/**
	 * Wskazuje czy aktualnie trwa transakcja
	 * 
	 * @var bool
	 * 
	 */ 	
	protected $_inTransaction = false;

	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param string $dsn 
	 * @param string $user
	 * @param string $pass
	 * @param bool Wlaczenie/wylaczenie stalego polaczenia z baza danych
	 * @param array Opcje sterownika bazy danych
	 * 
	 */
	public function __construct(IFactory $dbResultFactory, $dsn, $user, $pass, $persist=false, Array $driverOptions=array()) {
		$this->_dbResultFactory = $dbResultFactory;
		$this->_dsn = $dsn;
		$this->_user = $user;
		$this->_pass = $pass;
		$this->_persist = $persist;
		$this->_attributes = array(PDO::ATTR_AUTOCOMMIT => null, 
									PDO::ATTR_CLIENT_VERSION => null, 
									PDO::ATTR_DRIVER_NAME => null, 
									PDO::ATTR_PERSISTENT => null, 
									PDO::ATTR_SERVER_VERSION => null
									);
		$this->_driverOptions = $driverOptions;
		if($this->_persist == true) {
			$this->_driverOptions[PDO::ATTR_PERSISTENT] = true;
		}		
	}
	
	/**
	 * Funkcja sprawdza, czy jest polaczenie z baza danych,
	 * posrednio poprzez sprawdzenie czy jest instancja klasy PDO
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 
	public function isConnected() {
		return ($this->_pdo instanceof PDO);
	}
	
	/**
	 * Funkcja odpowiada za nawiazywanie polaczenia z baza 
	 * i utworzenie instancji klasy PDO
	 * 
	 * @access public
	 * @return void
	 * 
	 */ 
	public function connect() {
		if($this->isConnected()) {
			return;
		}
		if(($this->_user === '') || ($this->_pass === '') || ($this->_dsn === '')) {
			throw new DbException('Nie mozna nawiazac polaczenia. Bledne dane', 'Connection failed', 0);
		}		
		try {
			$this->_pdo = new PDO($this->_dsn, $this->_user, $this->_pass, $this->_driverOptions);
			$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->_pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna nawiazac polaczenia', $pe->getMessage(), (int)$pe->getCode());
		}
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
		try {
			return $this->_pdo->beginTransaction();
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna zainicjowac transakcji', $pe->getMessage(), (int)$pe->getCode());
		}
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
		$this->_inTransaction = false;
		$this->connect(); //jesli nawet nie ma transakcji bez tego wywali sie bo nie bedzie obiektu PDO
		try {
			return $this->_pdo->commit();
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna zatwierdzic transakcji', $pe->getMessage(), (int)$pe->getCode());
		}
	}

	/**
	 * Funkcja zwraca stan SQLSTATE - kod piecioznakowy, zwiazany z ostatnia operacja
	 * 
	 * @access public
	 * @return mixed
	 * 
	 */ 	
	public function errorCode() {
		if($this->isConnected()) {
			return $this->_pdo->errorCode();
		}
		return null;
	}
	
	/**
	 * Funkcja zwraca rozszerzona informacje zwiazana z ostatnia operacja
	 * array(SQLSTATE, kod bledu specyficzny dla sterownika, informacja specyficzna dla sterownika)  
	 * 
	 * @access public
	 * @return array|null
	 * 
	 */ 	
	public function errorInfo() {
		if($this->isConnected()) {
			return $this->_pdo->errorInfo();
		}
		return null;		
	}

	/**
	 * Funkcja wykonuje zapytanie sql i zwraca liczbe wierszy jakich zapytanie dotknelo
	 * 
	 * @access public
	 * @param string Zapytanie
	 * @return int|false
	 * 
	 */ 		
	public function exec($stm) {
		$this->connect();
		try {
			return $this->_pdo->exec($stm);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna wykonac zapytania', $pe->getMessage(), (int)$pe->getCode());
		}				
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
		try {
			if(array_key_exists($attribute, $this->_attributes)) {
				return $this->_pdo->getAttribute($attribute);
			}
			else {
				return null;
			}
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna pobrac wartosci atrybutu', $pe->getMessage(), (int)$pe->getCode());
		}			
	}

	/**
	 * Funkcja zwraca tablice dostepnych sterownikow PDO
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function getAvailableDrivers() {
		$this->connect();
		try {
			return $this->_pdo->getAvailableDrivers();
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna pobrac listy dostepnych sterownikow', $pe->getMessage(), (int)$pe->getCode());
		}			
	}
	
	/**
	 * Funkcja zwraca ID ostatnio zapisanego rekordu o ile jest taka mozliwosc 
	 * lub wartosc z obiektu podanego poprzez nazwe w niektorych sterownikach
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function lastInsertId() {
		$this->connect();
		$args = func_get_args();
		if(count($args) == 0) {
			$args[0] = null;
		}
		try {
			return $this->_pdo->lastInsertId($args[0]);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna pobrac ID ostatnio wstawionego rekordu', $pe->getMessage(), (int)$pe->getCode());
		}			
	}

	/**
	 * Funkcja przygotowuje zapytanie i zwraca obiekt zapytania
	 * 
	 * @access public
	 * @param string Nieprzetworzone zapytanie
	 * @param array Opcje sterownika
	 * @return object
	 * 
	 */
	public function prepare($query, $driverOptions=array()) {
		$this->connect();
		try {
			$statement = $this->_pdo->prepare($query, $driverOptions);
			$resultObject = $this->_dbResultFactory->create('DbResult');
			$resultObject->setResultAndQuery($statement, $query);	
			return $resultObject;
			//return new DbResultPdo($statement, $query);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna przygotowac zapytania', $pe->getMessage(), (int)$pe->getCode());
		}			
	}

	/**
	 * Funkcja wykonuje zapytanie i zwraca obiekt zapytania z zestawem wyszukanych rekordow
	 * 
	 * @access public
	 * @param string Zapytanie SQL
	 * @return object
	 * 
	 */
	public function query($query) {
		$args = func_get_args();
		if(!isset($args[0])) {
			throw new DbException('Brak wyspecyfikowanego zapytania', 'No query', 1239);
		}
		if(isset($args[1]) && ($args[1] != PDO::FETCH_COLUMN && $args[1] != PDO::FETCH_CLASS && $args[1] != PDO::FETCH_INTO)) {
			throw new DbException('Nieznana lub nieuzywana stala', 'No known constant', 9869);
		}
		$this->connect();
		try {
			$statement = call_user_func_array(array($this->_pdo, 'query'), $args);
			$resultObject = $this->_dbResultFactory->create('DbResult');
			$resultObject->setResultAndQuery($statement, $query);	
			return $resultObject;
			//return new DbResultPdo($statement, $query);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna wykonac zapytania', $pe->getMessage(), (int)$pe->getCode());
		}			
	}

	/**
	 * Funkcja przyjmuje jako arg. ciag znakow i zwraca go z zabezpieczonymi znakami specjalnymi, caly string otacza apostrofami
	 * 
	 * @access public
	 * @param string Ciag znakow do zabezpieczenia
	 * @param int Typ 
	 * @return string | false
	 * 
	 */		
	public function quote($str, $parameterType=PDO::PARAM_STR) {
		$this->connect();
		try {
			return $this->_pdo->quote($str, $parameterType);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna zabezpieczyc ciagu znakow', $pe->getMessage(), (int)$pe->getCode());
		}			
	}

	/**
	 * Funkcja wycofuje wprowadzone zmiany w obrebie transakcji
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 
	public function rollBack() {
		if(!$this->_inTransaction) {
			throw new DbException('Nie mozna cofnac wprowadzonych zmian. Brak istniejacych transakcji', 'RollBack failed', '01001');
		}
		$this->_inTransaction = false;
		$this->connect(); //jesli tego nie bedzie wywali sie, poniewaz nie bedzie obiektu PDO
		try {
			$this->_pdo->rollBack();
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna cofnac wprowadzonych zmian', $pe->getMessage(), (int)$pe->getCode());
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
		try {
			if(array_key_exists($attribute, $this->_attributes)) {
				return $this->_pdo->setAttribute($attribute, $value);
			}
			else {
				return false;
			}
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna ustawic wartosci atrybutu', $pe->getMessage(), (int)$pe->getCode());
		}			
	}
	
}

?>
