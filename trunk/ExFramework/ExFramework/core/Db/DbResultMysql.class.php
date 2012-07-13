<?php

/**
 * @class DbResultMysql
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class DbResultMysql extends DbResultAbstract implements IDbResult {
	
	/**
	 * Tablica parametrow
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_parameters = array();
	
	/**
	 * Zbior wynikow; wynik DbMysql.query lub DbResultMysql.execute
	 * 
	 * @var resource|bool
	 * 
	 */ 	
	protected $_result = null;
	
	/**
	 * Nieprzetworzone zapytanie, z prepare
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_rawQuery = '';
	
	/**
	 * Zapytanie przetworzone lub z query
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_query = '';
	
	/**
	 * Obiekt obslugi bazy danych MySQL
	 * 
	 * @var object
	 * 
	 */ 		
	protected $_dbObject = null;
	
	/**
	 * Tablica powiazan nazw/numerow kolumn z referencjami do zmiennych
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_bindedToColumn = array();
	
	/**
	 * Styl pobierania wynikow ze zbioru wynikow
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_fetchStyle = array('style' => DbAbstract::FETCH_BOTH,
									'class' => '',
									'params' => array(),
									'object' => null,
									'column' => 0
								);
	
	/**
	 * Tablica atrybutow
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_attributes = array();
	
	/**
	 * Tablica typow parametrow
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_parameterTypes = array(DbAbstract::PARAM_BOOL=>'bool', 
										DbAbstract::PARAM_NULL=>'null', 
										DbAbstract::PARAM_INT=>'int', 
										DbAbstract::PARAM_STR=>'string', 
										DbAbstract::PARAM_LOB=>'lob', 
										DbAbstract::PARAM_INPUT_OUTPUT=>'inout'
								);

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
	 * Ustawia obiekt zbioru wynikow i zapytanie
	 * 
	 * @access public
	 * @param resource|null Zbior wynikow jesli wykonano metode query lub null jesli prepare
	 * @param string Zapytanie SQL
	 * @return void
	 * 
	 */ 	
	public function setResultAndQuery($result, $query) {
		$this->_result = $result; //zbior wynikow lub null
		if($result !== null) {
			$this->_query = $query;
			$this->_rawQuery = null;
		}
		else {
			$this->_rawQuery = $query;
			$this->_query = null;
		}		
	}
			 
	/**
	 * Ustawia obiekt oblugi bazy MySQL
	 * 
	 * @access public
	 * @param object Objekt zapytania
	 * @return void
	 * 
	 */ 	
	public function setDbObject(DbMysql $db) {
		$this->_dbObject = $db;
	} 
	
	/**
	 * Destruktor
	 * Zawalnia zasoby zajmowane przez zbior wynikow
	 * 
	 * @access public
	 * 
	 */ 
	public function __destruct() {
		$this->free();
	}
	
	/**
	 * Funkcja zwalnia zasoby przyznane dla zbioru wynikow
	 * 
	 * @access protected
	 * @return void
	 * 
	 */ 	
	protected function free() {
		if(is_resource($this->_result)) {
			if(!@mysql_free_result($this->_result)) {
				throw new DbException('Nie mozna zwolnic zasobow', $this->db->getLastError(), $this->db->getLastErrorNumber());	
			}
		}
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
	 public function bindColumn($column, &$param, $type=DbAbstract::PARAM_STR) {
		if(is_numeric($column)) {
			$column -= 1;  
		}
		if(!array_key_exists($type, $this->_parameterTypes)) {
			return false;
		}
		$this->_bindedToColumn[$column] = array('param' => &$param, 'type' => $type);
		return true;
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
	public function bindParam($parameter, &$variable, $type=DbAbstract::PARAM_STR, $length=null) {
		if(!array_key_exists($type, $this->_parameterTypes)) {
			return false;
		}
		if($type == DbAbstract::PARAM_BOOL || $type == DbAbstract::PARAM_NULL || $type == DbAbstract::PARAM_INT || $type == DbAbstract::PARAM_STR) {
			$func = 'is_' . $this->_parameterTypes[$type];
			if(!$func($variable)) {
				$variable = (string)$variable;
			}
		}
		$this->_parameters[$parameter] = array('parameter' => $parameter, 'variable' => &$variable, 'type' => $type, 'length' => $length);
		return true;				
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
	public function bindValue($parameter, $variable, $type=DbAbstract::PARAM_STR) {
		if(!array_key_exists($type, $this->_parameterTypes)) {
			return false;
		}
		if($type == DbAbstract::PARAM_BOOL || $type == DbAbstract::PARAM_NULL || $type == DbAbstract::PARAM_INT || $type == DbAbstract::PARAM_STR) {
			$func = 'is_' . $this->_parameterTypes[$type];
			if(!$func($variable)) {
				$variable = (string)$variable;
			}
		}
		$this->_parameters[$parameter] = array('parameter' => $parameter, 'variable' => $variable, 'type' => $type, 'length' => null);
		return true;
	}
	
	/**
	 * Funkcja zwalnia polaczenie z baza danych, nie rozlacza tylko umozliwia wykonaniue kolejnego zapytania
	 * 
	 * @access public
	 * @return bool
	 * 
	 */	
	public function closeCursor() {
		$this->free();
		$this->_result = null;
		$this->_dbObject->setCursorClosed(true);
		
		$this->_parameters = array();
		//$this->_rawQuery = null;
		$this->_query = null;
		//$this->_dbObject = null;
		$this->_bindedToColumn = array();
		$this->_attributes = array();
		$this->_fetchStyle = array('style' => DbAbstract::FETCH_BOTH,
									'class' => '',
									'params' => array(),
									'object' => null,
									'column' => 0
								);
		return true;	
	}
	
	/**
	 * Funkcja zwraca liczbe kolumn w zbiorze wynikow
	 * 
	 * @access public
	 * @return int
	 * 
	 */	
	public function columnCount() {
		if(!is_resource($this->_result)) {
			throw new DbException('Nie wykonano jeszcze zapytania lub brak wynikow', 'No results', '09868');
		}		
		$numFields = 0;
		if(!$numFields = mysql_num_fields($this->_result)) {
			throw new DbException('Nie mozna zwrocic liczby pol w zbiorze wynikow');
		}
		return $numFields;
	}
	
	/**
	 * Funkcja wyswietla przygotowane zlozone zapytanie
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function debugDumpParams() {
		echo 'SQL: '. $this->_rawQuery . "<br>\n";
		echo 'Params: ' . count($this->_parameters) . "<br>\n";
		foreach($this->_parameters as $param) {
			echo 'Name: ' . $param['parameter'] . "<br>\n";
			echo 'Type: ' . $param['type'] . "<br>\n";
			echo 'Length: ' . $param['length'] . "<br>\n";
			echo 'Value: ' . $param['variable'] . "<br>\n";
		}
	}
	
	/**
	 * Funkcja zwraca kod bledu ostatniej operacji
	 * 
	 * Istnieje tylk odla kompatybilnosci
	 * 
	 * @access public
	 * @return string|null
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
			$errorInfo[1] = $this->_dbObject->getLastErrorNumber();
			$errorInfo[2] = $this->_dbObject->getLastError();
			return $errorInfo;
		}
		return null;
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
		if($this->_dbObject->getCursorClosed() == false) {
			if(is_resource($this->_result)) {
				$this->closeCursor();
			}
			//$this->_dbObject->setCursorClosed(true);
		}
		$this->_dbObject->setCursorClosed(false);
		if(count($inputParameters) > 0) {
			foreach($inputParameters as $index=>$value) {
				$this->bindValue($index, $value);
			}
		}
		if((strstr($this->_rawQuery, '?') !== false) && (strstr($this->_rawQuery, ':') !== false)) {
			throw new DbException('Nie mozna uzywac w tym samym czasie mapowan z uzyciem ? i :nazwaParametru', 'You cannot use ? and :paramName at the same time', 10987);	
		}
		$tempQuery = $this->_rawQuery;
		if(strstr($tempQuery, '?') !== false) {
			if(isset($this->_parameters[0])) {
				$i=0;
			}
			else {
				$i=1;
			}
			while($pos = strpos($tempQuery, '?')) { 
				$param = $this->_parameters[$i]['variable'];
				if($this->_parameters[$i]['type'] == DbAbstract::PARAM_STR && $this->_parameters[$i]['length'] != null) {
					$param = substr($param, 0, $this->_parameters[$i]['length']);
				}
				$param = $this->_dbObject->quote($param);
				$tempQuery = substr_replace($tempQuery, $param, $pos, 1); 
				$i++;
			}
			$this->_query = $tempQuery;
			$this->_result = mysql_query($this->_query);
			$this->_dbObject->setCursorClosed(false);
			return true;
		}
		else {			
			foreach($this->_parameters as $paramName=>$pV) {
				$variable = $this->_dbObject->quote($pV['variable']);				
				//$tempQuery = str_replace($pV['parameter'], $variable, $tempQuery); ///////////////////to powodowalo niedopasowania w przypadku np :str i :str1 , pierwszy oba byly podmieniane przez ten sam ciag				
				$tempQuery = preg_replace('/('. $pV['parameter'] .'([^[:alnum:]_]))|('. $pV['parameter'] .'$)/', $variable.'\\2', $tempQuery);
			}
			$this->_query = $tempQuery;
			$result = mysql_query($this->_query);
			if($result === false) {
				throw new DbException('Nie mozna wykonac zapytania', $this->_dbObject->getLastError(), $this->_dbObject->getLastErrorNumber());
			}		
			$this->_result = $result;	
			$this->_dbObject->setCursorClosed(false);
			return true;
		}
		return false;
	}

	/**
	 * Funkcja pobiera wiersz danych ze zbioru wynikow
	 * 
	 * @access public
	 * @param int Styl zwracania wynikow
	 * @return mixed
	 *
	 */	
	public function fetch($fetchStyle=DbAbstract::FETCH_BOTH) {
		if(!is_resource($this->_result)) {
			throw new DbException('Nie wykonano jeszcze zapytania lub brak wynikow', 'No results', '09868');
		}
		if($fetchStyle != DbAbstract::FETCH_ASSOC && $fetchStyle != DbAbstract::FETCH_BOTH && $fetchStyle != DbAbstract::FETCH_NUM && $fetchStyle != DbAbstract::FETCH_LAZY && $fetchStyle != DbAbstract::FETCH_BOUND && $fetchStyle != DbAbstract::FETCH_OBJ && $fetchStyle != DbAbstract::FETCH_COLUMN && $fetchStyle != DbAbstract::FETCH_CLASS && $fetchStyle != DbAbstract::FETCH_INTO) { 
			throw new DbException('Nieznana lub nieuzywana stala', 'No known constant', '09869');
		}
		if($this->_fetchStyle['style'] == DbAbstract::FETCH_BOTH && $fetchStyle != DbAbstract::FETCH_BOTH) {
			$this->_fetchStyle['style'] = $fetchStyle;
		}
		if($this->_fetchStyle['style'] == DbAbstract::FETCH_ASSOC) {
			return mysql_fetch_assoc($this->_result);
		}
		elseif($this->_fetchStyle['style'] == DbAbstract::FETCH_NUM) {
			return mysql_fetch_row($this->_result);
		}
		elseif($this->_fetchStyle['style'] == DbAbstract::FETCH_OBJ || $this->_fetchStyle['style'] == DbAbstract::FETCH_LAZY) {
			return mysql_fetch_object($this->_result);
		}	
		elseif($this->_fetchStyle['style'] == DbAbstract::FETCH_CLASS) {
			$arr = mysql_fetch_assoc($this->_result);
			if(!$arr) {
				return false;
			}
			if($this->_fetchStyle['class'] == '') {
				throw new DbException('Nie podano nazwy klasy', 'No class name', '09870');
			}
			$className = $this->_fetchStyle['class'];
			$args = $this->_fetchStyle['params'];			
			
			if(count($args) > 0) {
				$args2 = array();
				foreach($args AS $varName) {
					$args2[] = isset($arr[${$varName}]) ? $row[${$varName}] : null;
				}
				$refClass = new ReflectionClass($className);
				$obj = $refClass->newInstanceArgs($args2);				
				//$obj = new $className($args2);
			}
			else {
				$refClass = new ReflectionClass($className);
				$obj = $refClass->newInstance();				
				//$obj = new $className();
				foreach($arr AS $key=>$value) {
					$obj->$key = $value;
				}
			}
			return $obj;
		}
		elseif($this->_fetchStyle['style'] == DbAbstract::FETCH_INTO) {
			$arr = mysql_fetch_assoc($this->_result);
			if(!$arr) {
				return false;
			}			
			if($this->_fetchStyle['object'] == null) {
				throw new DbException('Brak instancji klasy', 'No instance of the class', '09871');
			}
			$obj = $this->_fetchStyle['object']; 
			foreach($arr AS $key=>$value) {
				$obj->$key = $value;
			}
			return $obj;
		}		
		elseif($this->_fetchStyle['style'] == DbAbstract::FETCH_BOUND) {
			$arr = mysql_fetch_array($this->_result);
			if(!$arr) {
				return false;
			}			
			foreach($arr AS $columnName => $value) {
				if(isset($this->_bindedToColumn[$columnName])) {
					$this->_bindedToColumn[$columnName]['param'] = $value; 
				}
			}
			return true;
		}
		else {
			return mysql_fetch_array($this->_result);			
		}
		return false;
	}

	/**
	 * Funkcja zwraca caly zbior wynikow
	 * 
	 * @access public
	 * @param int Styl zwracania wynikow
	 * @param int Indeks kolumny jesli taki wybrano styl FETCH_COLUMN lub nazwa klasy jesli wybrano FETCH_CLASS
	 * @return array
	 *
	 */	
	public function fetchAll($fetchStyle=DbAbstract::FETCH_BOTH, $fetchArgument=0, $params=array()) {
		if(!is_resource($this->_result)) {
			throw new DbException('Nie wykonano jeszcze zapytania lub brak wynikow', 'No results', '09868');
		}
		if($fetchStyle != DbAbstract::FETCH_ASSOC && $fetchStyle != DbAbstract::FETCH_BOTH && $fetchStyle != DbAbstract::FETCH_NUM && $fetchStyle != DbAbstract::FETCH_LAZY && $fetchStyle != DbAbstract::FETCH_OBJ && $fetchStyle != DbAbstract::FETCH_COLUMN && $fetchStyle != DbAbstract::FETCH_CLASS) { 
			throw new DbException('Nieznana lub nieuzywana stala', 'No known constant', '09869');
		}		
		$retArr = array();
		if($this->_fetchStyle['style'] == DbAbstract::FETCH_BOTH && $fetchStyle != DbAbstract::FETCH_BOTH) {
			$this->_fetchStyle['style'] = $fetchStyle;
		}
		if($this->_fetchStyle['style'] == DbAbstract::FETCH_CLASS) {
			if($fetchArgument != 0) {
				$this->_fetchStyle['class'] = $fetchArgument;
			}
			if($this->_fetchStyle['class'] == 0 || $this->_fetchStyle['class'] == '') {
				throw new DbException('Nie podano nazwy klasy', 'No class name', '09870');
			}
			if(count($params) > 0) {
				$this->_fetchStyle['params'] = $params;
			}
			$className = $this->_fetchStyle['class'];
			$args = $this->_fetchStyle['params'];
			while($row = @mysql_fetch_assoc($this->_result)) {
				if(count($args) > 0) {
					$args2 = array();
					foreach($args AS $varName) {
						$args2[] = isset($row[${$varName}]) ? $row[${$varName}] : null;
					}
					$refClass = new ReflectionClass($className);
					$obj = $refClass->newInstanceArgs($args2);
					//$obj = new $className($args2);
				}
				else {
					$refClass = new ReflectionClass($className);
					$obj = $refClass->newInstance();					
					//$obj = new $className();
					foreach($row AS $key=>$value) {
						$obj->$key = $value;
					}
				}	
				$retArr[] = $obj;
			}
			return $retArr;			
		}
		elseif($this->_fetchStyle['style'] == DbAbstract::FETCH_ASSOC) {
			while($row = @mysql_fetch_assoc($this->_result)) {
				$retArr[] = $row;
			}
			return $retArr;
		}
		elseif($this->_fetchStyle['style'] == DbAbstract::FETCH_COLUMN) {
			while($row = @mysql_fetch_assoc($this->_result)) {
				$retArr[] = $row[$fetchArgument];
			}
			return $retArr;
		}		
		elseif($this->_fetchStyle['style'] == DbAbstract::FETCH_NUM) {
			while($row = @mysql_fetch_row($this->_result)) {
				$retArr[] = $row;
			}
			return $retArr;
		}
		elseif($this->_fetchStyle['style'] == DbAbstract::FETCH_OBJ || $this->_fetchStyle['style'] == DbAbstract::FETCH_LAZY) {
			while($obj = @mysql_fetch_object($this->_result)) {
				$retArr[] = $obj;
			}
			return $retArr;
		}
		else { 																//both
			while($row = @mysql_fetch_array($this->_result)) {
				$retArr[] = $row;
			}
			return $retArr;
		}
	}

	/**
	 * Funkcja zwraca wartosc danej kolumny z kolejnego rekordu danych
	 * 
	 * @access public
	 * @param int Nazwa lub numer kolumny
	 * @return string | int | false
	 *
	 */	
	
	public function fetchColumn($columnNumber=0) {
		if(!is_resource($this->_result)) {
			throw new DbException('Nie wykonano jeszcze zapytania lub brak wynikow', 'No results', '09868');
		}
		$row = mysql_fetch_row($this->_result);
		if($row !== false) {
			return $row[$columnNumber];
		}
		return false;
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
		if(!is_resource($this->_result)) {
			throw new DbException('Nie wykonano jeszcze zapytania lub brak wynikow', 'No results', '09868');
		}
		$arr = mysql_fetch_assoc($this->_result);
		if(!$arr) {
			return false;
		}
		//$obj = new $className($ctorArgs);
		$refClass = new ReflectionClass($className);
		if($className == 'stdClass') {
			$obj = $refClass->newInstance();
		}
		else {
			$obj = $refClass->newInstanceArgs($ctorArgs);
		}
		foreach($arr AS $key=>$value) {
			$obj->$key = $value;
		}
		return $obj;
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
		if(isset($this->_attributes[$attribute])) {
			return $this->_attributes[$attribute];
		}
		else {
			return null;
		}
	}

	/**
	 * Funkcja zwraca tablice informacji o kolumnie
	 * 
	 * Wystepuje tylko dla kompatybilnosci
	 * 
	 * @access public
	 * @param int Numer kolumny
	 * @return array|null
	 *
	 */		
	public function getColumnMeta($column) {
		return null;
	}

	/**
	 * Funkcja pozwala na przejscie do natepnego zbioru danych, 
	 * co jest mozliwe w przypadku niektorych serwerow baz danych
	 * 
	 * Wystepuje tylko dla kompatybilnosci
	 * 
	 * @access public
	 * @return bool
	 *
	 */		
	public function nextRowset() {
		return false;
	}

	/**
	 * Funkcja zwraca ilosc zmienionych wierszy
	 * 
	 * @access public
	 * @return int
	 *
	 */		
	public function rowCount() {
		if(!is_resource($this->_result) && !is_bool($this->_result)) {
			throw new DbException('Nie wykonano jeszcze zapytania lub brak wynikow', 'No results', '09868');
		}
		$numRow = null;
		if(is_resource($this->_result)) {
			$numRows = mysql_num_rows($this->_result);
		}
		if(is_bool($this->_result)) {
			$numRows = mysql_affected_rows($this->_dbObject->getConnectionId());
			if($numRows === -1) {
				throw new DbException('Nie mozna zwrocic liczby wierszy', $this->_dbObject->getLastError(), $this->_dbObject->getLastErrorNumber());
			}
		}
		return $numRows;
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
		$this->_attributes[$attribute] = $value;
		return true;		
	}
	
	/**
	 * Funkcja ustawia sposob pobierania danych ze zbioru wynikow
	 * 
	 * @access public
	 * @param int Styl zwracania wynikow
	 * @param mixed Objekt, nazwa klasy lub numer kolumny
	 * @param mixed Tablica parametrow konstruktowa klasy
	 * @return 1
	 *
	 */	
	public function setFetchMode($fetchMode) {
		$args = func_get_args();
		if($args[0] != DbAbstract::FETCH_ASSOC && $args[0] != DbAbstract::FETCH_BOTH && $args[0] != DbAbstract::FETCH_NUM && $args[0] != DbAbstract::FETCH_LAZY && $args[0] != DbAbstract::FETCH_BOUND && $args[0] != DbAbstract::FETCH_OBJ && $args[0] != DbAbstract::FETCH_COLUMN && $args[0] != DbAbstract::FETCH_CLASS && $args[0] != DbAbstract::FETCH_INTO) { 
			throw new DbException('Nieznana lub nieuzywana stala', 'No known constant', '09869');
		}
		if($args[0] == DbAbstract::FETCH_ASSOC || $args[0] == DbAbstract::FETCH_BOTH || $args[0] == DbAbstract::FETCH_NUM || $args[0] == DbAbstract::FETCH_LAZY || $args[0] == DbAbstract::FETCH_BOUND || $args[0] == DbAbstract::FETCH_OBJ) {
			$this->_fetchStyle['style'] = $args[0];
			$this->_fetchStyle['class'] = '';
			$this->_fetchStyle['params'] = array();
			$this->_fetchStyle['object'] = null;
			$this->_fetchStyle['column'] = 0;
		}
		if($args[0] == DbAbstract::FETCH_COLUMN) {
			$columnNumber = (isset($args[1])) ? (int) $args[1] : 0;
			$this->_fetchStyle['style'] = $args[0];
			$this->_fetchStyle['class'] = '';
			$this->_fetchStyle['params'] = array();
			$this->_fetchStyle['object'] = null;
			$this->_fetchStyle['column'] = $columnNumber;		
		}
		if($args[0] == DbAbstract::FETCH_CLASS) {
			if(!isset($args[1]) || !is_string($args[1])) {
				throw new DbException('Nie podano nazwy klasy', 'No class name', '09870');
			}
			$params = (isset($args[2])) ? (array) $args[2] : array();
			$this->_fetchStyle['style'] = $args[0];
			$this->_fetchStyle['class'] = $args[1];
			$this->_fetchStyle['params'] = $params;
			$this->_fetchStyle['object'] = null;
			$this->_fetchStyle['column'] = 0;		
		}
		if($args[0] == DbAbstract::FETCH_INTO) {
			if(!isset($args[1]) || !is_object($args[1])) {
				throw new DbException('Brak instancji klasy', 'No instance of the class', '09871');
			}
			$this->_fetchStyle['style'] = $args[0];
			$this->_fetchStyle['class'] = '';
			$this->_fetchStyle['params'] = array();
			$this->_fetchStyle['object'] = $args[1];
			$this->_fetchStyle['column'] = 0;		
		}
		return 1;		
	}
	
}

?>
