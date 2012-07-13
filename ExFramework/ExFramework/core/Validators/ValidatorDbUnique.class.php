<?php

/**
 * @class ValidatorDbUnique
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ValidatorDbUnique extends ValidatorAbstract implements IValidator {
	
	/**
	 * Komunikat
	 *
	 * @var string
	 * 
	 */		
	protected $_message = 'This value already exists or is invalid';
	
	/**
	 * Obiekt bazy danych
	 *
	 * @var object
	 * 
	 */		
	protected $_db = null;	
	
	/**
	 * Nazwa tabeli w bazie danych
	 *
	 * @var string
	 * 
	 */		
	protected $_tableName = null;	
	
	/**
	 * Nazwa sprawdzanego pola w bazie danych
	 *
	 * @var string
	 * 
	 */		
	protected $_fieldName = null;		
	
	/**
	 * Konstruktor
	 *
	 * @access public
	 * @param object Obiekt obslugi bazy danych
	 * 
	 */		
	public function __construct(IDb $db) {
		$this->_db = $db;
	}
	
	/**	
	 * Sprawdza czy istnieje juz rekord o unikalnej wartosci sprawdzanego pola
	 * 
	 * @access public
	 * @param string Identyfikator zmiennej
	 * @param mixed Wartosc sprawdzanej zmiennej
	 * @param array Tablica opcji/parametrow
	 * @return bool
	 * 
	 */
	public function isValid($varId, $value, $options=array()) {
		$this->reset();
		if(count($options) > 0) {
			$this->setParams($options);
		}
		if($this->_tableName === null) {
			throw new ValidatorException('Obiekt walidatora nie zostal poprawnie skonfigurowany');
		}
		if($this->_fieldName === null) {
			throw new ValidatorException('Obiekt walidatora nie zostal poprawnie skonfigurowany');
		} 	
		if(is_array($value) || is_object($value)) {
			$this->_errors[$varId][] = $this->_message;
			return false;
		}
		$query = "SELECT * FROM `" . $this->_tableName . "` WHERE `" . $this->_fieldName . "`=:value";
		$result = $this->_db->prepare($query);
		$result->execute(array(":value"=>$value));	
		$row = $result->fetch();
		if($row !== false) {
			$this->_errors[$varId][] = $this->_message;
			return false;
		}
		return true;
	}
	
	/**	
	 * Ustawia parametry walidatora
	 * 
	 * @access public
	 * @param Tablica parametrow
	 * @return void
	 * 
	 */	
	public function setParams($params=array()) {
		if(isset($params['tableName'])) {
			$this->_tableName = (string) $params['tableName'];
		}
		if(isset($params['fieldName'])) {
			$this->_fieldName = (string) $params['fieldName'];
		}
	}
	
	/**	
	 * Resetuje ustawienia walidatora
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function reset() {
		parent::reset();
		$this->_tableName = null;
		$this->_fieldName = null;
		$this->_message = 'This value already exists or is invalid';
	}	
	
}

?>
