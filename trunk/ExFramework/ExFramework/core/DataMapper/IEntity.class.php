<?php

/**
 * @interface IEntity
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IEntity {
	
	/**
	 * Zwraca tablice wartosci pol obiektu
	 * 
	 * @access public
	 * @return array
	 * 
	 */ 	
	public function getValues();
	
	/**
	 * Zwraca tablice wartosci pol obiektu przed modyfikacja, jesli taka zaszla
	 * 
	 * @access public
	 * @return array
	 * 
	 */ 	
	public function getOldValues();
	
	/**
	 * Zwraca tablice indeksow zmodyfikowanych pol
	 * 
	 * @access public
	 * @return array
	 * 
	 */ 	
	public function getModifiedFields();
	
	/**
	 * Zwraca tablice zmodyfikowanych i przetworzonych danych 
	 * 
	 * @access public
	 * @return array
	 * 
	 */ 	
	public function getModifiedData();
	
	/**
	 * Zwraca konkretna wartosc pola w tablicy przed modyfikacja
	 * 
	 * @access public
	 * @param string Indeks tablicy
	 * @return mixed
	 * 
	 */
	public function getOld($index);
	
	/**
	 * Przelacza flage: obiekt nowy / obiekt odczytany z bazy danych
	 * 
	 * @access public
	 * @param bool Tru jesli nowy
	 * @return void
	 * 
	 */ 	
	public function created($new=true);
	
	/**
	 * Przelacza flage: obiekt niemodyfikowany / obiekt zmodyfikowany
	 * 
	 * @access public
	 * @param bool True dla modyfikowanego
	 * @return void
	 * 
	 */
	public function modified($modified=true);
	
	/**
	 * Przelacza flage obiekt usuniety / obiekt nieusuniety
	 * 
	 * @access public
	 * @param bool True dla obiektu usunietego
	 * @return void
	 * 
	 */
	public function deleted($deleted=true);
	
	/**
	 * Przelacza flage: obiekt tylko do odczytu / obiekt do zapisu i odczytu
	 * 
	 * @access public
	 * @param bool True jesli dopuszczono tylko odczyt
	 * @return void
	 * 
	 */ 	
	public function readOnly($ro=true);
	
	/**
	 * Przelacza flage obiekt sprawdzony / obiekt niezweryfikowany
	 * 
	 * @access public
	 * @param bool True dla obiektu po weryfikacji
	 * @return void
	 * 
	 */ 	
	public function valid($valid=true);
	
	/**
	 * Sprawdza czy obiekt jest nowy
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 	
	public function isNew();
	
	/**
	 * Sprawdza czy obiekt zostal zmodyfikowany
	 * 
	 * @access public
	 * @return bool
	 * 
	 */
	public function isModified();
	
	/**
	 * Sprawdza czy obiekt zostal usuniety
	 * 
	 * @access public
	 * @return bool
	 * 
	 */
	public function isDeleted();
	
	/**
	 * Sprawdza czy obiekt jest tylko do odczytu
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 	
	public function isReadOnly();
	
	/**
	 * Sprawdza czy obiet jest zweryfikowany do zapisu w bazie danych
	 * 
	 * @access public
	 * @return bool
	 * 
	 */
	public function isValid();
	
	/**
	 * Synchronizacja tablic wartosci pol obiektu przed i po modyfikacja, np po zapisie obiektu do bazy danych
	 * 
	 * @access public
	 * @return void
	 * 
	 */
	public function updateOld();
	
	/**
	 * Resetowanie tablicy indeksow zmodyfikowanych pol, np po zapisaniu do bazy
	 * 
	 * @access public
	 * @return void
	 * 
	 */
	public function resetModifiedFields();
	
	/**
	 * Ustawia wartosc pola obiektu, bezposrednio lub z wykorzystaniem odpowiedniego istniejacego settera
	 * 
	 * @access public
	 * @param string Nazwa pola obiektu
	 * @param mixed Wartosc pola obiektu
	 * @return void
	 * 
	 */
	public function set($name, $value);
	
	/**
	 * Zwraca wartosc danego pola obiektu, pobierajac z niego bezposrednio lub odpowiednim getterem
	 * 
	 * @access public
	 * @param string Nazwa pola obiektu
	 * @return void
	 * 
	 */
	public function get($name);
	
	/**
	 * Zwraca tablice wartosci pol obiektu
	 * 
	 * @access public
	 * @param array Nazwy pol do zwrocenia
	 * @return array
	 * 
	 */
	public function toArray($fieldsNames=null);
	
}

?>
