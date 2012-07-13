<?php

/**
 * @class EntityAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class EntityAbstract implements IEntity {
	
	/**
	 * Pola obiektu zmapowane z bazy danych. Podlegaja modyfikacji
	 *
	 * @var array
	 * 
	 */		
	protected $_values = array();
	
	/**
	 * Jak wyzej, jednak obecne dla sprawdzenia co sie zmienia, wymienialne w calosci
	 *
	 * @var array
	 * 
	 */		
	protected $_oldValues = array();

	/**
	 * Tablica przechowujaca indeksy - klucze zmodyfikowanych pol
	 *
	 * @var array
	 * 
	 */		
	protected $_modifiedFields = array();
	
	/**
	 * Przelacznik: obiekt nowy lub wyciagniety z bazy danych
	 *
	 * @var bool
	 * 
	 */		
	protected $_new = false;
	
	/**
	 * Przelacznik: obiekt zmodyfikowany lub nie
	 *
	 * @var bool
	 * 
	 */			
	protected $_modified = false;
	
	/**
	 * Przelacznik: obiekt usuniety lub nie
	 *
	 * @var bool
	 * 
	 */			
	protected $_deleted = false;
	
	/**
	 * Przelacznik: obiekt tylko do odczytu lub do odczytu i zapisu
	 *
	 * @var bool
	 * 
	 */			
	protected $_readOnly = false;
	
	/**
	 * Przelacznik: obiekt zweryfikowany i gotowy do zapisu w bazie danych
	 *
	 * @var bool
	 * 
	 */			
	protected $_valid = false;
	
	/**
	 * Zwraca tablice wartosci pol obiektu
	 * 
	 * @access public
	 * @return array
	 * 
	 */ 	
	public function getValues() {
		return $this->_values;
	}
	
	/**
	 * Zwraca tablice wartosci pol obiektu przed modyfikacja, jesli taka zaszla
	 * 
	 * @access public
	 * @return array
	 * 
	 */ 	
	public function getOldValues() {
		return $this->_oldValues;
	}
	
	/**
	 * Zwraca tablice indeksow zmodyfikowanych pol
	 * 
	 * @access public
	 * @return array
	 * 
	 */ 	
	public function getModifiedFields() {
		return $this->_modifiedFields;
	}	
	
	/**
	 * Zwraca tablice zmodyfikowanych i przetworzonych danych 
	 * 
	 * @access public
	 * @return array
	 * 
	 */ 	
	public function getModifiedData() {
		$modifiedArray = array();
		foreach($this->_modifiedFields AS $field) {
			$modifiedArray[$field] = $this->_values[$field];
		}
		return $modifiedArray;
	}	
	
	/**
	 * Zwraca konkretna wartosc pola w tablicy przed modyfikacja
	 * 
	 * @access public
	 * @param string Indeks tablicy
	 * @return mixed
	 * 
	 */
	public function getOld($index) {
		if(isset($this->_oldValues[$index])) {
			return $this->_oldValues[$index];
		}
		else {
			return false;
		}
	}
	
	/**
	 * Przelacza flage: obiekt nowy / obiekt odczytany z bazy danych
	 * 
	 * @access public
	 * @param bool Tru jesli nowy
	 * @return void
	 * 
	 */ 	
	public function created($new=true) {
		$this->_new = $new;
	}
	
	/**
	 * Przelacza flage: obiekt niemodyfikowany / obiekt zmodyfikowany
	 * 
	 * @access public
	 * @param bool True dla modyfikowanego
	 * @return void
	 * 
	 */
	public function modified($modified=true) {
		$this->_modified = $modified;
	}
	
	/**
	 * Przelacza flage obiekt usuniety / obiekt nieusuniety
	 * 
	 * @access public
	 * @param bool True dla obiektu usunietego
	 * @return void
	 * 
	 */
	public function deleted($deleted=true) {
		$this->_deleted = $deleted;
	}
	
	/**
	 * Przelacza flage: obiekt tylko do odczytu / obiekt do zapisu i odczytu
	 * 
	 * @access public
	 * @param bool True jesli dopuszczono tylko odczyt
	 * @return void
	 * 
	 */ 	
	public function readOnly($ro=true) {
		$this->_readOnly = $ro;
	}
	
	/**
	 * Przelacza flage obiekt sprawdzony / obiekt niezweryfikowany
	 * 
	 * @access public
	 * @param bool True dla obiektu po weryfikacji
	 * @return void
	 * 
	 */ 	
	public function valid($valid=true) {
		$this->_valid = $valid;
	}
	
	/**
	 * Sprawdza czy obiekt jest nowy
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 	
	public function isNew() {
		return $this->_new;
	}
	
	/**
	 * Sprawdza czy obiekt zostal zmodyfikowany
	 * 
	 * @access public
	 * @return bool
	 * 
	 */
	public function isModified() {
		return $this->_modified;
	}
	
	/**
	 * Sprawdza czy obiekt zostal usuniety
	 * 
	 * @access public
	 * @return bool
	 * 
	 */
	public function isDeleted() {
		return $this->_deleted;
	}	
	
	/**
	 * Sprawdza czy obiekt jest tylko do odczytu
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 	
	public function isReadOnly() {
		return $this->_readOnly;
	}
	
	/**
	 * Sprawdza czy obiet jest zweryfikowany do zapisu w bazie danych
	 * 
	 * @access public
	 * @return bool
	 * 
	 */
	public function isValid() {
		return $this->_valid;
	}
	
	/**
	 * Synchronizacja tablic wartosci pol obiektu przed i po modyfikacja, np po zapisie obiektu do bazy danych
	 * 
	 * @access public
	 * @return void
	 * 
	 */
	public function updateOld() {
		$this->_oldValues = $this->_values;
	}
	
	/**
	 * Resetowanie tablicy indeksow zmodyfikowanych pol, np po zapisaniu do bazy
	 * 
	 * @access public
	 * @return void
	 * 
	 */
	public function resetModifiedFields() {
		$this->_modifiedFields = array();
	}	
	
	/**
	 * Ustawia wartosc pola obiektu, bezposrednio lub z wykorzystaniem odpowiedniego istniejacego settera
	 * 
	 * @access public
	 * @param string Nazwa pola obiektu
	 * @param mixed Wartosc pola obiektu
	 * @return void
	 * 
	 */
	public function __set($name, $value) {
		$this->_modifiedFields[] = $name;
		$method = 'set' . ucfirst($name);
		if(method_exists($this, $method) && is_callable(array($this, $method))) {
			$this->{$method}($value);
		}
		else {
			$this->_values[$name] = $value;
		}
		$this->modified(true);
		$this->valid(false);
	}
	
	/**
	 * Ustawia wartosc pola obiektu, bezposrednio lub z wykorzystaniem odpowiedniego istniejacego settera
	 * 
	 * @access public
	 * @param string Nazwa pola obiektu
	 * @param mixed Wartosc pola obiektu
	 * @return void
	 * 
	 */
	public function set($name, $value) {
		$this->__set($name, $value);
	}
	
	/**
	 * Zwraca wartosc danego pola obiektu, pobierajac z niego bezposrednio lub odpowiednim getterem
	 * 
	 * @access public
	 * @param string Nazwa pola obiektu
	 * @return void
	 * 
	 */
	public function __get($name) {		
		$method = 'get' . ucfirst($name);
		if(method_exists($this, $method) && is_callable(array($this, $method))) {
			return $this->{$method}();
		}
		else {
			if(isset($this->_values[$name])) {
				return $this->_values[$name];
			}
			else {
				return null;
			}
		}	
	}
	
	/**
	 * Zwraca wartosc danego pola obiektu, pobierajac z niego bezposrednio lub odpowiednim getterem
	 * 
	 * @access public
	 * @param string Nazwa pola obiektu
	 * @return void
	 * 
	 */
	public function get($name) {		
		return $this->__get($name);
	}	
	
	/**
	 * Sprawdza czy ustawiono wartosc dla danego pola obiektu
	 * 
	 * @access public
	 * @param string Nazwa pola obiektu
	 * @return bool
	 * 
	 */
	public function __isset($name) {
		return isset($this->_values[$name]);
	}
	
	/**
	 * Usuwa wartosc danego pola obiektu
	 * 
	 * @access public
	 * @param string Nazwa pola obiektu
	 * @return void
	 * 
	 */
	public function __unset($name) {
		if(isset($this->_values[$name])) {
			$this->_values[$name] = null;
			$this->modified(true);
			$this->valid(false);
		}
	}
	
	/**
	 * Zwraca tablice wartosci pol obiektu
	 * 
	 * @access public
	 * @param array Nazwy pol do zwrocenia
	 * @return array
	 * 
	 */
	public function toArray($fieldsNames=null) {
		if(is_array($fieldsNames)) {
			$tmpArr = array();
			foreach($fieldsNames AS $index=>$fieldName) {
				if(array_key_exists($fieldName, $this->_values)) { //2011.09.03 z isset na array_key_exists
					$tmpArr[$fieldName] = $this->_values[$fieldName];
				}
			}
			return $tmpArr;
		}
		else {
			return $this->_values;
		}
	}
	
	/**
	 * Wyswietla tablice wartosci pol obiektu
	 * 
	 * @access public
	 * @return void
	 * 
	 */
	public function __toString() {
		$str = "<pre>\n";
		foreach($this->_values AS $index=>$value) {
			if($value instanceof RelationMapper) {
				$str .= "$index: RelationMapperObject<br>";
				continue;
			}
			$str .= "$index: $value<br>";
		}
		$str .= "</pre>\n";
		return $str;
	}
	
}

?>
