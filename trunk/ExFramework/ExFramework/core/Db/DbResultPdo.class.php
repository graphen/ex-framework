<?php

/**
 * @class DbResultPdo
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class DbResultPdo extends DbResultAbstract implements IDbResult {
	
	/**
	 * Obiekt PDOStatement zarzadzajacy wynikami
	 * 
	 * @var object
	 * 
	 */	
	protected $_pdoStm = null;
	
	/**
	 * Zapytanie przetworzone lub z query
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_query = null;


	/**
	 * Konstruktor
	 * 
	 * @access public
	 * 
	 */	
	public function __construct() {
		//
	}
	
	/**
	 * Ustawia obiekt obslugi zapytania i zapytanie
	 * 
	 * @access public
	 * @param object Objekt zapytania
	 * @param string Zapytanie SQL	
	 * @return void
	 * 
	 */
	public function setResultAndQuery(PDOStatement $statement, $query) {
		$this->_pdoStm = $statement;
		$this->_query = $query;		
	}
	
	
	/**
	 * Funkcja laczy kolume ze zmienna PHP przekazana przez referencje
	 * 
	 * @access public
	 * @param mixed Nazwa kolumny lub jej numer
	 * @param mixed Nazwa parametru, z ktorym zostanie zwiazana kolumna
	 * @param int Typ identyfikowany poprzez stala
	 * @return bool
	 * 
	 */	
	public function bindColumn($column, &$param, $type=PDO::PARAM_STR) {
		try {
			return $this->_pdoStm->bindColumn($column, $param, $type);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna powiazac kolumny z parametrem', $pe->getMessage(), (int)$pe->getCode());
		}			
	}
	
	/**
	 * Funkcja laczy parametr z nazwa zmiennej PHP przekazana przez referencje
	 * 
	 * @access public
	 * @param mixed Nazwa lub numer parametru
	 * @param mixed Nazwa zmiennej podana przez referencje
	 * @param int Typ
	 * @param int Dlugosc
	 * @return bool
	 * 
	 */		
	public function bindParam($parameter, &$variable, $type=PDO::PARAM_STR, $length=null) {
		try {
			$this->_pdoStm->bindParam($parameter, $variable, $type, $length);
			return true;
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna powiazac zmiennej z parametrem', $pe->getMessage(), (int)$pe->getCode());
		}		
	}

	/**
	 * Funkcja laczy parametr z zmienna PHP
	 * 
	 * @access public
	 * @param mixed Nazwa lub numer parametru
	 * @param mixed Wartosc dla parametru
	 * @param int Typ
	 * @return bool
	 * 
	 */		
	public function bindValue($parameter, $variable, $type=PDO::PARAM_STR) {
		try {
			return $this->_pdoStm->bindValue($parameter, $variable, $type);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna powiazac wartosci z parametrem', $pe->getMessage(), (int)$pe->getCode());
		}		
	}

	/**
	 * Funkcja zwalnia polaczenie z baza danych, nie rozlacza tylko umozliwia wykonaniue kolejnego zapytania
	 * 
	 * @access public
	 * @return bool
	 * 
	 */			
	public function closeCursor() {
		try {
			return $this->_pdoStm->closeCursor();
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna zwolnic zasobow zajetych przez wyniki poprzedniego zapytania', $pe->getMessage(), (int)$pe->getCode());
		}			
	}

	/**
	 * Funkcja zwraca liczbe kolumn w zbiorze wynikow
	 * 
	 * @access public
	 * @return int
	 * 
	 */			
	public function columnCount() {
		try {
			return $this->_pdoStm->columnCount();
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna pobrac liczby kolumn', $pe->getMessage(), (int)$pe->getCode());
		}			
	}

	/**
	 * Funkcja wyswietla przygotowane zlozone zapytanie
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function debugDumpParams() {
		try {
			return $this->_pdoStm->debugDumpParams();
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna wyswietlic danych', $pe->getMessage(), (int)$pe->getCode());
		}		
	}
	
	/**
	 * Funkcja zwraca kod bledu ostatniej operacji
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function errorCode() {
		return $this->_pdoStm->errorCode();
	}
	
	/**
	 * Funkcja zwraca rozszerzona informacje zwiazana z ostatnia operacja w postaci tablicy
	 * array(SQLSTATE, kod bledu specyficzny dla sterownika, informacja specyficzna dla sterownika)  
	 * 
	 * @access public
	 * @return array
	 * 
	 */ 	
	public function errorInfo() {
		return $this->_pdoStm->errorInfo();
	}

	/**
	 * Funkcja wykonuje przygotowane wczesniej zapytanie sql
	 * 
	 * @access public
	 * @param array Tablica polaczen parametrow z wartosciami
	 * @return bool
	 * 
	 */
	public function execute($inputParameters=array()) {
		try {
			$args = func_get_args();
			return call_user_func_array(array($this->_pdoStm, 'execute'), $args);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna wykonac zapytania', $pe->getMessage(), (int)$pe->getCode());
		}		
	}

	/**
	 * Funkcja pobiera wiersz danych ze zbioru wynikow
	 * 
	 * @access public
	 * @param int Styl zwracania wynikow
	 * @return mixed
	 *
	 */
	public function fetch($fetchStyle=PDO::FETCH_BOTH) {
		$args = func_get_args();		
		if($fetchStyle != PDO::FETCH_ASSOC && $fetchStyle != PDO::FETCH_BOTH && $fetchStyle != PDO::FETCH_NUM && $fetchStyle != PDO::FETCH_LAZY && $fetchStyle != PDO::FETCH_BOUND && $fetchStyle != PDO::FETCH_OBJ && $fetchStyle != PDO::FETCH_COLUMN && $fetchStyle != PDO::FETCH_CLASS && $fetchStyle != PDO::FETCH_INTO) { 
			throw new DbException('Nieznana lub nieuzywana stala', 'No known constant', 986900);
		}
		try {
			return call_user_func_array(array($this->_pdoStm, 'fetch'), $args);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna zwrocic rekordu', $pe->getMessage(), (int)$pe->getCode());
		}			
	}

	/**
	 * Funkcja zwraca caly zbior wynikow
	 * 
	 * @access public
	 * @param int Styl zwracania wynikow
	 * @return array
	 *
	 */
	public function fetchAll($fetchStyle=PDO::FETCH_BOTH) {
		$args = func_get_args();
		if($fetchStyle != PDO::FETCH_ASSOC && $fetchStyle != PDO::FETCH_BOTH && $fetchStyle != PDO::FETCH_NUM && $fetchStyle != PDO::FETCH_LAZY && $fetchStyle != PDO::FETCH_OBJ && $fetchStyle != PDO::FETCH_COLUMN && $fetchStyle != PDO::FETCH_CLASS) { 
			throw new DbException('Nieznana lub nieuzywana stala', 'No known constant', 9869);
		}		
		try {
			return call_user_func_array(array($this->_pdoStm, 'fetchAll'), $args);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna zwrocic zbioru rekordow', $pe->getMessage(), (int)$pe->getCode());
		}			
	}

	/**
	 * Funkcja zwraca wartosc danej kolumny z kolejnego rekordu danych
	 * 
	 * @access public
	 * @param int Nazwa lub numer kolumny
	 * @return string | false
	 *
	 */	
	public function fetchColumn($columnNumber=0) {
		try {
			return $this->_pdoStm->fetchColumn($columnNumber);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna zwrocic wartosci danej kolumny', $pe->getMessage(), (int)$pe->getCode());
		}		
	}

	/**
	 * Funkcja pobiera nastepny rekord i zwraca dane jako obiekt
	 * 
	 * @access public
	 * @param string Nazwa klasy
	 * param array Tablica argumentow konstruktora
	 * @return object | false
	 *
	 */		
	public function fetchObject($className="stdClass", $ctorArgs=array()) {
		try {
			return $this->_pdoStm->fetchObject($className, $ctorArgs);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna zwrocic rekordu jako obiektu', $pe->getMessage(), (int)$pe->getCode());
		}			
	}
	
	/**
	 * Funkcja zwraca wartosc danego atrybutu
	 * 
	 * @access public
	 * @param int Atrybut identyfikowany poprzez stala
	 * @return mixed
	 *
	 */	
	public function getAttribute($attribute) {
		try {
			return $this->_pdoStm->getAttribute($attribute);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna zwrocic wartosci atrybutu', $pe->getMessage(), (int)$pe->getCode());
		}			
	}

	/**
	 * Funkcja zwraca tablice informacji o kolumnie
	 * 
	 * @access public
	 * @param int Numer kolumny
	 * @return array
	 *
	 */		
	public function getColumnMeta($column) {
		try {
			return $this->_pdoStm->getColumnMeta($column);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna zwrocic informacji o kolumnie identyfikowanej przez nazwe', $pe->getMessage(), (int)$pe->getCode());
		}			
	}
	
	/**
	 * Funkcja pozwala na przejscie do natepnego zbioru danych, 
	 * co jest mozliwe w przypadku niektorych serwerow baz danych
	 * 
	 * @access public
	 * @return bool
	 *
	 */			
	public function nextRowset() {
		try {
			return $this->_pdoStm->nextRowset();
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna przejsc do nastepnego zbioru wynikow', $pe->getMessage(), (int)$pe->getCode());
		}		
	}

	/**
	 * Funkcja zwraca ilosc zmienionych wierszy
	 * 
	 * @access public
	 * @return int
	 *
	 */			
	public function rowCount() {
		try {
			return $this->_pdoStm->rowCount();
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna zwrocic ilosci zmienionych wierszy', $pe->getMessage(), (int)$pe->getCode());
		}
	}

	/**
	 * Funkcja ustawia wartosc danego atrybutu
	 * 
	 * @access public
	 * @param int Atrybut identyfikowany przez stala
	 * @param mixed Wartosc atrybutu
	 * @return bool
	 *
	 */		
	public function setAttribute($attribute, $value) {
		try {
			return $this->_pdoStm->setAttribute($attribute, $value);
		} catch(PDOException $pe) {
			throw new DbException('Cannot set attribute', $pe->getMessage(), (int)$pe->getCode());
		}
	}

	/**
	 * Funkcja ustawia sposob pobierania danych ze zbioru wynikow
	 * 
	 * @access public
	 * @param int Styl zwracania wynikow
	 * @return 1 | false
	 *
	 */		
	public function setFetchMode($fetchMode) {
		$args = func_get_args();
		if($args[0] != PDO::FETCH_ASSOC && $args[0] != PDO::FETCH_BOTH && $args[0] != PDO::FETCH_NUM && $args[0] != PDO::FETCH_LAZY && $args[0] != PDO::FETCH_BOUND && $args[0] != PDO::FETCH_OBJ && $args[0] != PDO::FETCH_COLUMN && $args[0] != PDO::FETCH_CLASS && $args[0] != PDO::FETCH_INTO) { 
			throw new DbException('Nieznana lub nieuzywana stala: "' . $args[0] . '"' , 'No known constant', 986900);
		}		
		try {
			return call_user_func_array(array($this->_pdoStm, 'setFetchMode'), $args);
		} catch(PDOException $pe) {
			throw new DbException('Nie mozna ustawic stylu zwracania wynikow', $pe->getMessage(), (int)$pe->getCode());
		}
	}
	
}

?>
