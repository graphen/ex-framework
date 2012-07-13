<?php

/**
 * @class DataMapperAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class DataMapperAbstract implements IDataMapper {
	
	/**
	 * Inflector (IInflector)
	 *
	 * @var object
	 * 
	 */		
	protected $_inflector = null;
	
	/**
	 * Obiekt budujacy zapytanie (IQuery)
	 *
	 * @var object
	 * 
	 */		
	protected $_query = null;
	
	/**
	 * Obiekt dostepu do bazy danych (IDb)
	 *
	 * @var object
	 * 
	 */		
	protected $_db = null;
	
	/**
	 * Obiekt kolekcji dla obiektow biznesowych
	 *
	 * @var object
	 * 
	 */		
	protected $_collection = null;
	
	/**
	 * Obiekt fabryczny tworzacy obiekty mapperow (IFactory)
	 *
	 * @var object
	 * 
	 */	
	protected $_mapperFactory = null;
	
	/**
	 * Obiekt fabryczny tworzacy obiekty biznesowe (IFactory)
	 *
	 * @var object
	 * 
	 */		
	protected $_entityFactory = null;

	/**
	 * Obiekt walidatora dla obiektow biznesowych (IValidatorInput)
	 *
	 * @var object
	 * 
	 */		
	protected $_inputValidator = null;

	/**
	 * Obiekt filtra dla obiektow biznesowych (IFilterInput)
	 *
	 * @var object
	 * 
	 */		
	protected $_inputFilter = null;

	/**
	 * Nazwa klasy obslugiwanego obiektu biznesowego. Pole musi zostac nadpisane lub ustawione w obiekcie mappera
	 *
	 * @var string
	 * 
	 */		
	protected $_entityClassName = '';
	
	/**
	 * Nazwa obslugiwanej tabeli dla obiektu biznesowego w bazie danych. Pole musi zostac nadpisane lub ustawione w obiekcie mappera
	 *
	 * @var string
	 * 
	 */			
	protected $_entityTableName = '';
	
	/**
	 * Nazwa obslugiwanego obiektu biznesowego. Pole musi zostac nadpisane lub ustawione w obiekcie mappera
	 *
	 * @var string
	 * 
	 */			
	protected $_entityName = '';
	
	/**
	 * Nazwa pola bedacego kluczem glownym. Domyslnie id. Moze zostac nadpisane w klasie potomnej
	 *
	 * @var string
	 * 
	 */		
	protected $_entityPkColumnName = 'id';
	
	/**
	 * Nazwa pola zawierajacego date utworzenia obiektu. Mozna nadpisac w klasie potomnej. Jesli zostanie podane i istnieje w tabeli moze byc automatycznie aktualizowane.
	 *
	 * @var string
	 * 
	 */		
	protected $_entityCreatedColumnName = 'created';
	
	/**
	 * Nazwa pola zawierajacego date aktualizacji  obiektu. Mozna nadpisac w klasie potomnej. Jesli zostanie podane i istnieje w tabeli moze byc automatycznie aktualizowane.
	 *
	 * @var string
	 * 
	 */	
	protected $_entityUpdatedColumnName = 'updated';
	
	/**
	 * Czy uzyc czasu lokalnego do aktualizacji automatycznych pol czasu
	 *
	 * @var bool
	 * 
	 */		
	protected $_entityUseLocalTime = true;
	
	/**
	 * Tabela definicji pol obslugiwanej tabeli w bazie danych. Musi zostac nadpisana w klasie potomnej
	 *
	 * @var array
	 * 
	 */		
	protected $_fields = array();

	/**
	 * Tabela definicji pol wirtualnych, nieobecnych w bazie, ale potrzebnych podczas weryfikacji
	 *
	 * @var array
	 * 
	 */		
	protected $_virtualFields = array();
	
	/**
	 * Tabela definicji relacji z innymi tabelami w bazie danych. Musi zostac nadpisana w klasie potomnej
	 *
	 * @var array
	 * 
	 */			
	protected $_relations = array();

	/**
	 * Reguly walidacji dla UPDATE i INSERT
	 *
	 * @var array
	 * 
	 */			
	protected $_validatorRules = array();

	/**
	 * Reguly walidacji dla scenariusza INSERT
	 *
	 * @var array
	 * 
	 */			
	protected $_insertValidatorRules = array();
	
	/**
	 * Reguly walidacji dla scenariusza UPDATE
	 *
	 * @var array
	 * 
	 */			
	protected $_updateValidatorRules = array();	
	
	/**
	 * Reguly filtrowania, dla filtrow wykonywanych przed walidacja
	 *
	 * @var array
	 * 
	 */	
	protected $_preValidationFilterRules = array();
	
	/**
	 * Reguly filtrowania, dla filtrow wykonywanych po walidacji
	 *
	 * @var array
	 * 
	 */	
	protected $_postValidationFilterRules = array();
	
	/*
	 * Przyklad zawartosci klasy potomnej mappera
	 * 
	
	protected $_entityClassName = 'User'; //
	protected $_entityTableName = 'users';
	protected $_entityName = 'user';  //
	
	protected $_fields = array(
		'id'=>array('nameInObj'=>'id','type'=>'int', 'primary'=>true, default=>'NULL'),
		'user_name'=>array('nameInObj'=>'userName','type'=>'string', 'primary'=>false, default=>'NULL'),
		'password'=>array('nameInObj'=>'password', 'type'=>'string', 'primary'=>false, default=>'')
	);
	
	protected $_virtualFields = array(
		'password_confirmation'=>array('nameInObj'=>'passwordConfirmation')
	);
	
	protected $_relations = array(
		'comments'=>array('nameInObj'=>'comments', 'relation'=>'hasMany', 'mapper'=>'CommentsDataMapper', 'class'=>'Comment', 'rtablename'=>'comments_users'),
		'places'=>array('nameInObj'=>'places', 'relation'=>'hasOne', 'mapper'=>'PlacesDataMapper', 'class'=>'Place', 'rtablename'=>'places_users'),
		'phones'=>array('nameInObj'=>'phones', 'relation'=>'hasMany', 'mapper'=>'PhonesDataMapper', 'class'=>'Phone', 'rtablename'=>'', 'rtable'=>'other', 'efkey'=>'user_id')
		'pesel'=>array('nameInObj'=>'pesels', 'relation'=>'hasOne', 'mapper'=>'PeselsDataMapper', 'class'=>'Pesel', 'rtablename'=>'', 'rtable'=>'same', 'erfkey'=>'pesel_id')

	);
	
	protected $_preValidationFilterRules = array(
		'user_name'=>array('FilterXss', 'FilterStringTrim', 'FilterStripEndLines'), 
		'password'=>array('FilterXss', 'FilterStringTrim', 'FilterStripEndLines')
	);
	
	protected $_postValidationFilterRules = array(
		'password'=>array('FilterMd5')	 
	);
	
	protected $_validatorRules = array(
		'user_age'=>array('ValidatorInteger', array('ValidatorMaxInteger', array('maxValue'=>130))),
		'user_phone'=>array('ValidatorCellPhone', 'default'=>'000000000', 'allowEmpty'=>true, 'options'=>array( 'notEmptyMessage'=>'A non-empty value is required for field')),
		'user_name'=>array(array('ValidatorStringMaxLength', array('maxLength'=>50)), 'presence'=>'required'),
		'user_email'=>array('ValidatorEmail', 'breakChainOnFailure' => true),
		'user_pass'=>array(array('ValidatorStringMinLength', array('minLength'=>7))),
		'user_pass2'=>array('ValidatorStringEquals', 'fields'=>array('user_pass1', 'user_pass2'));
	);	 
	
	protected $_insertValidatorRules = array();
	protected $_updateValidatorRules = array();
	 
	*
	*/
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Fabryka obiektow mapperow
	 * @param object Fabryka obiektow biznesowych
	 * @param object Obiekt kolekcji
	 * @param object Obiekt dostepu do bazy danych impl. IDb
	 * @param object Obiekt budujacy zapytanie
	 * @param object Obiekt inflekcji
	 * 
	 */	
	public function __construct(IFactory $mapperFactory, IFactory $entityFactory, ICollection $collection, IDb $db, IQuery $query, IInflector $inflector, IValidatorInput $inputValidator, IFilterInput $inputFilter) {
		$this->_mapperFactory = $mapperFactory;
		$this->_entityFactory = $entityFactory;
		$this->_collection = $collection;
		$this->_db = $db;
		$this->_query = $query;
		$this->_inflector = $inflector;
		$this->_inputValidator = $inputValidator;
		$this->_inputFilter = $inputFilter;
		if(empty($this->_entityPkColumnName)) {
			foreach($this->_fields AS $fieldName=>$fieldDefinition) {
				if((isset($fieldDefinition['primary']) && ($fieldDefinition['default'] == true))) {
					$this->_entityPkColumnName = $fieldName;
					break;
				}
			}
		}
		if(empty($this->_entityClassName)) {
			$this->_entityClassName = $this->_inflector->makeEntityClassNameFromMapperClassName(get_class($this)); //pobranie nazwy klasy entity/pojedynczego obiektu, tutaj nazwa tabeli w l. pojedynczej
		}
		if(empty($this->_entityTableName)) {
			$this->_entityTableName = $this->_inflector->makeEntityTableNameFromEntityClassName($this->_entityClassName); //pobranie nazwy tabeli
		}		
		if(empty($this->_entityName)) {
			$this->_entityName = $this->_inflector->makeEntityNameFromEntityClassName($this->_entityClassName); //pobranie nazwy entity/pojedynczego obiektu, tutaj nazwa tabeli w l. pojedynczej, mala litera
		}
	}
	
	/**
	 * Metoda magiczna, wywolywana w przypadku nieznalezienia funkcji w obiekcie tej klasy
	 * Wywoluje funkcje pobierajace wszystkie rekordy z bazy danych, spelniajace warunek taki ze pole, 
	 * ktorego nazwa jest czascia nazwy funkcji zawiera obiekty podane jako argument funkcji np getByName('przemek')
	 * pobierze wszystkie rekordy w korych pole imie rowne jest przemek
	 * 
	 * @access public
	 * @param string Nazwa funkcji
	 * @param array Argumenty funkcji
	 * @return mixed
	 * 
	 */	
	public function __call($functionName, $args) {
		/*
		 *
		if($functionName == 'select' || $functionName == 'from' || $functionName == 'group' || $functionName == 'order' || $functionName == 'join' || $functionName == 'where' || $functionName == 'having' || $functionName == 'limit' || $functionName == 'offset') {
			$this->_query->{$functionName}($args);	
			return $this;
		}
		elseif($functionName == 'query') {
			return $this->_query->query();
		}
		* 
		*/
		if(strstr($functionName, 'getByRelated')) {
			$functionName = str_replace('getByRelated', '', $functionName);
			$field = $args[0];
			$value = $args[1];
			$queryParts = isset($args[2]) ? $args[2] : null;
			$params = isset($args[3]) ? $args[3] : null;			
			return $this->getByRelated($functionName, array($field, $value), $queryParts, $params);
		}		 
		if(strstr($functionName, 'getBy')) {
			$functionName = str_replace('getBy', '', $functionName);
			$value = $args[0];
			$queryParts = isset($args[1]) ? $args[1] : null;
			$params = isset($args[2]) ? $args[2] : null;
			return $this->getBy($functionName, $value, $queryParts, $params);
		}
	}
	
	/*
	 *******************************************************************
	 * Tworzenie obiektow 
	 ******************************************************************* 
	 */	
	 
	/**
	 * Tworzy nowy obiekt biznesowy 
	 * 
	 * @access public
	 * @param array Wartosci pol nowo tworzonego obiektu biznesowego
	 * @return object
	 * 
	 */
	public function create($data=array()) {
		$entity = $this->_entityFactory->create($this->_entityClassName); //utworzenie pustego obiektu
		foreach($this->_fields AS $fieldDefinition) { //przygotowanie pustych pol w obiekcie
			$entity->{$fieldDefinition['nameInObj']} = (isset($fieldDefinition['default'])) ? $fieldDefinition['default'] : '';
		}
		foreach($this->_virtualFields AS $virtualFieldDefinition) { //przygotowanie pustych pol wirtualnych w obiekcie
			$entity->{$virtualFieldDefinition['nameInObj']} = '';
		}
		foreach($data AS $index=>$value) { //jesli jakies wartosci przekazano jako argument zostana zaladowane do obiektu, bez mapowania nazw, nazwy w przekazanej tablicy maja odpowiadac tym w obiekcie
			$entity->{$index} = $value;
		}
		foreach($this->_relations AS $relationDefinition) { //zainicjowane zostana pola dla kolekcji obiektow pozostajacych w relacji z danym
			$entity->{$relationDefinition['nameInObj']} = array();
		} 
		//obiekt zostal w tym momencie juz automatycznie oznaczony jako zmodyfikowany i do walidacji (w metodzie __set obiektu Entity)
		$entity->created(true); //jeszcze oznaczamy go jako nowy
		return $entity; //tak utworzony obiekt zostanie zwrocony
	}
	
	/**
	 * Tworzy obiekt biznesowy i laduje go danymi z bazy danych
	 * 
	 * @access public
	 * @param array Wartosci pol z bazy danych ladowane do obiektu biznesowego
	 * @return object
	 * 
	 */
	public function load($data=array()) {
		$entity = $this->_entityFactory->create($this->_entityClassName); //utworzenie pustego obiektu
		$entityPkColumnName = $this->_entityPkColumnName; //nazwa klucza glownego tabeli w bazie
		$entityPk = isset($data[$entityPkColumnName]) ? $data[$entityPkColumnName] : ''; //identyfikator obiektu bedzie potrzebny do pobrania obiektow pozostajacych w relacji
		if($entityPk === null) { //obiekt bedzie tylko do odczytu, bo nie zostaly pobrane wszystki pola, szczegolnie id
			$entity->readOnly(true);
		}
		
		foreach($this->_fields AS $fieldName=>$fieldDefinition) { //wypelnienie obiektu danymi z bazy lub przypisanie pustych wartosci kiedy czegos brak
			$fieldValue = isset($data[$fieldName]) ? $data[$fieldName] : null; //uzywam mapowania nazw
			//if(isset($fieldDefinition['phpType'])) {
				;//tu mapowanie typow dorobic
			//}
			$entity->{$fieldDefinition['nameInObj']} = $fieldValue;
		}
		foreach($this->_virtualFields AS $virtualFieldDefinition) { //przygotowanie pustych pol wirtualnych w obiekcie
			$entity->{$virtualFieldDefinition['nameInObj']} = '';
		}
		///////////////////////tutaj pasuje zrobic mapowanie danych jesli chodzi o typy
		$entity = $this->initRelations($entity, $entityPk); //inicjowanie pol, ktore beda zawierac kolekcje obiektow pozostajacych w relacji do danego
		//obiekt zostal juz automatycznie oznaczony jako zmodyfikowany i do walidacji, podczas uzywania metody__set obiektu Entity, co nie jest tutaj pozadane, dlatego:
		$entity->modified(false); //obiekt zostal zaladowany ale nie zmodyfikowany jeszcze, wiec oznaczam jako nie modyfikowany
		$entity->created(false); //natomiast nie jest to obiekt nowy
		$entity->updateOld(); //aktualne wartosci pol zostana zachowane, jako kopia, aby wiadomo bylo pozniej, np podczas walidacji, co zostalo zmodyfikowane, inaczej trzeba byloby walidowac wszystkie pola
		$entity->resetModifiedFields(); //zresetowanie indeksow pol zmodyfikowanych		//////////////////////sprawdzic dodane 2011.07.30 czy nie zresetowac tutaj wartosci zmodyfikowanych w obiekcie entity, przeciez ta tablica zawiera teraz wszystkie nazwy pol, a nie zpstaly one zmodyfikowane
		return $entity; //tak utworzony obiekt zostanie zwrocony
	}
	
	/*
	 *******************************************************************
	 * Gettery i settery 
	 *******************************************************************
	 */
	
	/**
	 * Zwraca tablice asocjacyjna nazw pol tabeli
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getFieldsNames() {
		$names = array();
		foreach($this->_fields AS $fieldName=>$def) {
			//$names[$fieldName] = $def['nameInObj'];
			$names[$def['nameInObj']] = $def['nameInObj'];
		}
		return $names;		
		
	}
	
	/**
	 * Zwraca tablice asocjacyjna nazw pol wirtualnych
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getVirtualFieldsNames() {
		$names = array();
		foreach($this->_virtualFields AS $relationName=>$def) {
			//$names[$relationName] = $def['nameInObj'];
			$names[$def['nameInObj']] = $def['nameInObj'];
		}
		return $names;		
		
	}	
	
	/**
	 * Zwraca tablice asocjacyjna nazw relacji
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getRelationsNames() {
		$names = array();
		foreach($this->_relations AS $relationName=>$def) {
			//$names[$relationName] = $def['nameInObj'];
			$names[$def['nameInObj']] = $def['nameInObj'];
		}
		return $names;		
		
	}	
	
	/**
	 * Zwraca obiekt kolekcji
	 * 
	 * @access public
	 * @return object
	 * 
	 */	
	public function getCollection() {
		return $this->_collection;
	}
	
	/**
	 * Zwraca nazwe klasy obslugiwanego obiektu
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getEntityClassName() {
		return $this->_entityClassName;
	}
	
	/**
	 * Zwraca nazwe tabeli
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getEntityTableName() {
		return $this->_entityTableName;
	}
	
	/**
	 * Zwraca nazwe pola tabeli bedaca kluczem glownym
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getEntityPkColumnName() {
		return $this->_entityPkColumnName;
	}
	
	/**
	 * Zwraca nazwe pola tabeli z data utworzenie obiektu
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getEntityCreatedColumnName() {
		return $this->_entityCreatedColumnName;
	}
	
	/**
	 * Zwraca nazwe pola tabeli z data aktualizacji obiektu
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getEntityUpdatedColumnName() {
		return $this->_entityUpdatedColumnName;
	}
	
	/**
	 * Pokazuje czy uzywany jest czas lokalny
	 * 
	 * @access public
	 * @return bool
	 * 
	 */	
	public function getEntityUseLocalTime() {
		return $this->_entityUseLocalTime;
	}
	
	/**
	 * Zwraca defnicje pol obiektu obslugiwanego przez ten mapper
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getFields() {
		return $this->_fields;
	}
	
	/**
	 * Zwraca definicje relacji dla obiektu obslugiwanego przez ten mapper
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getRelations() {
		return $this->_relations;
	}
	
	/**
	 * Zwraca identyfikator (nazwa mala litera) obiektu biznesowego
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getEntityName() {
		return $this->_entityName;
	}
	
	/*
	 *******************************************************************
	 * Pobieranie danych 
	 *******************************************************************
	 */
	
	/**
	 * Pobiera z bazy danych rekordy wg klucza glownego
	 * 
	 * @access public
	 * @param mixed Wartosc klucza glownego lub tablica wartosci kluczy glownych
	 * @return object
	 * 
	 */	
	public function get() {
		$args = func_get_args();
		if(count($args) == 0) {
			return $this->create();
		}
		else {
			$entityPkColumnName = $this->getEntityPkColumnName();
			return $this->getBy($entityPkColumnName, $args);
		}
	
	}
	
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
	public function getBy($what, $args, $queryParts=null, $params=null) {
		$what = lcfirst($what); //nazwa pola po ktorym bedzie prowadzone szukanie
		$entityPkColumnName = $this->_entityPkColumnName; //pobieram nazwe klucza gl w tabeli
		if($what == 'id') { //jesli w funkcji szukano po id, nie wiadomo czy nazwa klucza glownego jest wlasnie taka stad podmiana
			$what = $entityPkColumnName;
		}
		else {
			$exists = false; //szukana nazwa moze nie istniec jako pole w tabeli
			foreach($this->_fields AS $fieldName=>$fieldDefinition) { //w innym przypadku trzeba zmapowac poszukiwana nazwe pola obiektu na pole nazwe pola tabeli
				if($fieldDefinition['nameInObj'] == $what) {
					$what = $fieldName; //odnaleziono nazwe pola w bazie danych
					$exists = true;
					break;
				}
			}
			if($exists === false) { //jeslii nie istnieje zglaszam wyjatek, ine wiadomo czgo szukano
				throw new DataMapperException('Szukane pole nie istnieje w tabeli bazy danych');
			}
		}
		//tymczasowe tablice i zmienne dla utworzenia odpowiedniego zapytania
		$whatArr = array();
		$whereStr = '';
		
		if(is_array($args) && count($args) > 0) {
			foreach($args AS $index => $value) {
				$newIndex = ':' . $what . '_' . $index;
				$whatArr[$newIndex] = $value;
			}

			$whatStr = '';
			$whatArrKeys = array_keys($whatArr);
			$whatStr = implode(', ', $whatArrKeys);
			$whereStr = "`". $what . "` IN( " . $whatStr . ")"; //przygotowany kawalek zapytania dla klauzuli WHERE
			
		}
		elseif(!is_array($args)) {
			$whatArr = array(":$what" => $args);
			$whereStr = "`" . $what . "` = :" . $what; //przygotowany kawalek zapytania dla WHERE
			
		}
		
		$tmpArray = array(); //jesli w argumencie funkcji przeslano juz kawalki zapytania dla WHERE, trzeba je polaczyc
		if(isset($queryParts['where'])) {
			if(is_array($queryParts['where'])) {
				$queryParts['where'][] = $whereStr;
			}
			else {
				$tmpArray = array($queryParts, $whereStr);
				$queryParts['where'] = $tmpArray;
			}
		}
		else {
			$queryParts['where'] = array($whereStr);
		}
		
		if(isset($params)) { //podobnie trzeba polaczyc wartosci parametrow
			if(is_array($params)) {
				$params = array_merge($params, $whatArr);
			}
			else {
				$params = $whatArr;
			}
		}
		else {
			$params = $whatArr;
		}

		return $this->getAll($queryParts, $params);		
	}
	
	/**
	 * Pobiera wszystkie rekordy z bazy danych, poprzez nazwe obiektu powiazanego 
	 * (jego nazwa jest pierwszym argumentem funkcji, jest to nazwa obiektu powiazanego, drugi argument to dwuelementowa tablica, 
	 * jej pierwszy element to nazwa pola powiazanego obiektu, a drugi to wartosc klucza glownego tego obiektu). 
	 * Majac taki obiekt mozna pobrac wszystkie obiekty z nim powiazane, a obslugiwane przez dany mapper, np.:
	 * getByRelatedGroup('name', 'goscie'); //pobierze wszystkie obiekty obslugiwane przez ten mapper a powiazane z obiektem group, o polu name majacym wartosc goscie
	 * 
	 * @access public
	 * @param string Nazwa powiazanego obiektu bieznesowego
	 * @param mixed Wartosc klucza glownego tego obiektu
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */
	
	public function getByRelated($entityRelatedName, $args, $queryParts=null, $params=null) {
		$field = $args[0];
		$fieldValue = (is_array($args[1])) ? $args[1][0] : $args[1];
		$entityRelatedName = lcfirst($entityRelatedName);
		$entitiesRelatedName = $this->_inflector->pluralize($entityRelatedName);
		if(isset($this->_relations[$entitiesRelatedName])) {
			if(isset($this->_relations[$entitiesRelatedName]['mapper'])) {
				$relatedMapper = $this->_mapperFactory->create($this->_relations[$entitiesRelatedName]['mapper']);
				$entityRelatedCollection = $relatedMapper->getBy($field, $fieldValue, $queryParts);
				if(count($entityRelatedCollection) == 0) {
					return array();
				}
				$entityRelated = $entityRelatedCollection[0];
				$entitiesName = $this->_inflector->pluralize($this->_entityName);
				
				if($entityRelated->{$entitiesName} instanceof RelationMapper) {
					$collection = $entityRelated->{$entitiesName}->getCollection();
					return $collection;
					exit();
				}
				else {
					return array();
				}
			}
			else {
				throw new DataMapperException('Brak zdefiniowanego mappera dla zadanego obiektu');
			}
		}
		else {
			throw new DataMapperException('Nie zdefiniowano powiazan miedzy zadanymi obiektami');
		} 
	}	
	
	
	/**
	 * Pobiera z bazy danych wszystkie rekordy spelniajace warunki podane jako tabela w argumencie wywolania
	 * 
	 * @access public
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */		
	public function getAll($queryParts=array(), $params=array()) {
		$entityPkColumnName = $this->_entityPkColumnName;
		$entityTableName = "`" . $this->_entityTableName . "`";
		
		$this->_query->from($entityTableName);
		if(isset($queryParts['from'])) {
			$this->_query->from($queryParts['from']);
		}
		if(isset($queryParts['join'])) {
			$this->_query->join($queryParts['join']);
		}		
		if(isset($queryParts['select'])) {
			$this->_query->select($queryParts['select']);
		}
		if(isset($queryParts['where'])) {
			$this->_query->where($queryParts['where']);
		}
		if(isset($queryParts['limit'])) {
			$this->_query->limit($queryParts['limit']);
		}
		if(isset($queryParts['offset'])) {
			$this->_query->offset($queryParts['offset']);
		}
		if(isset($queryParts['order'])) {
			$this->_query->order($queryParts['order']);
		}
		if(isset($queryParts['group'])) {
			$this->_query->group($queryParts['group']);
		}
		if(isset($queryParts['having'])) {
			$this->_query->having($queryParts['having']);
		}	
		
		$parameters = array();
		if((is_array($params)) && (count($params) > 0)) {
			$parameters = $params; //jesli nie bedzie parametrow zostanie wyslana pusta tablica do funkcji
		}
		$query = $this->_query->query();
		$result = $this->_db->prepare($query); //wyslanie zapytania do serwera bazy danych
		$result->execute($parameters); //wykonanie zapytania z tablica parametrow
		$data = $result->fetchAll(); //pobranie wynikow
		$this->_collection->clear(); //wyczyszczenie kolekcji
		foreach($data AS $row) {
			$entity = $this->load($row); //tworzenie obiektow biznesowych i wypelniane ich danymi
			if(isset($queryParts['select'])) { //jesli wybrano konkretne pola, obiekty beda tylko do odczytu,!!! nie sparwdzam czy wybranow * wtedy nie trzeba byloby robic obiekt tylko do odczytu
				$entity->readOnly(true);
			}
			$this->_collection->add($entity); //dodanie obiektu do kolekcji
		}
		return clone $this->_collection; //musi zostac zwrocona nawet pusta kolekcja //clone 2011.08.24
	}
	
	/**
	 * Zwraca pierwszy obiekt z pobranych wynikow
	 * 
	 * @access public
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */			
	public function getFirst($queryParts=array(), $params=array()) {
		$queryParts['limit'] = 1;
		$collection = $this->getAll($queryParts, $params);
		$collection->rewind();
		return $collection->current();
	}
	
	/**
	 * Zwraca ostatni obiekt z pobranych wynikow
	 * 
	 * @access public
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */			
	public function getLast($queryParts=array(), $params=array()) {
		$collection = $this->getAll($queryParts, $params);
		$lastEntityIndex = count($collection);
		return $collection[$lastEntityIndex];
	}	
	
	/**
	 * Zwraca wszystkie obiekty, alias do getAll
	 * 
	 * @access public
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */		
	public function all($queryParts=array(), $params=array()) {
		return $this->getAll($queryParts, $params);
	}
	
	
	/**
	 * Zwraca pierwszy obiekt z pobranych wynikow, alias do getFirst
	 * 
	 * @access public
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */		
	public function first($queryParts=array(), $params=array()) {
		return $this->getFirst($queryParts, $params);
	}
	
	
	/**
	 * Zwraca ostatni obiekt z pobranych wynikow, alias do getLast
	 * 
	 * @access public
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return object
	 * 
	 */		
	public function last($queryParts=array(), $params=array()) {
		return $this->getLast($queryParts, $params);
	}
	
	/**
	 * Pobiera z bazy ilosc rekordow spelniajacych zalozenia
	 * 
	 * @access public
	 * @param array Tablica fragmentow zapytania
	 * @param array Tablica asocjacyjna wartosci parametrow powiazanych z ich identyfikatorami  
	 * @return int
	 * 
	 */		
	public function countAll($queryParts=array(), $params=array()) {
		$entityPkColumnName = $this->_entityPkColumnName;
		$entityTableName = "`" . $this->_entityTableName . "`";
		
		$this->_query->select("COUNT(*) AS count_all");
		if(isset($queryParts['select'])) {
			unset($queryParts['select']);
		}
		$this->_query->from($entityTableName);
		if(isset($queryParts['from'])) {
			$this->_query->from($queryParts['from']);
		}
		if(isset($queryParts['join'])) {
			$this->_query->join($queryParts['join']);
		}
		if(isset($queryParts['where'])) {
			$this->_query->where($queryParts['where']);
		}
		if(isset($queryParts['limit'])) {
			$this->_query->limit($queryParts['limit']);
		}
		if(isset($queryParts['offset'])) {
			$this->_query->offset($queryParts['offset']);
		}
		if(isset($queryParts['order'])) {
			$this->_query->order($queryParts['order']);
		}
		if(isset($queryParts['group'])) {
			$this->_query->group($queryParts['group']);
		}
		if(isset($queryParts['having'])) {
			$this->_query->having($queryParts['having']);
		}	
		
		$parameters = array();
		if((is_array($params)) && (count($params) > 0)) {
			$parameters = $params; //jesli nie bedzie parametrow zostanie wyslana pusta tablica do funkcji
		}
		$query = $this->_query->query();
		$result = $this->_db->prepare($query);
		$result->execute($parameters);
		$data = $result->fetchAll();
		return $data[0]['count_all'];
	}


	/*
	 *******************************************************************
	 * Walidacja
	 *******************************************************************
	 */
	
	/**
	 * Sprawdza, czy po weryfikcji danych pojawily sie bledy
	 * 
	 * @access public
	 * @return bool
	 * 
	 */		
	public function hasErrors() {
		return $this->_inputValidator->hasErrors();
	}
	
	/**
	 * Zwraca bledy weryfikacji, jesli sa
	 * 
	 * @access public
	 * @return array
	 * 
	 */			
	public function getErrors() {
		return $this->_inputValidator->getErrors();
	}
	
	/**
	 * Czysci tablice bledow
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function clearErrors() {
		$this->_inputValidator->reset();
	}
	
	/**
	 * Przeprowdza weryfikacje obiektu biznesowego, wywolywana wewnetrznie
	 * 
	 * @access protected
	 * @param string Id
	 * @param array Tablica do walidacji
	 * @param string Scenariusz [update|insert]
	 * @return bool
	 * 
	 */		
	protected function isValid($id, $data, $scenario=null) {
		$validatorRules = $this->_validatorRules;
		if($scenario == 'update') {
			$validatorRules = array_merge($validatorRules, $this->_updateValidatorRules);
		}
		if($scenario == 'insert') {
			$validatorRules = array_merge($validatorRules, $this->_insertValidatorRules);
		}
		$this->clearErrors();
		$this->_inputFilter->setRules($this->_preValidationFilterRules);
		$data = $this->_inputFilter->filter($data);
		$this->_inputValidator->setRules($validatorRules);
		$result = $this->_inputValidator->isValid($id, $data);
		return $result;
	}
	
	/**
	 * Przeprowdza weryfikacje danych dla obiektu biznesowego, wywolywana publicznie
	 * 
	 * @access public
	 * @param array Tablica do walidacji
	 * @param string Scenariusz [update|insert]
	 * @return bool
	 * 
	 */		
	public function validate($data, $scenario=null) {
		$id = md5(time());
		return $this->isValid($id, $data, $scenario);
	}	
	
	/*
	 *******************************************************************
	 * Zapisywanie i kasowanie danych
	 *******************************************************************
	 */
	
	/**
	 * Zapisuje obiekt i obiekty z nim powiazane do bazy danych
	 * 
	 * @access public
	 * @param object Obiekt biznesowy obslugiwany przez danego mappera
	 * @param mixed Obiekt biznesowy powiazany z nim, jesli ujeto go w konfiguracji (lub tablica obiektow)
	 * @return object
	 * 
	 */	
	public function save(IEntity $entity, $entityRelated=null) {
		$entityClassName = $this->_entityClassName;
		$entityTableName = $this->_entityTableName;
		
		if(!$entity instanceof $entityClassName) { //rzucony wyjatek w przypadku kiedy pierwszy arg. nie jest obiektem obslugiwanym przez ten mapper
			throw new DataMapperException('Obiekt nie jest instancja wymaganej klasy: ' . $entityClassName);
		}
		if($entity->isReadOnly()) { //jesli obiekt tylko do odczytu to nie ma mozliwosci zapisania go
			throw new DataMapperException('Obiekt jest tylko od odczytu i nie moze zostac zapisany');
		}		
		if($entity->isDeleted()) { //jesli obiekt jest skasowany to nie ma mozliwosci zapisania go
			throw new DataMapperException('Obiekt zostal usuniety z bazy i nie moze zostac ponownie zapisany');
		}
		if($entity->isNew() || $entity->isModified()) { //jesli obiekt nowy lub zmodyfikowany		
			$allFieldsData = $entity->toArray(); //pobieram zawartosc obiektu jako tablice, sa to wszystkie pola jakie beda zapisane w bazie, pola wirtualne i pola bedace odnosnikami do obiektow pozostajacych w relacji z danym
			$modifiedFields = $entity->getModifiedFields(); //pobieram zmodyfikowane nazwy pol
			/*				
			$dataToValidation = array(); //tablica z polami do walidacji, tylko te beda sprawdzane, ktore zostaly dodane
			foreach($modifiedFields AS $fieldName) { //przebiegam przez wszystkie zmodyfikowane nazwy pol i dodaje zmodyfikowane pola do tablicy podlegajacej walidacji
				if(isset($allFieldsData[$fieldName])) {
					$dataToValidation[$fieldName] = $allFieldsData[$fieldName];
				}
			}*/
			if($entity->isValid() != true) {
				$scenario = null;
				if($entity->isNew()) {
					$scenario = 'insert';
				}
				else {
					$scenario = 'update';
				}
				$result = $this->isValid('[EntityObject]', $allFieldsData, $scenario); //przeprowadzenie walidacji; walidator waliduje wszystki pola, dla jakich reguly zostaly zdefiniowane, nie tylko dla pol zmodyfikowanych. Tak dziala ValidatorInput, moznaby dolozyc parametr aby ignorowal reguly dla pol niemodyfikowanych
			}
			if($this->hasErrors() === false) { //jesli brak bledow
				$timestamp = ($this->_entityUseLocalTime == true) ? date("Y-m-d H:i:s") : gmdate("Y-m-d H:i:s"); //Ponizej automatyczna obsluga pol z znacznikami czasowymi wskazujacymi na czas utworzenia obiektu lub jego aktualizacji
				$entityCreatedColumnName = $this->_entityCreatedColumnName;
				$entityUpdatedColumnName = $this->_entityUpdatedColumnName;
				
				if(isset($this->_fields[$entityCreatedColumnName])) {
					if((isset($this->_fields[$entityCreatedColumnName]['default'])) && ($entity->{$this->_fields[$entityCreatedColumnName]['nameInObj']} == $this->_fields[$entityCreatedColumnName]['default'])) {
						$entity->{$this->_fields[$entityCreatedColumnName]['nameInObj']} = $timestamp;
					}
					if(empty($entity->{$this->_fields[$entityCreatedColumnName]['nameInObj']})) {	
						$entity->{$this->_fields[$entityCreatedColumnName]['nameInObj']} = $timestamp;
					}
				}
				if(isset($this->_fields[$entityUpdatedColumnName])) {
					$entity->{$this->_fields[$entityUpdatedColumnName]['nameInObj']} = $timestamp;
				}
							
				$entityPkColumnName = $this->_entityPkColumnName;

				if($entity->isNew()) { //proba zapisu nowego obiektu - INSERT
					$fieldsNames = array();
					$fieldsValues = array();
					foreach($this->_fields AS $fieldName=>$fieldDefinition) {
						$fieldsNames[] = '`'.$fieldName.'`';

						//tutaj wypada zrobic dokladniejsze mapowanie wg typow
						$fieldsValues[$fieldName] = $entity->{$fieldDefinition['nameInObj']};
						
					}
					$this->_inputFilter->setRules($this->_postValidationFilterRules); //wykonanie filtrowania przed zapisem do bazy, w obiekcie dane pozostana niefiltrowane
					$fieldsValues = $this->_inputFilter->filter($fieldsValues);	
					foreach($fieldsValues AS $fieldName=>$fieldValue) {
						$fieldsValues[$fieldName] = $this->_db->quote($fieldValue); //zabezpieczenie wartosci zmiennych przed zapisem do bazy
					}
					foreach($this->_fields AS $fieldName=>$fieldDefinition) {
						if((($entity->{$fieldDefinition['nameInObj']}) == null) && (($entity->{$fieldDefinition['nameInObj']}) !== 0)) {
							if(($fieldName == $entityPkColumnName)) {
								if((isset($fieldDefinition['auto'])) && ($fieldDefinition['auto'] == true)) {
									$fieldsValues[$fieldName] = "NULL";
								}
							}
							else {
								$fieldsValues[$fieldName] = "NULL";
							}
						}
					}					
					$fieldsNames = implode(',', $fieldsNames);
					$fieldsValues = implode(',', $fieldsValues);
					$this->_db->beginTransaction();
					try {
						$query = "INSERT INTO `" . $entityTableName . "` (" . $fieldsNames . ") VALUES (" . $fieldsValues . ")";
						$result = $this->_db->exec($query);
					}
					catch(DbException $dbEx) {
						$this->_db->rollBack();
						throw $dbEx;
					}
					
					$lastId = $this->_db->lastInsertId();
					$this->_db->commit();
					if((!isset($this->_fields[$entityPkColumnName]['auto'])) || ((isset($this->_fields[$entityPkColumnName]['auto'])) && ($this->_fields[$entityPkColumnName]['auto'] == true))) {
						$entity->{$this->_fields[$entityPkColumnName]['nameInObj']} = $lastId;
					}
					$entity->valid(true);
					$entity->created(false);
					$entity->modified(false);
					$entity->resetModifiedFields(); //zresetowanie indeksow pol zmodyfikowanych////////2011.07.30					
					$entity->updateOld();
						
				}
				if($entity->isModified()) { //proba zapisu obiektu zmodyfikowanego - UPDATE
					$fieldsValues = array();
					$fieldsNamesValues = array();
					foreach($this->_fields AS $fieldName=>$fieldDefinition) { //wyciagniecie z obiektu tylko pol zmodyfikowanych
						if($entity->getOld($fieldDefinition['nameInObj']) == $entity->{$fieldDefinition['nameInObj']}) { //tutaj bedzie problem z pustymi wartosciami w starym obiekcie i wartosciami NULL w nowym
							continue;
						}
						if($entity->{$fieldDefinition['nameInObj']} === null) {
							$fieldsValues[$fieldName] = "NULL";
						}
						else {
							/////////////////////mapowanie typow do zrobienia
							$fieldsValues[$fieldName] = $entity->{$fieldDefinition['nameInObj']};
						}				
					}
					$this->_inputFilter->setRules($this->_postValidationFilterRules); //wykonanie filtrowania przed zapisem do bazy, w obiekcie dane pozostana niefiltrowane
					$fieldsValues = $this->_inputFilter->filter($fieldsValues);	
					foreach($fieldsValues AS $fieldName=>$fieldValue) {
						$fieldsValues[$fieldName] = $this->_db->quote($fieldValue); //zabezpieczenie wartosci zmiennych przed zapisem do bazy
					}

					foreach($fieldsValues AS $fieldName=>$fieldValue) {
						$fieldsNamesValues[] = "`" . $fieldName . "` = " . $fieldValue; //tworzenie ciagu do zapytania zapisujacego
					}
					$fieldsNamesValues = implode(',', $fieldsNamesValues);
					if(!empty($fieldsNamesValues)) { //jesli zadne pole nie zostalo zmienione, a nastepuje proba ponownego zapisu opuszczamy ja
						$this->_db->beginTransaction();
						try {
							$query = "UPDATE `" . $entityTableName . "` SET " . $fieldsNamesValues . " WHERE `" . $entityPkColumnName . "` = '" . $entity->{$this->_fields[$entityPkColumnName]['nameInObj']} . "' LIMIT 1";
							$result = $this->_db->exec($query);
						}
						catch(DbException $dbEx) {
							$this->_db->rollBack();
							throw $dbEx;
						}
						$this->_db->commit();
					}
					$entity->valid(true);
					$entity->created(false);
					$entity->modified(false);
					$entity->updateOld();
					$entity->resetModifiedFields(); //zresetowanie indeksow pol zmodyfikowanych
				}
			}
			else {
				return false;
			}	
		}
			
		//Ponizej zapisywanie relacji z obiektem pozostajacym w relacji
		if((!$entity->isNew()) && (!$entity->isModified()) && ($entityRelated != null)) {
			if(is_array($entityRelated)) { //jesli wystepuje drugi argument to zostanie dodana relacja pomiedzy danym obiektem a drugim podanym jako drugi argument
				$this->_db->beginTransaction(); //rozpoczecie transakcji
				try {
					foreach($entityRelated AS $entityR) {
						if(is_array($entityR)) {
							foreach($entityR AS $eR) {
								if(!$eR instanceof IEntity) { //rzucony wyjatek w przypadku kiedy drugi arg. nie jest obiektem obslugiwanym przez mappery
									throw new DataMapperException('Obiekt nie jest instancja wymaganej klasy');
								}										
								$this->saveRelation($entity, $eR);
							}
						}
						else {
							if(!$entityR instanceof IEntity) { //rzucony wyjatek w przypadku kiedy drugi arg. nie jest obiektem obslugiwanym przez mappery
								throw new DataMapperException('Obiekt nie jest instancja wymaganej klasy');
							}									
							$this->saveRelation($entity, $entityR);
						}
					}
				}	
				catch(DbException $dbEx) {
					$this->_db->rollBack();
					throw $dbEx;
				}
				$this->_db->commit(); //zamkniecie transakcji
			}
			else {
				if(!$entityRelated instanceof IEntity) { //rzucony wyjatek w przypadku kiedy drugi arg. nie jest obiektem obslugiwanym przez mappery
					throw new DataMapperException('Obiekt nie jest instancja wymaganej klasy');
				}					
				$this->_db->beginTransaction(); //rozpoczecie transakcji
				try {
					$this->saveRelation($entity, $entityRelated);
				}	
				catch(DbException $dbEx) {
					$this->_db->rollBack();
					throw $dbEx;
				}
				$this->_db->commit(); //zamkniecie transakcji
			}						
		}			
		return $entity;	
	}
	
	/**
	 * Usuwa obiekt i obiekty z nim powiazane z bazy danych
	 * 
	 * @access public
	 * @param object Obiekt biznesowy obslugiwany przez daneg mappera
	 * @param object Obiekt biznesowy powiazany z nim (lub obiekty), jesli ujeto go w konfiguracji (lub tablica obiektow)
	 * @return void
	 * 
	 */	
	public function delete(IEntity $entity, $entityRelated=null) {		
		$entityClassName = $this->_entityClassName;
		if(!$entity instanceof $entityClassName) { //jesli pierwszy argument nie jest obiektem klasy obslugiwanej przez ten mapper zostanie rzucony blad
			throw new DataMapperException('Obiekt nie jest instancja wymaganej klasy: ' . $entityClassName);
		}	
		if($entityRelated == null) { //jesli nie podano drugiego argumentu - obiektu pozostajacego do danego w relacji to nastapi skasowane obiektu podanego w pierwszym argumencie i wszelkich powiazan tego obiektu z obiektami pozostajacymi do niego w relacji
			if($entity->isNew() == false && $entity->isDeleted() == false && $entity->isReadOnly() == false) { // usuniecie nastapi jesli obiekt istnieje w bazie, wiec nie jest nowy i nie jest  juz usuniety
				$this->_db->beginTransaction();
				try {
					$entityPkColumnName = $this->_entityPkColumnName; //pobranie nazwy kolumny z kluczem glownym
					$entityTableName = $this->_entityTableName; //pobranie nazwy tabeli 
					$query = "DELETE FROM `" . $entityTableName . "` WHERE " . $entityPkColumnName . " = '" . $entity->{$this->_fields[$entityPkColumnName]['nameInObj']} . "' LIMIT 1"; //zapytanie usuwajace obiekt z bazy danych 
					$result = $this->_db->exec($query); //usuniecie danego obiektu z bazy danych
					
					foreach($this->_relations AS $relationDefinitionName=>$relationDefinition) { //obsluga usuwania relacji 1:1, 1:many						
						if((isset($relationDefinition['rtable'])) && ($relationDefinition['rtable'] != '')) { //UWAGA! Aktualnie nie ma sprawdzania czy nie ustaowiono w ten sposob relacji many:many, w ten sposob nie bedzie dzialac, w tej chwili trzeba skorzystac z tabeli posredniej
							if($relationDefinition['rtable'] == 'same') {
								continue; //jesli tabela utrzymujaca relacje jest tabela aktualnego obiektu, a wlasnie skasowano go, to relacja juz nie istnieje
							}
							elseif($relationDefinition['rtable'] == 'other') {
								if((isset($relationDefinition['rtablename'])) && (!empty($relationDefinition['rtablename']))) {
									$relationshipTableName = $relationDefinition['rtablename']; //jesli umieszczono nazwe tabeli w konfiguracji to statad ja biore 
								}
								else {
									$relationshipTableName = $relationDefinitionName; //w innym wypadku bedzie odpowiadac nazwie relacji
								}
								if((isset($relationDefinition['efkey'])) && (!empty($relationDefinition['efkey']))) {
									$entityFkColumnName = $relationDefinition['efkey']; //jesli umieszczono nazwe klucza obcego w tabeli konfiguracji relacji to pobieram 
								}
								else {
									$entityFkColumnName = $this->_entityName . '_id'; //w innym wypadku tworze nazwe pola klucza
								}	
								$query = "UPDATE `" . $relationshipTableName . "` SET `" . $entityFkColumnName . "` = NULL WHERE " . $entityFkColumnName . " = '" . $entity->{$this->_fields[$entityPkColumnName]['nameInObj']} . "'";  
								$result = $this->_db->exec($query); //usuniecie wszystkich powiazan danego obiektu z obiektem/ami pozostajacym do niego w relacji
							}
							else {
								throw new DataMapperException('Nieznana definicja tabeli relacji');
							}
						}
						else { //obsluga relacji many:many, moze tez 1:1 i 1:many
							if(isset($relationDefinition['rtablename'])) {
								$relationshipTableName = $relationDefinition['rtablename']; 
							}
							if(empty($relationshipTableName)) {
								if(!isset($relationDefinition['mapper'])) {
									throw new DataMapperException('Nie zdefiniowano nazwy obiektu mapowania');
								}
								$entityRelatedDataMapper = $this->_mapperFactory->create($relationDefinition['mapper']); //utworzenie obiektu mappera dla obiektu pozostajacego w relacji do danego
								$entityRelatedObject = $entityRelatedDataMapper->create(); //utworzenie tymczasowego obiektu pozostajacego w relacji do danego
								$relationshipTableName = $this->getEntitiesRelationshipTableName($entity, $entityRelatedObject); //utworzenie nazwy tabeli posredniej
							}
							
							$entityFkColumnName = ''; //pobranie nazwy pola klucza glownego
							if((isset($relationDefinition['efkey'])) && (!empty($relationDefinition['efkey']))) {
								$entityFkColumnName = $relationDefinition['efkey']; //jesli umieszczono nazwe klucza obcego w tabeli konfiguracji relacji to pobieram 
							}
							else {
								$entityFkColumnName = $this->_entityName . '_id'; //w innym wypadku tworze nazwe pola klucza
							}							
							
							/*
							$tmpArrSelf = explode('_', $relationshipTableName);
							if($tmpArrSelf[0] == $tmpArrSelf[1]) { //obsluga przypadu relacji many:many w jednej tabeli z tabela posrednia
								$entityRelatedFkColumnName = '';
								if((isset($relationDefinition['erfkey'])) && (!empty($relationDefinition['erfkey']))) {
									$entityRelatedFkColumnName = $relationDefinition['erfkey']; //jesli umieszczono nazwe klucza obcego obiektu bedacego w relacji z danym w tabeli konfiguracji relacji to pobieram 
								}
								else {
									if(!isset($relationDefinition['mapper'])) {
										throw new DataMapperException('Nie zdefiniowano nazwy obiektu mapowania');
									}
									$entityRelatedDataMapper = $this->_mapperFactory->create($relationDefinition['mapper']); //utworzenie obiektu mappera dla obiektu pozostajacego w relacji do danego
									$entityRelatedRelations = $entityRelatedDataMapper->getRelations(); //pobieram konfiguracje relacji, aby znalesc nazwe klucza obcego dla tego obiektu w tabeli posredniej
									foreach($entityRelatedRelations AS $name=>$definition) { //sprwadzam kolejno definicje relacji szukajac nazwy tabeli odpowiadajacej 
										if((isset($definition['rtablename'])) && ($definition['rtablename'] == $relationshipTableName) && ($name != $relationDefinitionName)) {
											if((isset($definition['efkey'])) && (!empty($definition['efkey']))) {
												$entityRelatedFkColumnName = $definition['efkey'];
												 break;
											}
										}
									}
									if($entityRelatedFkColumnName == '') {
										$entityRelatedFkColumnName = $entityRelatedDataMapper->getEntityName() . '_id'; //w innym wypadku tworze nazwe pola klucza
									}
								}
								$query = "DELETE FROM `" . $relationshipTableName . "` WHERE `" . $entityFkColumnName . "` = '" . $entity->{$this->_fields[$entityPkColumnName]['nameInObj']} . "' OR `". $entityRelatedFkColumnName . "` = '" . $entity->{$this->_fields[$entityPkColumnName]['nameInObj']} . "'"; //zapytanie usuwajace wszystkie powiazania danego obiektu z obiektami pozoztajacymi do niego w relacji								
								$result = $this->_db->exec($query); //usuniecie wszystkich powiazan danego obiektu z obiektem/ami pozostajacym do niego w relacji
														
							}
							else {*/
								$query = "DELETE FROM `" . $relationshipTableName . "` WHERE `" . $entityFkColumnName . "` = '" . $entity->{$this->_fields[$entityPkColumnName]['nameInObj']} . "'"; //zapytanie usuwajace wszystkie powiazania danego obiektu z obiektem pozoztajacym do niego w relacji
								$result = $this->_db->exec($query); //usuniecie wszystkich powiazan danego obiektu z obiektem/ami pozostajacym do niego w relacji
							/*}*/
						}
					}
				}
				catch(DbException $dbEx) {
					$this->_db->rollBack();
					throw $dbEx;
				}
				$this->_db->commit(); //zamkniecie transakcji
				$entity->deleted(true); //oznaczenie obiektu jako skasowanego
			}
			else {
				throw new DataMapperException('Obiekt nie istnieje w bazie danych, poniewaz zostal juz usuniety lub jest nowy');
			}
		}
		elseif(is_array($entityRelated)) { //jesli wystepuje drugi agrument to nie zostanie usuniety obiekt podany jako arg pierwszy tylko relacje pomiedzy nim a obiektem/ami podanym/i jako arg drugi
			$this->_db->beginTransaction(); //rozpoczecie transakcji
			try {
				foreach($entityRelated AS $entityR) {
					if(is_array($entityR)) {
						foreach($entityR AS $eR) {
							if(!$eR instanceof IEntity) { //jesli pierwszy argument nie jest obiektem klasy obslugiwanej przez ten mapper zostanie rzucony blad
								throw new DataMapperException('Obiekt nie jest instancja wymaganej klasy');
							}								
							$this->deleteRelation($entity, $eR);
						}
					}
					else {
						if(!$entityR instanceof IEntity) { //jesli pierwszy argument nie jest obiektem klasy obslugiwanej przez ten mapper zostanie rzucony blad
							throw new DataMapperException('Obiekt nie jest instancja wymaganej klasy');
						}							
						$this->deleteRelation($entity, $entityR);
					}
				}
			}	
			catch(DbException $dbEx) {
				$this->_db->rollBack();
				throw $dbEx;
			}
			$this->_db->commit(); //zamkniecie transakcji
		}
		else {
			$this->_db->beginTransaction(); //rozpoczecie transakcji
			try {
				if(!$entityRelated instanceof IEntity) { //jesli pierwszy argument nie jest obiektem klasy obslugiwanej przez ten mapper zostanie rzucony blad
					throw new DataMapperException('Obiekt nie jest instancja wymaganej klasy');
				}					
				$this->deleteRelation($entity, $entityRelated);
			}	
			catch(DbException $dbEx) {
				$this->_db->rollBack();
				throw $dbEx;
			}
			$this->_db->commit(); //zamkniecie transakcji
		}		
	}
	
	/**
	 * Wyszukuje obiekty pozostajace w relacji do danego (obslugiwane przez mapper podany w argumencie), obslugiwanego przez ten mapper i podanego przez wartosc klucza glownego,  
	 * 
	 * @access public
	 * @param object Obiekt mappera dla obiektu biznesowego pozostajacego w relacji do obslugiwanego przez ten aktualny mapper
	 * @param int Wartosc klucza glownego dla tego obiektu beda wyszukiwane obiekty powiazane
	 * @return object
	 * 
	 */		
	public function getRelated(DataMapperAbstract $entityRelatedDataMapper, $pk) { 
		$entityTableName = $this->_entityTableName;
		$entityClassName = $this->_entityClassName;
		$entityName = $this->_entityName;
		$entityPkColumnName = $this->_entityPkColumnName;
		$entityFkColumnName = $entityName . "_id";
		$entityRelatedMapperClassName = get_class($entityRelatedDataMapper);
		$entityRelatedTableName = $entityRelatedDataMapper->getEntityTableName();
		$entityRelatedName = $entityRelatedDataMapper->getEntityName();
		$entityRelatedPkColumnName = $entityRelatedDataMapper->getEntityPkColumnName();
		$entityRelatedFkColumnName = $entityRelatedName . "_id";
		
		$relationshipTableName = null;
		$entityDefinedRelations = $this->_relations; //definicje tabel powiazanych
		$entityRelatedDefinedRelations = $entityRelatedDataMapper->getRelations(); //definicje relacji obiektu powiazanego	
				
		$entitiesName = $this->_inflector->pluralize($entityName); //nazwa definicji obiektu powiazanego z danym
		$entitiesRelatedName = $this->_inflector->pluralize($entityRelatedName); //nazwa definicji relacji z obiektem powiazanym	
		if(isset($entityDefinedRelations[$entitiesRelatedName]['rtable']) && ($entityDefinedRelations[$entitiesRelatedName]['rtable'] != '')) { //jesli zdefiniowano relacje w ktorejs z dwoch tabel pozostajacych w relacji 
			if($entityDefinedRelations[$entitiesRelatedName]['rtable'] == 'same') { //i jest to tabela obslugiwana przez aktualny mapper
				$relationTypeWithEntityRelated = $entityDefinedRelations[$entitiesRelatedName]['relation']; //pobranie typu relacji: hasOne, HasMany
				if($relationTypeWithEntityRelated == 'hasMany') { //sprawdzam czy nie mamy do czynienia z relacja many:many, ta nie jest obslugiwana w ten sposob
					throw new DataMapperException('Obiekt: ' . $entityName . ' nie moze przechowywac kluczy obcych obiektow: ' . $entityRelatedName . ', pozostajac z nimi w relacji 1:many');
				}
				//$query = "SELECT * FROM `" . $entityRelatedTableName . "`, `" . $entityTableName . "` WHERE `" . $entityTableName . "`.`". $entityRelatedFkColumnName . "` = `" . $entityRelatedTableName . "`.`" . $entityRelatedPkColumnName . "` AND `" . $entityRelatedTableName . "`.`" . $entityRelatedPkColumnName . "` = :pk";
				$query = "SELECT `" . $entityTableName . "`.* FROM `" . $entityTableName . "`";
				$query .= " JOIN `" . $entityRelatedTableName . "`";
				$query .= " ON `" . $entityRelatedTableName . "`.`" . $entityRelatedPkColumnName . "` = `" . $entityTableName . "`.`" . $entityRelatedFkColumnName . "`";
				$query .= " WHERE `" . $entityRelatedTableName . "`.`" . $entityRelatedPkColumnName . "` = :pk";
			}
			elseif($entityDefinedRelations[$entitiesRelatedName]['rtable'] == 'other') { //jesli tabela relacji jest tabela obslugiwana przez mapper obiektu powiazanego
				$relationTypeRelatedWithEntity = $entityRelatedDefinedRelations[$entitiesName]['relation']; //pobranie typu relacji: hasOne, HasMany					
				if($relationTypeRelatedWithEntity == 'hasMany') { //sprawdzam czy obiekt bedacy w relacji z danym nie jest z nim w relacji 1:many, jesli tak to bylaby to relacja many:many, a ta jest obslugiwana tabela pivotem
					throw new DataMapperException('Obiekt: ' . $entityRelatedName . ' nie moze przechowywac kluczy obcych obiektow: ' . $entityName . ', pozostajac z nimi w relacji 1:many');
				}
				//$query = "SELECT * FROM `" . $entityRelatedTableName . "`, `" . $entityTableName . "` WHERE `" . $entityTableName . "`.`". $entityPkColumnName . "` = `" . $entityRelatedTableName . "`.`" . $entityFkColumnName . "` AND `" . $entityRelatedTableName . "`.`" . $entityRelatedPkColumnName . "` = :pk";

				if($entityTableName == $entityRelatedTableName) { //jesli nazwa tabeli jest taka sama jak tabeli powiazanej
					$query = "SELECT `" . $entityTableName . "`.* FROM `" . $entityTableName . "`";
					$query .= " WHERE `" . $entityRelatedTableName . "`.`" . $entityRelatedFkColumnName . "` = :pk";
				}
				else {		
					$query = "SELECT `" . $entityTableName . "`.* FROM `" . $entityTableName . "`";
					$query .= " JOIN `" . $entityRelatedTableName . "`";
					$query .= " ON `" . $entityRelatedTableName . "`.`" . $entityFkColumnName . "` = `" . $entityTableName . "`.`" . $entityPkColumnName . "`";
					$query .= " WHERE `" . $entityRelatedTableName . "`.`" . $entityRelatedPkColumnName . "` = :pk";
				}
			}
			else {
				throw new DataMapperException('Nieznana definicja tabeli relacji');
			}
			
			$params = array(":pk"=>$pk);
			$result = $this->_db->prepare($query); //wyslanie i wykonanie zapytania 
			$result->execute($params); 
			$data = $result->fetchAll(); //pobranie wynikow
			$this->_collection->clear(); //wyczyszczenie kolekcji
			foreach($data AS $row) {
				$entity = $this->load($row); //zaladowanie danych do tworzonych obiektow biznesowych
				$this->_collection->add($entity); //dodanie obiektow do kolekcji
			}
			return $this->_collection; //pusta kolekcja, nalezy ja zwrocic nawet pusta, bo nie bedzie mozna iterowac po pustej wartosci
			
		}
		else { //obsluga tabeli posredniej		
			/* //2011.07.31
			foreach($entityDefinedRelations AS $relationDefinition) {
				if(isset($relationDefinition['mapper']) && ($relationDefinition['mapper'] == $entityRelatedMapperClassName)) {
					if(isset($relationDefinition['rtablename'])) { //jesli zdefiniowano mapper dla powiazanych obiektow i jeszcze jest podana nazwa taebli posredniej
						$relationshipTableName = $relationDefinition['rtablename'];
						break;
					}
				}
			}
			*/

			$entitiesRelatedName = $this->_inflector->pluralize($entityRelatedName); //nazwa definicji relacji z obiektem powiazanym		
			if(isset($entityDefinedRelations[$entitiesRelatedName]['mapper']) && ($entityDefinedRelations[$entitiesRelatedName]['mapper'] == $entityRelatedMapperClassName)) {
				if(isset($entityDefinedRelations[$entitiesRelatedName]['rtablename'])) {
					$relationshipTableName = $entityDefinedRelations[$entitiesRelatedName]['rtablename'];
				}					
			}		
			
			if($relationshipTableName == null) { //jesli nie uzyskano nazwy tabeli posredniej z kongiguracji, nalezy ja utworzyc wg przyjetych zasad
				$tempEntity =  $this->create();
				$tempEntityRelated = $entityRelatedDataMapper->create();
				$relationshipTableName =  $this->getEntitiesRelationshipTableName($tempEntity, $tempEntityRelated);
			}
			
			if($entityTableName == $entityRelatedTableName) { //jesli nazwa tabeli jest taka sama jak tabeli powiazanej
				$query = "SELECT `" . $entityTableName . "`.* FROM `" . $entityTableName . "`";
				$query .= " LEFT OUTER JOIN `" . $relationshipTableName . "`";
				$query .= " ON `" . $entityRelatedTableName . "`.`" . $entityRelatedPkColumnName . "` = `" . $relationshipTableName . "`.`" . $entityFkColumnName . "`";
				$query .= " WHERE `" . $relationshipTableName . "`.`" . $entityRelatedFkColumnName . "` = :pk";
			}
			else { //w innym przypadku 
				$query = "SELECT `" . $entityTableName . "`.* FROM `" . $entityTableName . "`";
				$query .= " LEFT OUTER JOIN `" . $relationshipTableName . "`";
				$query .= " ON `" . $entityTableName . "`.`" . $entityPkColumnName . "` = `" . $relationshipTableName . "`.`" . $entityFkColumnName . "`";
				$query .= " LEFT OUTER JOIN `" . $entityRelatedTableName . "`";
				$query .= " ON `" . $entityRelatedTableName . "`.`" . $entityRelatedPkColumnName . "` = `" . $relationshipTableName . "`.`" . $entityRelatedFkColumnName . "`";
				$query .= " WHERE `" . $entityRelatedTableName . "`.`" . $entityRelatedPkColumnName . "` = :pk";
			}
			$params = array(":pk"=>$pk);
			$result = $this->_db->prepare($query); //wyslanie i wykonanie zapytania 
			$result->execute($params); 
			$data = $result->fetchAll(); //pobranie wynikow
			$this->_collection->clear(); //wyczyszczenie kolekcji
			foreach($data AS $row) {
				$entity = $this->load($row); //zaladowanie danych do tworzonych obiektow biznesowych
				$this->_collection->add($entity); //dodanie obiektow do kolekcji
			}
			return $this->_collection; //kolekcja, nalezy ja zwrocic, nawet pusta bo nie bedzie mozna iterowac po pustej wartosci
		}
	}	
	
	/*
	 *******************************************************************
	 * Pomocnicze metody
	 *******************************************************************
	 */
	 
	/**
	 * Usuwa relacje miedzy dwoma obiektami biznesowymi
	 * 
	 * @access public
	 * @param object Obiekt biznesowy
	 * @param object Obiekt biznesowy powiazany z pierwszym
	 * @return int
	 * 
	 */	
	public function deleteRelation(IEntity $entity, IEntity $entityRelated) {
		$result = null;
		$entityClassName = $this->_entityClassName;
		if(!$entity instanceof $entityClassName) { //rzucony wyjatek w przypadku kiedy pierwszy arg. nie jest obiektem obslugiwanym przez ten mapper
				throw new DataMapperException('Obiekt nie jest instancja wymaganej klasy:' . $entityClassName);
		}	

		$entityPkColumnName = $this->_entityPkColumnName; //pobranie nazwy kolumny bedacej kluczem glownym
		$entityName = $this->_entityName; //nazwa obiektu, jak nazwa tabeli, ale w l.pojedynczej
		$entityTableName = $this->_entityTableName; //nazwa tabeli
		
		$entityRelatedClassName = get_class($entityRelated); //uzyskanie nazwy klasy obiektu pozostajacego w relacji do danego
		$entityRelatedMapperClassName = $this->_inflector->makeMapperClassNameFromEntityClassName($entityRelatedClassName); //uzyskanie nazwy klasy mappera obslugujacego powyzsze obiekty
		$entityRelatedMapper = $this->_mapperFactory->create($entityRelatedMapperClassName); //utworzenie obiektu mappera dla obiektów pozostajacych w relacji z danym
		$entityRelatedPkColumnName = $entityRelatedMapper->getEntityPkColumnName(); //pobranie nazwy kolumny bedacej kluczem glownym dotyczy obiektu bedacego w relacji do danego
		$entityRelatedName = $entityRelatedMapper->getEntityName(); //nazwa obiektu, jak nazwa tabeli, ale w l.pojedynczej dla obiektu pozostajacego w relacji
		$entityRelatedTableName = $entityRelatedMapper->getEntityTableName();
		
		$entityPk = $entity->{$this->_fields[$entityPkColumnName]['nameInObj']}; //uzyskanie wartosci bedacej kluczem glownym dla danego obiektu 
		$fieldsInRelatedTable = $entityRelatedMapper->getFields(); //definicje pol z obiektu powiazanego
		$entityRelatedPk = $entityRelated->{$fieldsInRelatedTable[$entityRelatedPkColumnName]['nameInObj']};//uzyskanie wartosci bedacej kluczem glownym dla obiektu bedacego w relacji do danego 
		
		if($entityPk != null && $entityRelatedPk != null) { //jesli zaden z obiektow nie jest nowy
			$relationshipTableName = null; //nazwa tabeli relacji
			$entityDefinedRelations = $this->_relations; //definicje relacji danego obiektu z powiazanym
			$entityRelatedDefinedRelations = $entityRelatedMapper->getRelations(); //definicje relacji obiektu powiazanego			
			$entitiesRelatedName = $this->_inflector->pluralize($entityRelatedName); //nazwa definicji relacji z obiektem powiazanym
			$entitiesName = $this->_inflector->pluralize($entityName); //nazwa definicji obiektu powiazanego z danym
			
			if(isset($entityDefinedRelations[$entitiesRelatedName]['rtable']) && ($entityDefinedRelations[$entitiesRelatedName]['rtable'] != '')) { //jesli zdefiniowano relacje w ktorejs z dwoch tabel pozostajacych w relacji 
				if($entityDefinedRelations[$entitiesRelatedName]['rtable'] == 'same') { //i jest to tabela obslugiwana przez aktualny mapper
					$relationTypeWithEntityRelated = $entityDefinedRelations[$entitiesRelatedName]['relation']; //pobranie typu relacji: hasOne, HasMany
					if($relationTypeWithEntityRelated == 'hasMany') { //sprawdzam czy nie mamy do czynienia z relacja many:many, ta nie jest obslugiwana w ten sposob
						throw new DataMapperException('Obiekt: ' . $entityName . ' nie moze przechowywac kluczy obcych obiektow: ' . $entityRelatedName . ', pozostajac z nimi w relacji 1:many');
					}
					$relationshipTableName = $entityTableName; //to staje sie tabela przechowujaca relacje
					$entityRelatedFkColumnName = $entityRelatedName . '_id'; //a to jest nazwa pola klucza obcego w tej tabeli
					$query = "UPDATE `" . $relationshipTableName . "` SET `" . $entityRelatedFkColumnName . "` = NULL WHERE " . $entityRelatedFkColumnName . " = '" . $entityRelatedPk . "' AND `" . $entityPkColumnName . "` = '" . $entityPk . "'";;  //ustawiam jego wartosc na null, tym samym usuwajac relacje miedzy oboma obiektami
					$result = $this->_db->exec($query); //usuniecie wszystkich powiazan danego obiektu z obiektem/ami pozostajacym do niego w relacji
				}
				elseif($entityDefinedRelations[$entitiesRelatedName]['rtable'] == 'other') { //jesli tabela relacji jest tabela obslugiwana przez mapper obiektu powiazanego
					$relationTypeRelatedWithEntity = $entityRelatedDefinedRelations[$entitiesName]['relation']; //pobranie typu relacji: hasOne, HasMany					
					if($relationTypeRelatedWithEntity == 'hasMany') { //sprawdzam czy obiekt bedacy w relacji z danym nie jest z nim w relacji 1:many, jesli tak to bylaby to relacja many:many, a ta jest obslugiwana tabela pivotem
						throw new DataMapperException('Obiekt: ' . $entityRelatedName . ' nie moze przechowywac kluczy obcych obiektow: ' . $entityName . ', pozostajac z nimi w relacji 1:many');
					}
					$relationshipTableName = $entityRelatedTableName; //staje sie ona tabela przechowujaca relacje
					$entityFkColumnName = $entityName . '_id'; //a to jest nazwa pola klucza obcego w tej tabeli
					$query = "UPDATE `" . $relationshipTableName . "` SET `" . $entityFkColumnName . "` = NULL WHERE " . $entityFkColumnName . " = '" . $entityPk . "' AND `" . $entityRelatedPkColumnName . "` = '" . $entityRelatedPk . "'";  //ustawiam jego wartosc na null, tym samym usuwajac relacje miedzy oboma obiektami
					$result = $this->_db->exec($query); //usuniecie wszystkich powiazan danego obiektu z obiektem/ami pozostajacym do niego w relacji		
				}
				else {
					throw new DataMapperException('Nieznana definicja tabeli relacji');
				}	
			}
			else { //obsluga relacji w tabeli posredniej
				/* //2011.07.31
				foreach($entityDefinedRelations AS $relationDefinition) {
					if(isset($relationDefinition['mapper']) && ($relationDefinition['mapper'] == $entityRelatedMapperClassName)) {
						if(isset($relationDefinition['rtablename'])) {
							$relationshipTableName = $relationDefinition['rtablename'];
							break;
						}
					}
				}
				*/
				if(isset($entityDefinedRelations[$entitiesRelatedName]['mapper']) && ($entityDefinedRelations[$entitiesRelatedName]['mapper'] == $entityRelatedMapperClassName)) {
					if(isset($entityDefinedRelations[$entitiesRelatedName]['rtablename'])) {
						$relationshipTableName = $entityDefinedRelations[$entitiesRelatedName]['rtablename'];
					}					
				}
				if(empty($relationshipTableName)) {
					$relationshipTableName = $this->getEntitiesRelationshipTableName($entity, $entityRelated); //pobranie nazwy tabeli posredniej
				}
				$entityFkColumnName = $entityName . '_id'; //uzyskanie nazw kolumn bedacych kluczami obcymi w tabeli posredniej
				$entityRelatedFkColumnName = $entityRelatedName . '_id';
				$query = "DELETE FROM `" . $relationshipTableName . "` WHERE `" . $entityFkColumnName . "` = '" . $entityPk . "' AND `" . $entityRelatedFkColumnName . "` = '" . $entityRelatedPk . "' LIMIT 1"; //zapytanie usuwajace relacje miedzy tymi dwoma obiektami
				$result = $this->_db->exec($query); //usuniecie relacji z bazy danych
			}
		}
		return $result;
	}
	
	/**
	 * Dodaje do bazy danych relacje miedzy dwoma obiektami biznesowymi
	 * 
	 * @access public
	 * @param object Obiekt biznesowy
	 * @param object Obiekt biznesowy powiazany z pierwszym
	 * @return int
	 * 
	 */		
	public function saveRelation(IEntity $entity, IEntity $entityRelated) {
		$entityClassName = $this->_entityClassName;
		if(!$entity instanceof $entityClassName) { //rzucony wyjatek w przypadku kiedy pierwszy arg. nie jest obiektem obslugiwanym przez ten mapper
			throw new DataMapperException('Obiekt nie jest instancja wymaganej klasy: ' . $entityClassName);
		}
		
		$entityPkColumnName = $this->_entityPkColumnName; //pobranie nazwy kolumny bedacej kluczem glownym
		$entityName = $this->_entityName; //nazwa obiektu, jak nazwa tabeli, ale w l.pojedynczej
		$entityTableName = $this->_entityTableName;
		
		$entityRelatedClassName = get_class($entityRelated); //uzyskanie nazwy klasy obiektu pozostajacego w relacji do danego
		$entityRelatedMapperClassName = $this->_inflector->makeMapperClassNameFromEntityClassName($entityRelatedClassName); //uzyskanie nazwy klasy mappera obslugujacego powyzsze obiekty
		$entityRelatedMapper = $this->_mapperFactory->create($entityRelatedMapperClassName); //utworzenie obiektu mappera dla obiektów pozostajacych w relacji z danym
		$entityRelatedPkColumnName = $entityRelatedMapper->getEntityPkColumnName(); //pobranie nazwy kolumny bedacej kluczem glownym dotyczy obiektu bedacego w relacji do danego
		$entityRelatedName = $entityRelatedMapper->getEntityName(); //nazwa obiektu, jak nazwa tabeli, ale w l.pojedynczej dla obiektu pozostajacego w relacji
		$entityRelatedTableName = $entityRelatedMapper->getEntityTableName();
		
		$fieldsInRelatedTable = $entityRelatedMapper->getFields();
		
		$entityPk = $entity->{$this->_fields[$entityPkColumnName]['nameInObj']}; //uzyskanie wartosci bedacej kluczem glownym dla danego obiektu 
		$entityRelatedPk = $entityRelated->{$fieldsInRelatedTable[$entityRelatedPkColumnName]['nameInObj']};//uzyskanie wartosci bedacej kluczem glownym dla obiektu bedacego w relacji do danego 
		
		if($entityPk !== null && $entityRelatedPk !== null) { //jesli oba obiekty istnieja juz w bazie danych
			$relationshipTableName = null;
			$entityDefinedRelations = $this->_relations; //definicje relacji z obiektami powiazanymi
			$entityRelatedDefinedRelations = $entityRelatedMapper->getRelations(); //definicje relacji obiektu powiazanego
			
			$entitiesRelatedName = $this->_inflector->pluralize($entityRelatedName); //nazwa definicji relacji z obiektem powiazanym
			$entitiesName = $this->_inflector->pluralize($entityName); //nazwa definicji obiektu powiazanego z danym

			if(isset($entityDefinedRelations[$entitiesRelatedName]['rtable']) && ($entityDefinedRelations[$entitiesRelatedName]['rtable'] != '')) { //jesli zdefiniowano relacje w ktorejs z dwoch tabel pozostajacych w relacji 
				if($entityDefinedRelations[$entitiesRelatedName]['rtable'] == 'same') { //i jest to tabela obslugiwana przez aktualny mapper
					$relationTypeWithEntityRelated = $entityDefinedRelations[$entitiesRelatedName]['relation']; //pobranie typu relacji: hasOne, HasMany
					if($relationTypeWithEntityRelated == 'hasMany') { //sprawdzam czy nie mamy do czynienia z relacja many:many, ta nie jest obslugiwana w ten sposob
						throw new DataMapperException('Obiekt: ' . $entityName . ' nie moze przechowywac kluczy obcych obiektow: ' . $entityRelatedName . ', pozostajac z nimi w relacji 1:many');
					}
					$relationshipTableName = $entityTableName; //tabela przechowujaca relacje					
					$entityRelatedFkColumnName = $entityRelatedName . '_id'; //a to jest nazwa pola klucza obcego w tej tabeli
					$query = "SELECT * FROM `" . $relationshipTableName . "` WHERE  `" . $entityRelatedFkColumnName . "` = '" . $entityRelatedPk . "' AND " . $entityPkColumnName . " = '" . $entityPk . "'";
					$result = $this->_db->query($query);
					$numRows = $result->rowCount(); //ilosc rekordow odpowiadajacych relacjom miedzy sprawdzanymi dwoma obiektami
					if($numRows == 0) { //jesli relacja miedzy tymi obiektami jeszcze nie istnieje to zostanie dodana 
						$query = "UPDATE `" . $relationshipTableName . "` SET `" . $entityRelatedFkColumnName . "` = '" . $entityRelatedPk . "' WHERE " . $entityPkColumnName . " = '" . $entityPk . "'";  //ustawiam relacje zpisujac wartosc klucza w polu klucza obcego w tabeli obslugiwanej przez ten mapper
						$result = $this->_db->exec($query); //usuniecie wszystkich powiazan danego obiektu z obiektem/ami pozostajacym do niego w relacji	
					} //w innym wypadku nie ma co dodawac

				}
				elseif($entityDefinedRelations[$entitiesRelatedName]['rtable'] == 'other') { //jesli tabela relacji jest tabela obslugiwana przez mapper obiektu powiazanego					
					$relationTypeRelatedWithEntity = $entityRelatedDefinedRelations[$entitiesName]['relation']; //pobranie typu relacji: hasOne, HasMany					
					if($relationTypeRelatedWithEntity == 'hasMany') { //sprawdzam czy obiekt bedacy w relacji z danym nie jest z nim w relacji 1:many, jesli tak to bylaby to relacja many:many, a ta jest obslugiwana tabela pivotem
						throw new DataMapperException('Obiekt: ' . $entityRelatedName . ' nie moze przechowywac kluczy obcych obiektow: ' . $entityName . ', pozostajac z nimi w relacji 1:many');
					}
					$relationshipTableName = $entityRelatedTableName; //tabela przechowujaca relacje
					$entityFkColumnName = $entityName . '_id'; //i nazwa klucza obcego w tej tabeli
					$query = "SELECT * FROM `" . $relationshipTableName . "` WHERE  `" . $entityFkColumnName . "` = '" . $entityPk . "' AND " . $entityRelatedPkColumnName . " = '" . $entityRelatedPk . "'";					
					$result = $this->_db->query($query);
					$numRows = $result->rowCount(); //ilosc rekordow odpowiadajacych relacjom miedzy sprawdzanymi dwoma obiektami
					if($numRows == 0) { //jesli relacja miedzy tymi obiektami jeszcze nie istnieje to zostanie dodana 					
						$query = "UPDATE `" . $relationshipTableName . "` SET `" . $entityFkColumnName . "` = '" . $entityPk . "' WHERE " . $entityRelatedPkColumnName . " = '" . $entityRelatedPk . "'";  //ustawiam relacje zapisujac wartosc klucza w polu klucza obcego w tabeli powiazanej
						$result = $this->_db->exec($query); //usuniecie wszystkich powiazan danego obiektu z obiektem/ami pozostajacym do niego w relacji	
					} //w innym wypadku nie ma co dodawac
				}
				else {
					throw new DataMapperException('Nieznana definicja tabeli relacji');
				}	
			}
			else { //obsluga relacji w tabeli posredniej
				/* //2011.07.31
				foreach($entityDefinedRelations AS $relationDefinition) {
					if(isset($relationDefinition['mapper']) && ($relationDefinition['mapper'] == $entityRelatedMapperClassName)) {
						if(isset($relationDefinition['rtablename'])) {
							$relationshipTableName = $relationDefinition['rtablename'];
							break;
						}
					}
				}
				*/
				if(isset($entityDefinedRelations[$entitiesRelatedName]['mapper']) && ($entityDefinedRelations[$entitiesRelatedName]['mapper'] == $entityRelatedMapperClassName)) {
					if(isset($entityDefinedRelations[$entitiesRelatedName]['rtablename'])) {
						$relationshipTableName = $entityDefinedRelations[$entitiesRelatedName]['rtablename'];
					}
				}
				if(empty($relationshipTableName)) {
					$relationshipTableName = $this->getEntitiesRelationshipTableName($entity, $entityRelated); //pobranie nazwy tabeli posredniej
				}
				$entityFkColumnName = $entityName . '_id'; //uzyskanie nazw kolumn bedacych kluczami obcymi w tabeli posredniej
				$entityRelatedFkColumnName = $entityRelatedName . '_id';
				$query = "SELECT * FROM `" . $relationshipTableName . "` WHERE `" . $entityFkColumnName . "` = '" . $entityPk . "' AND `" . $entityRelatedFkColumnName . "` = '" . $entityRelatedPk . "'";	//zapytanie pobierajace rekordy odpowiadajace relacjom miedzy dwoma sprawdzanymi obiektami
				$result = $this->_db->query($query);
				$numRows = $result->rowCount(); //ilosc rekordow odpowiadajacych relacjom miedzy sprawdzanymi dwoma obiektami
				if($numRows == 0) { //jesli relacja miedzy tymi obiektami jeszcze nie istnieje to zostanie dodana 
					$entitiesRelatedName = $this->_inflector->pluralize($entityRelatedName); //obiekty pozostajace w relacji do danego maja nazwe zdefiniowana w l. mnogiej
					$entityDefinedRelations = $this->_relations; //pobranie istniejacych relacji
					if(!isset($entityDefinedRelations[$entitiesRelatedName])) { //przypadek kiedy nie zdefiniowano takiej relacji, a nastapila proba dodania obiektu
						throw new DataMapperException('Nie zdefioniowano relacji');
					}
					if(!isset($entityDefinedRelations[$entitiesRelatedName]['relation'])) { //brak zdefiniowanego rodzaju relacji
						throw new DataMapperException('Nie zdefiniowano relacji');
					}				
					$relationTypeWithEntityRelated = $entityDefinedRelations[$entitiesRelatedName]['relation']; //pobranie typu relacji: hasOne, HasMany
					if($relationTypeWithEntityRelated == 'hasOne') {//jesli relacja z dodawanym obiektem jest typu hasOne
						$query = "SELECT * FROM `" . $relationshipTableName . "` WHERE `" . $entityFkColumnName . "` = '" . $entityPk . "' LIMIT 1"; //sprawdzenie czy obiekt jest juz w relacji z innym, jesli jest to znaczy ze trzeba ta relacje usunac, bo relacja jest typu hasOne
						$result = $this->_db->query($query);
						$numRows = $result->rowCount();
						if($numRows > 0) { //jesli jest jakas relacja tutaj bedzie wartosc 1
							$query = "UPDATE `" . $relationshipTableName . "` SET `" . $entityFkColumnName . "` = '" . $entityPk . "', `" . $entityRelatedFkColumnName . "` = '" . $entityRelatedPk . "' WHERE `" . $entityFkColumnName . "` = '" . $entityPk . "'";
							$result = $this->_db->exec($query); //wykonanie uaktualnienia relacji miedzy obiektami
							return $result;
						}
						else { //jesli nie ma zadnej relacji, w ktorej uczestniczy dany obiekt poprostu dodaje sie nowa
							$query = "INSERT INTO `" . $relationshipTableName . "` (`" . $entityFkColumnName . "`, `" . $entityRelatedFkColumnName . "`) VALUES ('" . $entityPk . "', '" . $entityRelatedPk . "')"; 
							$result = $this->_db->exec($query); //dodanie relacji
							return $result;
						}	
					}
					elseif($relationTypeWithEntityRelated == 'hasMany') {//jesli relacja z dodawanym obiektem jest typu hasMany 
						$entitiesName = $this->_inflector->pluralize($entityName); //nazwa tego obiektu w l. mnogiej
						$entityRelatedDefinedRelations = $entityRelatedMapper->getRelations(); //zdefiniowane relacje dla obiektu dodawanego
						if(!isset($entityRelatedDefinedRelations[$entitiesName])) { //przypadek kiedy nie zdefiniowano takiej relacji, a nastapila proba dodania obiektu
							throw new DataMapperException('Nie zdefiniowano relacji');
						}
						if(!isset($entityRelatedDefinedRelations[$entitiesName]['relation'])) { //brak zdefiniowanego rodzaju relacji
							throw new DataMapperException('Nie zdefiniowano relacji');
						}							
						$relationTypeWithEntity = $entityRelatedDefinedRelations[$entitiesName]['relation']; //pobranie typu relacji w stosunku do tego danego obiektu
						if($relationTypeWithEntity == 'hasOne') { //w przypadku kiedy dodawany obiekt ma z danym relacje hasOne nalezy sprawdzic czy juz taka relacja jest jesli tak to ja zastapic w innym przypadku tylko dodac
							$query = "SELECT * FROM `" . $relationshipTableName . "` WHERE `" . $entityRelatedFkColumnName . "` = '" . $entityRelatedPk . "' LIMIT 1";//zapytanie sprawdzajace istnienie takiej relacji
							$result = $this->_db->query($query);
							$numRows = $result->rowCount();
							if($numRows > 0) { //istnieje juz relacja, trzeba wiec ja zastapic
								$query = "UPDATE `" . $relationshipTableName . "` SET `" . $entityFkColumnName . "` = '" . $entityPk . "', `" . $entityRelatedFkColumnName . "` = '" . $entityRelatedPk . "' WHERE `" . $entityRelatedFkColumnName . "` = '" . $entityRelatedPk . "'";
								$result = $this->_db->exec($query); //wykonanie uaktualnienia relacji miedzy obiektami
								return $result;
							}
							else {
								$query = "INSERT INTO `" . $relationshipTableName . "` (`" . $entityFkColumnName . "`, `" . $entityRelatedFkColumnName . "`) VALUES ('" . $entityPk . "', '" . $entityRelatedPk . "')"; 
								$result = $this->_db->exec($query); //dodanie relacji							
								return $result;
							}
						}
						elseif($relationTypeWithEntity == 'hasMany') { //jesli relacja jest typu hasMany w obie strony to tylko nastepuje dodanie nowej relacji miedzy zadanymi obiektami
							$query = "INSERT INTO `" . $relationshipTableName . "` (`" . $entityFkColumnName . "`, `" . $entityRelatedFkColumnName . "`) VALUES ('" . $entityPk . "', '" . $entityRelatedPk . "')"; 
							$result = $this->_db->exec($query); //dodanie relacji
							return $result;
						}
					}				
				} //w przeciwnym wypadku relacja juz istnieje wiec nie zostanie dodana	
			}
		
		}
	}
	
	/**
	 * Poszukuje nazwy tabeli posredniej (pivot) dla dwoch podanych obiektow biznesowych
	 * 
	 * @access public
	 * @param object Obiekt biznesowy
	 * @param object Obiekt biznesowy powiazany z pierwszym
	 * @return string
	 * 
	 */		
	protected function getEntitiesRelationshipTableName(IEntity $entity, IEntity $entityRelated) {
		$entityClassName = $this->_entityClassName;
		if(!$entity instanceof $entityClassName) { //rzucony wyjatek w przypadku kiedy pierwszy arg. nie jest obiektem obslugiwanym przez ten mapper
			throw new DataMapperException('Obiekt nie jest instancja wymaganej klasy: ' . $entityClassName);
		}	
		
		$entityTableName = $this->_entityTableName; //nazwa tabeli z ktorej korzysta pierwszy obiekt
		
		$entityRelatedClassName = get_class($entityRelated); //uzyskanie nazwy klasy obiektu pozostajacego w relacji do danego
		$entityRelatedMapperClassName = $this->_inflector->makeMapperClassNameFromEntityClassName($entityRelatedClassName); //uzyskanie nazwy klasy mappera obslugujacego powyzsze obiekty
		$entityRelatedMapper = $this->_mapperFactory->create($entityRelatedMapperClassName); //utworzenie obiektu mappera dla obiektów pozostajacych w relacji z danym
		$entityRelatedTableName = $entityRelatedMapper->getEntityTableName(); //pobranie nazwy tabeli przechowujacej obiekty pozostajace w relacji do danego
		
		if($entityTableName == $entityRelatedTableName) { //jesli nazwy tabel dla obu obiektow sa takie same nazwa tabeli posredniej zostanie utworzona z nazw obiektow
			$entityName = $this->_entityName; //pobranie nazwy obiektu
			$entityRelatedName = $entityRelatedMapper->getEntityName(); //nazwa obiektu, jak nazwa tabeli, ale w l.pojedynczej dla obiektu pozostajacego w relacji
			$entityTableName = $this->_inflector->pluralize($entityName);
			$entityRelatedTableName = $this->_inflector->pluralize($entityRelatedName);
		}
		
		//nazwa tabel posredniej sklada sie z nazw tabel lub obiektow w liczbie mnogiej przedzielonych znakiem podkreslenia w kolejnoscie alfabetycznej
		$relationshipTableName = ($entityTableName < $entityRelatedTableName) ? $entityTableName . '_' . $entityRelatedTableName : $entityRelatedTableName . '_' . $entityTableName;
		return $relationshipTableName;
	}
	
	/**
	 * Inicjuje pola zawierajace kolekcje obiektow pozostajacych w relacji z danym
	 * 
	 * @access public
	 * @param object Obiekt biznesowy
	 * @param int wartosc klucza glownego
	 * @return string
	 * 
	 */		
	protected function initRelations($entity, $entityPk=null) {
		if($entityPk !== null) { //jesli nie wybrano selectem id lub po zapisaniu do bazy nie pobrano go to nie bedzie mozna pobrac obiektow powiazanych z danym
			foreach($this->_relations AS $relationDefinition) { //zainicjowane zostana (przez kolekcje) pola dla kolekcji obiektow pozostajacych w relacji z danym
				if(isset($relationDefinition['mapper'])) { //o ile zdefiniowano dla nich mappery
					$entity->{$relationDefinition['nameInObj']} = $this->_mapperFactory->create('RelationMapper');
					$entity->{$relationDefinition['nameInObj']}->setEntityMapperClassName($relationDefinition['mapper']);
					$entity->{$relationDefinition['nameInObj']}->setEntityRelatedMapperClassName(get_class($this)); 
					$entity->{$relationDefinition['nameInObj']}->setEntityRelatedPk($entityPk);
				}
			}
		}
		return $entity;
	}
	
	/**
	 * Wyswietla pewne informacje o obiekcie
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function __toString() {
		$str = "";
		$str .= "EntityClassName: " . $this->_entityClassName . "<br />";
		$str .= "EntityTableName: " . $this->_entityTableName . "<br />";
		$str .= "EntityName: " . $this->_entityName . "<br />";		
		$str .= "Fields: <pre>" . print_r($this->_fields,true) . "</pre><br />";		
		$str .= "VirtualFields: <pre>" . print_r($this->_virtualFields,true) . "</pre><br />";	
		$str .= "Relations: <pre>" . print_r($this->_relations,true) . "</pre><br />";		
		return $str;	
	}	
	
}

?>
