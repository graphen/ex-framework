<?php

/**
 * @class Router
 *
 * @author Przemyslaw Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Router implements IRouter {
	
	/**
	 * Instancja obiektu impl. IRequest
	 *
	 * @var object
	 * 
	 */	
	protected $_request = null;
	
	/**
	 * Sposob uzyskania danych
	 *
	 * @var string
	 * 
	 */	
	protected $_urlMode = '_REQUEST_URI';
	
	/**
	 * Czy uzywac tablicy asocjacyjnej przy budowaniu zapytania
	 *
	 * @var bool
	 * 
	 */		
	protected $_useAssoc = true;
	
	/**
	 * Czy uzywac suffiksu do budowania zaytania i podczas uzyskiwania danych z adresu
	 *
	 * @var string
	 * 
	 */	
	protected $_urlSuffix = '.html';
	
	/**
	 * Nazwa domyslnego kontrolera
	 *
	 * @var string
	 * 
	 */	
	protected $_defaultController = 'index';
	
	/**
	 * Nazwa domyslnej akcji
	 *
	 * @var string
	 * 
	 */	
	protected $_defaultAction = 'index';
	
	/**
	 * Tablica przekierowan
	 *
	 * @var array
	 * 
	 */	
	protected $_routes = array();	
	
	/**
	 * Ciag stanowiacy zapytanie HTTP
	 *
	 * @var string
	 * 
	 */	
	protected $_queryString = '';
	
	/**
	 * Ciag zapytania po przetworzeniu przekierowania
	 *
	 * @var string
	 * 
	 */	
	protected $_rString = '';
	
	/**
	 * Segmenty zapytania
	 *
	 * @var array
	 * 
	 */	
	protected $_querySegments = array();
	
	/**
	 * Tablica segmentow zapytania po przetworzeniu przekierowan
	 *
	 * @var array
	 * 
	 */	
	protected $_rSegments = array();
	
	/**
	 * Nazwa hosta
	 *
	 * @var string
	 * 
	 */	
	protected $_host = '';
	
	/**
	 * Sciezka do katalogu w ktorym jest wykonywany skrypt
	 *
	 * @var string
	 * 
	 */		
	protected $_dirName = '';
	
	/**
	 * Nazwa pliku skryptu
	 *
	 * @var string
	 * 
	 */		
	protected $_baseName = '';
	
	/**
	 * Nazwa kontrolera
	 *
	 * @var string
	 * 
	 */		
	protected $_controller = '';
	
	/**
	 * Nazwa akcji
	 *
	 * @var string
	 * 
	 */		
	protected $_action = '';

	/**
	 * Nazwa klucza kontrolera w tablicy _GET
	 *
	 * @var string
	 * 
	 */		
	protected $_controllerKey = 'c';
	
	/**
	 * Nazwa klucza akcji w tablicy _GET
	 *
	 * @var string
	 * 
	 */		
	protected $_actionKey = 'a';
	
	/**
	 * Tablica argumentow
	 *
	 * @var array
	 * 
	 */		
	protected $_args = array();
		
	/**
	 * Wlaczenie/wylaczenie dostepu do obszarow
	 *
	 * @var bool
	 * 
	 */			
	protected $_areasEnabled = true;
	
	/**
	 * Nazwa klucza wskazujacego na obszar w tablicy _GET
	 *
	 * @var string
	 * 
	 */			
	protected $_areaKey = 'ar';
	
	/**
	 * Tablica nazw obszarow
	 *
	 * @var array
	 * 
	 */			
	protected $_areas = array();
	
	/**
	 * Nazwa obszaru
	 *
	 * @var string
	 * 
	 */			
	protected $_area = null;
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt implementujacy interfejs IRequest
	 * @param string Sposob uzyskiwania informacji z adresu URL
	 * @param string Suffix URL
	 * @param bool Czy w adresie uzywana jest tablica asocjacyjna
	 * @param string Klucz dla kontrolera w tablicy _GET
	 * @param string Klucz dla akcji w tablicy _GET
	 * @param string Domyslny kontroler
	 * @param string Domyslna akcja
	 * @param bool Wlaczenie/wylaczenie dostepu do obszarow
	 * @param string Klucz dla obszaru w tablicy _GET
	 * @param mixed Nazwy uzywanych obszarow, tablica badz ciag nazw przedzielonych przecinkami
	 * @param array Definicje statyczne sciezek
	 * 
	 */	
	public function __construct(IRequest $request, $urlMode='', $urlSuffix='', $useAssoc=true, $controllerKey='', $actionKey='', $defCtrl='', $defAct='', $areasEnabled=true, $areaKey='', $areas=null, $routes=null) {

		$this->_request = $request;
		if(!empty($urlMode)) {
			$this->_urlMode = (string)$urlMode;
		}		
		if(!empty($urlSuffix)) {
			$this->_urlSuffix = (string)$urlSuffix;
		}
		$this->_useAssoc = (bool)$useAssoc;
		if(!empty($controllerKey)) {
			$this->_controllerKey = (string)$controllerKey;
		}
		if(!empty($actionKey)) {
			$this->_actionKey = (string)$actionKey;
		}
		if(!empty($defCtrl)) {
			$this->_defaultController = (string)$defCtrl;
		}
		if(!empty($defAct)) {
			$this->_defaultAction = (string)$defAct;
		}
		
		$this->_areasEnabled = (bool)$areasEnabled;	
		if(!empty($areaKey)) {
			$this->_areaKey = (string)$areaKey;
		}
		if($areas !== null) {
			if(is_array($areas)) {
				$this->_areas = $areas;
			}
			else {
				$this->_areas = explode(',', $areas);
			}
		}
		if(!empty($routes) && is_array($routes)) {
			$this->_routes = $routes;
		}
		
		//Pobranie domeny i nazw katalogu oraz wykonywanego pliku
		$this->_host = $this->_request->getHost();
		$this->_dirName = $this->_request->getScriptFolder();
		$this->_baseName = $this->_request->getScriptName();		
		
		//$this->route();
	}
	
	/**
	 * Uzyskuje sciezke wyszukiwania, w przypadku kiedy nie korzysta sie z tablicy $_GET 
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function fetchQueryString() {
		if($this->_urlMode == '_GET') { //Jesli tryb wyszukiwania sciezki ustawiony jest na _GET, to nie bedzie ciagu wyszukiwania jaki jest tu poszukiwany
			return '';
		}
		//Proba uzyskania sciezek wyszukiwania z kolejnych zmiennych tabllicy $_SERVER, lub $_GET
		$tmpArgsStr = '';
		if($this->_urlMode == '_QUERY_STRING' || $this->_urlMode == '_REQUEST_URI') {
			if(is_array($_GET) && (count($_GET) == 1) && (trim(key($_GET), '/') != '')) { //Jesli jest tablica $_GET najlatwiej lancuch wyszukiwania pobrac z jej pierwszego klucza
				$tmpArgsStr = trim(key($_GET), '/');
			}
		}
		if($this->_urlMode == '_PATH_INFO') {
			$tmpStr = $this->_request->server('PATH_INFO');
			if(trim($tmpStr, '/') != '') {
				$tmpArgsStr = trim($tmpStr, '/');					
			}
		}
		if(($this->_urlMode == '_QUERY_STRING' && $tmpArgsStr == '') || $this->_urlMode == '_MOD_REWRITE') {
			$tmpStr = $this->_request->server('QUERY_STRING');
			if(trim($tmpStr, '/') != '') {
				$tmpArgsStr = trim($tmpStr, '/');			
			}				
		}
		if($this->_urlMode == '_REQUEST_URI' && $tmpArgsStr == '') {
			$requestUri = '';
			$scriptName = '';
			$requestUri = $this->_request->server('REQUEST_URI');
			$scriptName = $this->_request->server('SCRIPT_NAME');
			if($requestUri != '' && $scriptName != '') {
				$tmpStr = preg_replace('|' . $scriptName . '|', '', $requestUri); //Ciag wyszukiwania to czesc REQUEST_URI, tutaj to jest wyciagane
				$tmpStr = preg_replace('|//|', '', $tmpStr);
				if(trim($tmpStr, '/') != '') {
					$tmpArgsStr = trim($tmpStr, '/');
				}				
			}
		}
		//Jesli w ponizszych trybach sciezka wyszukiwania bylaby w formacie takim jak w trybie _GET, zostanie usunieta
		if($this->_urlMode == '_QUERY_STRING' || $this->_urlMode == '_REQUEST_URI') { 
			if(!strstr($tmpArgsStr, '/') && strstr($tmpArgsStr, '&')) {
				$tmpArgsStr = '';
			}
		}		
		$this->_queryString = $tmpArgsStr; //Zapamietanie sciezki wyszukiwania
	} 
	
	/**
	 * Usuwa suffix 
	 * 
	 * @access protected
	 * @param string Adres lub sciezka wyszikiwania z suffixem na koncu
	 * @return string
	 * 
	 */
	protected function removeSuffix($var) {
		if($this->_urlSuffix != '') {
			$var = preg_replace('|' . $this->_urlSuffix . '$|', '', $var);
		}
		return $var;		
	}

	/**
	 * Rozbija sciezke wyszukiwania na tablice elementow, wykonuje czyszczenie elementow 
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function explodeQueryString() {
		//Sciezka wyszukiwania zostaje podzielona na segmenty
		$tmpArgsArray = explode('/', $this->_queryString);
		$tmpArgsArrayCount = count($tmpArgsArray);
		for($i=0; $i<$tmpArgsArrayCount; $i++) {
			$segment = trim($tmpArgsArray[$i]);
			if($segment != '') {
				$this->_querySegments[$i] = $segment; //Zapamietanie segmentu o ile nie jest pusty w tablicy segmentow
			}
		}		
	}
	
	/**
	 * Uzyskuje nazwe kontrolera, akcji oraz liste parametrow z tablicy elementow sciezki wyszukiwania
	 * 
	 * @access protected
	 * @param array Tablica elementow sciezki wyszukiwania
	 * @return void
	 * 
	 */	
	protected function fetchCaaFromSegments(Array $segments) {
		$controllerIndex = 0;
		$actionIndex = 1;
		$specialSegments = 2; //liczba segmentow specjalnych
		//Szukanie obszaru, kontrolera, akcji i parametrow
		if((isset($segments[0])) && ($this->_areasEnabled == true)) {
			$area = $segments[0];
			if(in_array($area, $this->_areas)) { //jesli nazwa obszaru zostala wczesniej zdefiniowana to nia jest w przeciwnym razie jest to pewnie nazwa kontrolera lub jakis losowy ciag znakow, ktory obsluzymy pozniej
				$this->_request->setQuery($this->_areaKey, $segments[0]);
				$this->_area = $area; ///////////
				$controllerIndex = 1;
				$actionIndex = 2;
				$specialSegments = 3; //liczba segmentow specjalnych
			}
		} 
		if(isset($segments[$controllerIndex])) { //Kontroler musi byc na miejscu pierszym w tablicy, chyba ze wykryto zadanie wejscia do panelu adm.
			$this->_request->setQuery($this->_controllerKey, $segments[$controllerIndex]); ///////////
			$this->_controller = $segments[$controllerIndex];
			if(isset($segments[$actionIndex])) { //Akcja musi byc na miejscu drugim w tablicy, chyba ze wykryto zadanie dostepu do panelu adm.
				$this->_request->setQuery($this->_actionKey, $segments[$actionIndex]); ////////////
				$this->_action = $segments[$actionIndex];
				if(count($segments) > $specialSegments) { //Jesli jest wiecej elementow w tablicy to sa to argumenty 
					if($this->_useAssoc == false) { //Jesli nie jest uzywana forma adresow, gdzie kazdy parametr ma przed soba swoja nazwe
						$this->_args = array_slice($segments, $specialSegments); //Po prostu nastepuje przypisanie argumentow do tablicy argumentow, bez pierszych dwoch elementow
					}
					else { //Jesli adresy maja wartosci parametrow z poprzedzajacymi nazwami
						$tmpArr = array_slice($segments, $specialSegments); //Usuniecie dwoch elementow dla kontroletra i akcji
						$count = count($tmpArr); 
						if($count % 2) $count -= 1; 
						for($i = 0; $i < $count; $i += 2) {
							$this->_request->setQuery($tmpArr[$i], $tmpArr[$i+1]); ///////////
							$this->_args[$tmpArr[$i]] = $tmpArr[$i+1]; //Zapamietanie elementu w tablicy argumentow
						}
					}
				}
			}
		}
		//$this->fetchCaaFromGet();	//////////////?
	}
	
	/**
	 * Uzyskuje nazwe kontrolera, akcji oraz liste parametrow z tablicy $_GET 
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function fetchCaaFromGet() {
		//Pobranie z konfiguracji nazw indeksow dla kontrolera, akcji i panelu adm
		if($this->_controllerKey == null || $this->_actionKey == null || $this->_areaKey == null) { //Jesli ich nie zdefiniowano nie ma na czym pracowac
			throw new RouterException('Brak zmiennych konfiguracyjnych z nazwami kluczy dla kontrolera, akcji i obszaru');
		}
		//Sprawdzenie czy nie jest wymagany dostep do obszaru
		if($this->_areasEnabled == true) {
			$area = $this->_request->get($this->_areaKey);
			if(($area != null) && (in_array($area, $this->_areas))) {
				$this->_area = $area;
			}
		}		
		//Ustawienie wartosci kontrolera i akcji oraz tablicy dodatkowych parametrow, po wczesniejszym sprawdzeniu
		$this->_controller = $this->_request->get($this->_controllerKey); //Proba pobrania nazwy kontrolera
		if($this->_controller != null && $this->_controller != '') { //Jesli znaleziono nazwe kontrolera
			$this->_action = $this->_request->get($this->_actionKey); //Proba pobrania nazwy akcji
			if($this->_action != null && $this->_action != '') { //Jesli znaleziono nazwe akcji
				$args = $this->_request->get(); //Pobranie calej tablicy $_GET
				foreach($args as $key => $arg) { //Jesli istnieja jakies parametry
					if($key != $controllerKey && $key != $actionKey) { //I nie sa to nazwa kontrolera oraz akcji
						$this->_args[$key] = $arg; //Pobranie parametrow
					}
				}
			}
		}		
	}
	
	// Metody publiczne

	/**
	 * Wykonuje analize sciezki wyszukiwania z URLa, dopasowuje do tras zdefiniowanych prze uzytkownika, 
	 * wybiera sciezke docelowa
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function route() {
		//Jesli polegamy na tablicy _GET i stamtad pobieramy dane konieczne do wyznaczenia sciezki
		//od razu szukamy kontrolera, akcji i parametrow
		if($this->_urlMode == '_GET') {
			$this->fetchCaaFromGet();
		}
		//W przypadku kiedy tablica _GET jest pusta, albo zawiera tylko jeden element trzeba pobrac caly ciag parametrow odzielonych znakiem /.
		if($this->_urlMode == '_PATH_INFO' || $this->_urlMode == '_QUERY_STRING' || $this->_urlMode == '_REQUEST_URI' || $this->_urlMode == '_MOD_REWRITE') {
			//Uzyskanie sciezki wyszukiwania
			$this->fetchQueryString();
			//Usuniecie suffixu z ciagu URI jesli istnieje
			$this->_queryString = $this->removeSuffix($this->_queryString);
			//Jesli sciezka wyszukiwania nie jest pusta dalsze prace odbywaja sie na niej
			if($this->_queryString != '') {
				//Sciezka wyszukiwania zostaje podzielona na segmenty, w tym czasie przeprowadzane jest sprawdzanie poprawnosci
				$this->explodeQueryString();
				//Sprawdzenie czy sa trasy zdefiniowane przez uzytkownika w pliku konfiguracyjnym
				//Jesli sa jakies zdefiniowane trasy, porownanie ich z biezacym adresem i ewentualnie przekierowanie na inna trase
				if(count($this->_routes) > 0) {
					//Ponowne laczenie segmentow URI w ciag, jest on juz sprawdzony i nie zawiera na pewno niedopuszczalnych znakow
					$uriStr = implode('/', $this->_querySegments); 
					$routeStr = ''; //finalna trasa po dopasowaniu do wyrazenia zdefiniowanego w pliku konfiguracyjnym
					foreach($this->_routes AS $el) {
						$expr = str_replace(':any', '.+', $el['expr']); //W zdefiniowanych trasach podmienia sie nazwy specjalne ich odpowiednikami uzywanymi w wyrazeniach regularnych
						$expr = str_replace(':num', '[0-9]+', $expr);
						$route = trim($this->removeSuffix($el['route']), '/'); //Usuniecie suffiksu o ile jest z zdefiniowanej trasy
						if(preg_match('|^' . $expr . '$|', $uriStr)) { //jesli znaleziono wyrazenie w ciagu wyszukiwania
							if(strstr($route, '$') && strstr($expr, '(') && strstr($expr, ')')) { //sprawdzenie czy w dopasowanej trasie nie trzeba czegos zamienic
								$routeStr = preg_replace('|^' . $expr . '$|', $route, $uriStr); //zapamietanie nowej trasy, po podmianie
								break;
							}
							$routeStr = $route; //jesli nie trzeba nic zamieniac zapamietanie nowej trasy
							break;
						}
					}
					if($routeStr == '') { //jesli nie dopasowano zadnej trasy
						$routeStr = $uriStr; //zapamietanie trasy z adresu
					}					
					$this->_rString = $routeStr; //Zapamietanie nowej dopasowanej trasy
					//Rozbicie znalezionego ciagu na segmenty i zapamietanie w tablicy
					$this->_rSegments = explode('/', $routeStr); //Jesli nie dopasowano zadnej trasy tablica rSegments jest rowna tablicy querySegments
					//Szukanie kontrolera, akcji i parametrow
					$this->fetchCaaFromSegments($this->_rSegments);
				}
				//Jesli nie bylo zdefiniowanych drog w pliku konfiguracyjnym mozna sprobowac wykryc kontroler, akcje i parametry korzystajac z wzorca (area/)*kontroler/akca/parametr1/parametr2 lub o ile wlaczono tablice asocjacyjne (area/)*kontroler/akcja/index1/parametr1/index2/parametr2..
				if((count($this->_rSegments) == 0) && ($this->_controller == '')) { //do tej pory nie dopasowano kontrolera i nie bylo zdefiniowanych drog
					$this->fetchCaaFromSegments($this->_querySegments);
				}
			}		
		}
		//Sprawdznie czy ustawiono nazwe kontrolera i akcji, jesli do tej pory sie nie udalo ustawienie nazw domyslnych
		if($this->_controller == '') {			
			$this->_controller = $this->_defaultController;
		}
		if($this->_action == '') {
			$this->_action = $this->_defaultAction;
		}
	}
			
	/**
	 * Zwraca adres hosta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getHost() {
		return $this->_host;
	}

	/**
	 * Zwraca sciezke w ktorej znajduje sie plik do ktorego trafilo zadanie
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getDirName() {
		return $this->_dirName;
	}
	
	/**
	 * Zwraca nazwe wykonywanego pliku, do ktorego trafilo zadanie
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getBaseName() {
		return $this->_baseName;
	}		
	
	/**
	 * Zwraca nazwe kontrolera
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getController() {
		return $this->_controller;
	}
	
	/**
	 * Zwraca nazwe akcji
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getAction() {
		return $this->_action;
	}
	
	/**
	 * Zwraca tablice parametrow
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getArgs() {		
		return $this->_args;
	}

	/**
	 * Zwraca nazwe obszaru lub null
	 * 
	 * @access public
	 * @return string|null
	 * 
	 */	
	public function getArea() {		
		return $this->_area;
	}
	
	/**
	 * Zwraca ciag wyszukiwan pobrany z adresu URL
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getQueryString() {
		return $this->_queryString;
	}
	
	/**
	 * Zwraca tablice z elementami sciezki pobranej z adresu URL
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getQuerySegments() {
		return $this->_querySegments;
	}

	/**
	 * Zwraca ciag wyszukiwan uzyskany po dopasowaniu drogi zdefiniowanej przez uzytkownika
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getRString() {
		return $this->_rString;
	}
	
	/**
	 * Zwraca tablice z elementami sciezki na ktora nastapilo przekierowanie
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getRSegments() {
		return $this->_rSegments;
	}

	/**
	 * Tworzy ciag wyszukiwan dla adresu URL
	 * 
	 * @access public
	 * @param string Nazwa kontrolera
	 * @param string Nazwa akcji
	 * @param array Tablica parametrow
	 * @param string Nazwa obszaru
	 * @return string
	 * 
	 */	
	public function makeQueryString($controller=null, $action=null, $args=null, $area=null) {
		//Jesli jest tablica parametrow obliczenie ich ilosci
		$argsCount = 0;
		if(isset($args) && is_array($args)) {
			$argsCount = count($args);		
		}
		
		//Budowanie sciezki wyszukiwania poprzez dodanie nazwy kontrolera, akcji i nazw oraz wartosci parametrow
		$queryString = '';
		//W przypadku kiedy dostepna i uzywana jest tablica $_GET 
		if($this->_urlMode == '_GET') { 
			if($controller !== null) { //Jesli podano nazwe kontrolera
				$controllerKey = $this->_controllerKey; //Pobranie klucza identyfikujacego nazwe kontrolera
				$controllerStr = '?' . $controllerKey . '=' . $controller; 
				$queryString .= $controllerStr; //Dodanie nazwy kontrolera do sciezki wyszukiwania
				if($action !== null) { //Jesli podano nazwe akcji
					$actionKey = $this->_actionKey; //Pobranie klucza identyfikujacego nazwe akcji
					$actionStr = '&amp;' . $actionKey . '=' . $action;
					$queryString .= $actionStr; //Dodanie nazwy akcji do sciezki wyszukiwania
					if($argsCount != 0) { //Jesli sa jakies parametry 
						foreach($args as $key => $value) { 
							$queryString .= '&amp;' . $key . '=' . $value; //Dopisanie ich do sciezki wyszukiwania
						}
					}
				}
			}
			if($area !== null) {
				$areaKey = $this->_areaKey;
				$areaValue = $area;
				if($controller == null) {
					$queryString .= '?' . $areaKey . '=' . $areaValue;
				}
				else {
					$queryString .= '&amp' . $areaKey . '=' . $areaValue;					
				}
			}
		}
		//W przypadku niekorzystania z tablicy $_GET	
		if($this->_urlMode == '_QUERY_STRING' || $this->_urlMode == '_PATH_INFO' || $this->_urlMode == '_REQUEST_URI' || $this->_urlMode == '_MOD_REWRITE') {
			$queryString = '';
			if($area !== null) {
				if($this->_urlMode == '_QUERY_STRING') {
					$areaStr = '?' . $area;
				}
				else {
					$areaStr = '/' . $area;
				}
				$queryString .= $areaStr;
			}
			if($controller !== null) { //Jesli podano nazwe kontrolera
				if($this->_urlMode == '_QUERY_STRING'  && $area === null) { //Jesli korzystamy z QUERY_STRING to znak oddzielajacy sciezke wyszukiwania to '?', w innym wypadku '/'
					$controllerStr = '?' . $controller; 
				}
				else {
					$controllerStr = '/' . $controller;
				}
				$queryString .= $controllerStr; //Dodanie nazwy kontrolera do sciezki wyszukiwania
				if($action !== null) { //Jesli podano nazwe akcji
					$actionStr = '/' . $action; 
					$queryString .= $actionStr; //Dodanie nazwy akcji do sciezki wyszukiwania
					if($argsCount !== 0) { //Jesli sa jakies parametry 
						if($this->_useAssoc === true) { //Jesli ciag parametrow w sciezce ma odzwierciedlac tablice asocjacyjna
							foreach($args as $key => $value) {
								$queryString .= '/' . $key . '/' . $value; //Dopisanie ich oraz ich kluczy do sciezki wyszukiwania
							}
						}
						else {
							foreach($args as $value) { //Jesli parametry beda tylko wartosciami tablicy
								$queryString .= '/' . $value; //Dopisanie ich do sciezki wyszukiwania
							}
						}
					}
				}
			}
		}
		return $queryString;		
	}
		
	/**
	 * Tworzy adres URL
	 * 
	 * @access public
	 * @param string Nazwa kontrolera
	 * @param string Nazwa akcji
	 * @param array Tablica parametrow
	 * @param string URL bez sciezki wyszukiwania
	 * @param string Protokol
	 * @param string Nazwa hosta
	 * @param integer Numer portu
	 * @param string Sciezka do katalogu, moze zawierac nazwe pliku
	 * @param string Nazwa pliku, moze byc zawarta w sciezce do katalogu
	 * @param string Nazwa obszaru
	 * @return string
	 * 
	 */	
	public function makeUrl($controller=null, $action=null, $args=null, $url=null, $proto=null, $host=null, $port=null, $dirName=null, $baseName=null, $area=null) {
		//Tworzenie czesci bazowej URLa
		$urlString = '';
		//Pobranie protokolu i nazwy hosta z konfiguracji i dodanie ewentualnie nazwy katalogu i pliku
		if($url === null) {		
			if($proto === null) { //Jesli podano protokol
				$proto = 'http';
			}
			if($host === null) { //Jesli podano adres hosta
				$host = $this->_host;
			}
			$urlString = $proto . '://' . $host;
			if($dirName != null) { //Jesli podano sciezke do katalogu gdzie jest plik do wykonania
				$dirName = '/' . trim($dirName, '/');
				///$dirName = rtrim($dirName, '&');
				$urlString .= $dirName;
			}
			if($baseName !== null) { //Jesli podano nazwe pliku do wykonania
				if($this->_urlMode == '_GET' || $this->_urlMode == '_QUERY_STRING' || $this->_urlMode == '_PATH_INFO' || $this->_urlMode == '_REQUEST_URI') {
					$baseName = '/' . trim($baseName, '/');
					///$baseName = rtrim($baseName, '&');
					if(!preg_match('|'. $baseName .'|', $urlString)) {
						$urlString .= $baseName;						
					}
				}
			}
			if($port !== null) { //Jesli podano numer portu
				$urlString .= ':' . $port;
			}
		}
		//Jesli czesc bazowa zostala przekazana jako parametr, tylko dodanie protokolu, jesli go nie ma
		else {
			if(!preg_match('|^[a-z]+://|', $url)) {
				$proto = 'http';
				$url = $proto . '://' . $url;
			}
			$url = rtrim($url, '/');
			$urlString = $url;
		}
		//Dopisanie sciezki wyszukiwania
		$urlString .= $this->makeQueryString($controller, $action, $args, $area);
		//Dodanie suffixa
		if($this->_urlSuffix != '') {
			$urlString .= $this->_urlSuffix;
		}
		return $urlString;
	}
	
	/**
	 * Wyswietla informacje
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function __toString() {
		$out = "";
		$out .= "<br />==INFO==========================================<br /><br />\n";
		$out .= "URL Mode: " . $this->_urlMode . "<br />\n";
		$out .= "Use Assoc: " . (int)$this->_useAssoc . "<br />\n";
		$out .= "Suffix: " . $this->_urlSuffix . "<br />\n";
		$out .= "Default Controller: " . $this->_defaultController . "<br />\n";
		$out .= "Default Action: " . $this->_defaultAction . "<br />\n";
		$out .= "Query string: " . $this->_queryString . "<br />\n";
		$out .= "rString: " . $this->_rString . "<br />\n";
		$out .= "Host: " . $this->_host . "<br />\n";
		$out .= "Dir  Name: " . $this->_dirName . "<br />\n";
		$out .= "BaseName: " . $this->_baseName . "<br />\n";
		$out .= "Controller: " . $this->_controller . "<br />\n";
		$out .= "Action: " . $this->_action . "<br />\n";
		$out .= "Obszary wlaczone: " . (int)$this->_areasEnabled . "<br />\n";
		$out .= "Obszar: " . $this->_area . "<br /><br />\n";		
		$out .= "Tablica args: <br />\n";
		$out .= "<pre>";
		$out .= print_r($this->_args,true);
		$out .= "</pre>";
		$out .= "Tablica uriSegments: <br />\n";
		$out .= "<pre>";
		$out .= print_r($this->_querySegments,true);
		$out .= "</pre>";
		$out .= "Tablica rSegments: <br />\n";
		$out .= "<pre>";
		$out .= print_r($this->_rSegments,true);
		$out .= "</pre>";
		$out .= "Tablica routes: <br />\n";		
		$out .= "<pre>";
		$out .= print_r($this->_routes,true);
		$out .= "</pre>";	
		$out .= "Tablica _GET: <br />\n";		
		$out .= "<pre>";
		$out .= print_r($this->_request->get(),true);
		$out .= "</pre>";	
		$out .= "<br /><br />================================================<br /><br />\n";			
		return $out;
	}
	
}

?>
