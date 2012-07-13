<?php

/**
 * @interface IDataMapper
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IDataMapper {
	 
	/**
	 * Tworzy nowy obiekt biznesowy 
	 * 
	 * @access public
	 * @param array Wartosci pol nowo tworzonego obiektu biznesowego
	 * @return object
	 * 
	 */
	public function create($data=array());
	
	/**
	 * Tworzy obiekt biznesowy i laduje go danymi z bazy danych
	 * 
	 * @access public
	 * @param array Wartosci pol z bazy danych ladowane do obiektu biznesowego
	 * @return object
	 * 
	 */
	public function load($data=array());
	
	/**
	 * Zwraca tablice asocjacyjna nazw pol tabeli
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getFieldsNames();
	
	
	/**
	 * Zwraca obiekt kolekcji
	 * 
	 * @access public
	 * @return object
	 * 
	 */	
	public function getCollection();
	
	/**
	 * Zwraca nazwe klasy obslugiwanego obiektu
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getEntityClassName();
	
	/**
	 * Zwraca nazwe tabeli
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getEntityTableName();
	
	/**
	 * Zwraca nazwe pola tabeli bedaca kluczem glownym
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getEntityPkColumnName();
	
	/**
	 * Zwraca nazwe pola tabeli z data utworzenie obiektu
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getEntityCreatedColumnName();
	
	/**
	 * Zwraca nazwe pola tabeli z data aktualizacji obiektu
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getEntityUpdatedColumnName();
	
	/**
	 * Pokazuje czy uzywany jest czas lokalny
	 * 
	 * @access public
	 * @return bool
	 * 
	 */	
	public function getEntityUseLocalTime();
	
	/**
	 * Zwraca defnicje pol obiektu obslugiwanego przez ten mapper
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getFields();
	
	/**
	 * Zwraca definicje relacji dla obiektu obslugiwanego przez ten mapper
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getRelations();
	
	/**
	 * Zwraca identyfikator (nazwa mala litera) obiektu biznesowego
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getEntityName();
	
	/**
	 * Pobiera z bazy danych rekordy wg klucza glownego
	 * 
	 * @access public
	 * @param mixed Wartosc klucza glownego lub tablica wartosci kluczy glownych
	 * @return object
	 * 
	 */	
	public function get();
	
	/**
	 * Pobiera wszystkie rekordy z bazy danych, spelniajace warunek taki ze pole, 
	 * ktorego nazwa jest pierwszym argumentem funkcji, zawiera obiekty podane jako drugi argument funkcji np getBy('imie', 'przemek')
	 * pobierze wszystkie rekordy w korych pole imie rowne jest przemek
	 * 
	 * @access public
	 * @param string Nazwa pola
	 * @param mixed Wartosc pola
	 * @param array Fragmenty zapytania
	 * @param array Wartosci parametrow zapytania, nelezy je indeksowac stringami poprzedzonymi znakiem :, nie powinna to byc tablica indeksowana liczbami, bo pomiesza sie kolejnosc parametrow
	 * @return object
	 * 
	 */	
	public function getBy($what, $args, $queryParts=null, $params=null);
	
	/**
	 * Pobiera wszystkie rekordy z bazy danych, poprzez nazwe obiektu powiazanego 
	 * (jego nazwa jest pierwszym argumentem funkcji, jest to nazwa obiektu powiazanego, drugi argument to dwuelementowa tablica, 
	 * jej pierwszy element to nazwa pola powiazanego obiektu, a drugi to wartosc klucza glownego tego obiektu). 
	 * Majac taki obiekt mozna pobrac wszystkie obiekty z nim powiazane, a obslugiwane przez dany mapper, np.:
	 * getByRelatedGroup('name', 'goscie'); //pobierze wszystkie obiekty obslugiwane przez ten mapper a powiazane z obiektem group, o polu name majacym wartosc goscie
	 * 
	 * @access public
	 * @param string Nazwa powiazanego obiektu bieznesowego
	 * @param array Tablica dwuelementowa (nazwa pola, wartosc pola)
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */
	
	public function getByRelated($entityRelatedName, $args, $queryParts=null, $params=null);
	
	
	/**
	 * Pobiera z bazy danych wszystkie rekordy spelniajace warunki podane jako tabela w argumencie wywolania
	 * 
	 * @access public
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */		
	public function getAll($queryParts=array(), $params=array());
	
	/**
	 * Zwraca pierwszy obiekt z pobranych wynikow
	 * 
	 * @access public
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */			
	public function getFirst($queryParts=array(), $params=array());
	
	/**
	 * Zwraca ostatni obiekt z pobranych wynikow
	 * 
	 * @access public
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */			
	public function getLast($queryParts=array(), $params=array());
	
	/**
	 * Zwraca wszystkie obiekty, alias do getAll
	 * 
	 * @access public
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */		
	public function all($queryParts=array(), $params=array());
	
	
	/**
	 * Zwraca pierwszy obiekt z pobranych wynikow, alias do getFirst
	 * 
	 * @access public
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */		
	public function first($queryParts=array(), $params=array());
	
	
	/**
	 * Zwraca ostatni obiekt z pobranych wynikow, alias do getLast
	 * 
	 * @access public
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */		
	public function last($queryParts=array(), $params=array());
	
	/**
	 * Sprawdza, czy po weryfikcji danych pojawily sie bledy
	 * 
	 * @access public
	 * @return bool
	 * 
	 */		
	public function hasErrors();
	
	/**
	 * Zwraca bledy weryfikacji, jesli sa
	 * 
	 * @access public
	 * @return array
	 * 
	 */			
	public function getErrors();
	
	/**
	 * Czysci tablice bledow
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function clearErrors();
	
	/**
	 * Przeprowdza weryfikacje danych dla obiektu biznesowego, wywolywana publicznie
	 * 
	 * @access public
	 * @param array Tablica do walidacji
	 * @return bool
	 * 
	 */		
	public function validate($data);
	
	/**
	 * Zapisuje obiekt i obiekty z nim powiazane do bazy danych
	 * 
	 * @access public
	 * @param object Obiekt biznesowy obslugiwany przez danego mappera
	 * @param mixed Obiekt biznesowy powiazany z nim, jesli ujeto go w konfiguracji (lub tablica obiektow)
	 * @return object
	 * 
	 */	
	public function save(IEntity $entity, $entityRelated=null);
	
	/**
	 * Usuwa obiekt i obiekty z nim powiazane z bazy danych
	 * 
	 * @access public
	 * @param object Obiekt biznesowy obslugiwany przez daneg mappera
	 * @param object Obiekt biznesowy powiazany z nim (lub obiekty), jesli ujeto go w konfiguracji (lub tablica obiektow)
	 * @return void
	 * 
	 */	
	public function delete(IEntity $entity, $entityRelated=null);
	
	/**
	 * Wyszukuje obiekty pozostajace w relacji do danego (obslugiwane przez mapper podany w argumencie), obslugiwanego przez ten mapper i podanego przez wartosc klucza glownego,  
	 * 
	 * @access public
	 * @param object Obiekt mappera dla obiektu biznesowego pozostajacego w relacji do obslugiwanego przez ten aktualny mapper
	 * @param int Wartosc klucza glownego dla tego obiektu beda wyszukiwane obiekty powiazane
	 * @return object
	 * 
	 */		
	public function getRelated(DataMapperAbstract $entityRelatedDataMapper, $pk);
	 
	/**
	 * Usuwa relacje miedzy dwoma obiektami biznesowymi
	 * 
	 * @access public
	 * @param object Obiekt biznesowy
	 * @param object Obiekt biznesowy powiazany z pierwszym
	 * @return int
	 * 
	 */	
	public function deleteRelation(IEntity $entity, IEntity $entityRelated);
		
	/**
	 * Dodaje do bazy danych relacje miedzy dwoma obiektami biznesowymi
	 * 
	 * @access public
	 * @param object Obiekt biznesowy
	 * @param object Obiekt biznesowy powiazany z pierwszym
	 * @return int
	 * 
	 */		
	public function saveRelation(IEntity $entity, IEntity $entityRelated);
	
}

?>
