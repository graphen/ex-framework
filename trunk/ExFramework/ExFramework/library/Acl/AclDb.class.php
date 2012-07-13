<?php

/*
CREATE TABLE `resources` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) collate utf8_polish_ci NOT NULL,
  `resource` varchar(240) collate utf8_polish_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(40) collate utf8_polish_ci NOT NULL,
  `password` varchar(60) collate utf8_polish_ci NOT NULL,
  `firtname` varchar(60) collate utf8_polish_ci NOT NULL,
  `lastname` varchar(80) collate utf8_polish_ci NOT NULL,
  `sex` char(1) collate utf8_polish_ci,
  `email` varchar(100) collate utf8_polish_ci NOT NULL,
  `url` varchar(200) collate utf8_polish_ci NULL,
  `address` varchar(240) collate utf8_polish_ci NULL,
  `phone` varchar(40) collate utf8_polish_ci NULL,
  `info` text collate utf8_polish_ci NULL,
  `registerdate` datetime collate utf8_polish_ci NOT NULL,
  `code` varchar(32) collate utf8_polish_ci NOT NULL,
  `status` int(1) collate utf8_polish_ci NOT NULL DEFAULT 0,
  `lastaccess` datetime collate utf8_polish_ci NOT NULL,
  `visitcount` int(10) collate utf8_polish_ci NOT NULL default 0,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `groups` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) collate utf8_polish_ci NOT NULL,
  `info` varchar(240) collate utf8_polish_ci NOT NULL default '',
  `root` int(4) NOT NULL default 0,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `groups_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  KEY `group_id` (`group_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci; 

CREATE TABLE `groups_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  KEY `group_id` (`group_id`,`resource_id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci; 
*/

/**
 * @class AclDb
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class AclDb implements IAcl {
	
	/**
	 * Obiekt komunikacji z baza danych
	 *
	 * @var object
	 * 
	 */		
	protected $_db = null;
	
	/**
	 * Nazwa grupy anonimowej dla uzytkownikow
	 *
	 * @var string
	 * 
	 */		
	protected $_anonymousGroupName = 'guest'; 
	
	/**
	 * Wlaczenie/wylaczenie sprawdzania drzewa uprawnien
	 *
	 * @var bool
	 * 
	 */		
	protected $_checkTree = false; 
	
	/**
	 * "Sciezka" do akcji, na ktora maja zostac przekierowani niezalogowani uzytkownicy
	 *
	 * @var string
	 * 
	 */		
	protected $_loginPath = '';
	
	/**
	 * "Sciezka" do akcji na ktora maja zostac przekierowani uzytkownicy, niemajacy praw do danego zasobu
	 *
	 * @var string
	 * 
	 */		 				
	protected $_errorPath = '';					
	
	/**
	 * 
	 * Konstruktor
	 * 
	 * @param object Obiekt komunikacji z baza danych
	 * @param string Sciezka do akcji logowania do serwisu
	 * @param string Sciezka do akcji jaka ma byc wywolana po wystapieniu bledu
	 * @param string Nazwa grupy uzytkonwikow anonimowych
	 * @param bool Wlaczenie/wylaczenie sprawdzania drzewa zasobow
	 * @access public
	 * 
	 */		
	public function __construct(IDb $db, $loginPath, $errorPath, $anonymousGroupName='guest', $checkTree=false) {
		$this->_db = $db;
		
		$this->_checkTree = $checkTree;
		$this->_anonymousGroupName = $anonymousGroupName;
		
		$this->_loginPath = $loginPath;
		$this->_errorPath = $errorPath;
		
		if(empty($this->_loginPath) || empty($this->_errorPath)) {
			throw new AclException('Obiekt nie zostal prawidlowo zainicjalizowany');
		}
	}
	
	/**
	 * 
	 * Pobiera grupy uzytkownikow dla danego uzytkownika
	 * 
	 * @access public
	 * @param string Login uzytkownika
	 * @return array
	 * 
	 */			
	public function getUserGroups($userLogin=null) {
		return $this->fetchUserGroups($userLogin);
	}
	
	/**
	 * 
	 * Pobiera grupy uzytkownikow
	 * 
	 * @access public
	 * @return array
	 * 
	 */			
	public function getGroups() {
		$groups = array();
		$sql = "SELECT * FROM `groups` ORDER BY `name` ASC";
		$stm = $this->_db->prepare($sql);
		$stm->execute();
		$data = $stm->fetchAll();
		return $data;
	}
	
	/**
	 * 
	 * Pobiera zasoby
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function getResources() {
		$resources = array();
		$sql = "SELECT * FROM `resources` ORDER BY `name` ASC";
		$stm = $this->_db->prepare($sql);
		$stm->execute();
		$data = $stm->fetchAll();
		return $data;	
	}
	
	/**
	 * 
	 * Pobiera zasoby dozwolone dla danego uzytkownika
	 * 
	 * @access public
	 * @param string Login uzytkownika
	 * @return array
	 * 
	 */		
	public function getUserPermissions($userLogin=null) {
		return $this->fetchUserPermissions($userLogin);
	}
	
	/**
	 * 
	 * Sprawdza czy uzytkownik ma raco do zasobu
	 * 
	 * @access public
	 * @param string Identyfikator zasobow
	 * @param string Login uzytkownika
	 * @return bool
	 * 
	 */			
	public function isAllowed($resourceRule, $userLogin=null) {	
		
		if(empty($resourceRule)) {
			$resourceRule = '/';
		}
		$userResourcesAllowed = $this->fetchUserPermissions($userLogin); //pobranie uprawnien dla aktualnego uzytkownika
		
		if(count($userResourcesAllowed) == 0) {
			throw new AclException('Brak zdefiniowanych uprawnien');//jesli uzytkownik nie ma zadnych przyznanych uprawnien, nie ma co dalej sprawdzac
			return false;
		}
		else {
			$notRecRule = true; //zmienna pomocnicza, ustawiana na true w przypadku nieznalezenia reguly
			if(($this->_checkTree !== true)) { //jesli sprawdzana ma byc tylko konkretna regula, nie cale drzewo do niej prowadzace
				if(in_array($resourceRule, $userResourcesAllowed)) {
					$notRecRule = false;
					return true;
				}
			}			
			if(($this->_checkTree === true) || ($notRecRule === true)) { //jesli sprawdzone maja byc uprawnienia do kazdego wyzszego poziomu od poszukiwanego, lub w przypadku wczesniejszego nieznalezienia regul
				if($resourceRule == '/') {
					if(!in_array($resourceRule, $userResourcesAllowed)) {
						return false;
					}
				}
				$parts = explode('/', $resourceRule); //rozbijam regule opisujaca zasob na elementy
				$partsCount = count($parts); //sprawdzam liczbe elementow
				$resourceRulePartsAllowed = false; //na poczatku ustawiam uprawnienia sprawdzanego zasobu na false
				$resourceRuleToCheckWildcard = '/*'; //ta zmienna bedzie reprezentowala wszystkie zasoby na danym poziomie i poziomach nizej, jesli uzytkownik bedzie mial do takiego zasobu dostep to bedzie mial takze dostep do wszystkich zasobow ponizej
				$resourceRuleToCheck = '/'.$parts[0]; //sprawdzam pierwszy element
				for($i=0; $i <= $partsCount; $i++) { //petla biegnie przez wszystkie elementy reguly opisujacej zasob poczynajac od pierwszego
					if(in_array($resourceRuleToCheckWildcard, $userResourcesAllowed)) { //jesli ustawiono taka regule uzytkkownik ma dostep do wszystkich zasobow na danym poziomie i poziomach ponizej
						$resourceRulePartsAllowed = true;
						break;	
					}
					elseif(in_array($resourceRuleToCheck, $userResourcesAllowed)) { //jesli uzytkownik ma prawo do tego poziomu
						$resourceRulePartsAllowed = true; //ustawiam true
						$resourceRuleToCheckWildcard = $resourceRuleToCheck . '/*'; //teraz zmienna ta reprezentuje wszystkie zasoby na poziomie nizszym, dostep do tego zlozenia sprawdze w nastepnym przebiegu petli
						$resourceRuleToCheck .= '/' . $parts[$i+1]; // do aktualnie sprawdzanego poziomu doklejam kolejny element opisujacy zasob na poziomie nizszym i w nastepnym przebiegu sprawdzam uprawnienia do takiego zlozenia
					}				
					else {
						$resourceRulePartsAllowed = false; //w przypadku braku uprawnien do jednego ze sprawdzanych elementow, uzytkownik nie moze miec praw do dalszych elementow, ustawiam uprawnienia do calosci zasobu na false
						break;
					}
				}
				return $resourceRulePartsAllowed; //zwracam uprawniania
			}	
		}
	}
	
	/**
	 * 
	 * Sprawdza czy uzytkownik nalzey do danej grupy
	 * 
	 * @access public
	 * @param string Nazwa gruy
	 * @param string Login uzytkownika
	 * @return bool
	 * 
	 */		
	public function hasGroup($groupName, $userLogin) {
		$groups = $this->fetchUserGroups($userLogin);
		foreach($groups AS $group) {
			if($groupName == $group['name']) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 
	 * Pobiera grupy dla danego uzytkownika
	 * 
	 * @access protected
	 * @param string Login uzytkownika
	 * @return array
	 * 
	 */			
	protected function fetchUserGroups($userLogin=null) {
		$groups = array(); //tymczasowa tablica dla definicji grup uzytkownika
		if(empty($userLogin)) { //jesli brak loginu uzytkownika, tzn ze sesja jest uruchomiona dla uzytkownika anonimowego
			if(empty($this->_anonymousGroupName)) { //jesli nie zdefiniowano nazwy grupy uzytkownikow anonimowych to nie ma co szukac w bazie
				return $groups; //konczenie pracy funkcji i zwrocenie pustej tablicy
			}
			$sql = "SELECT * FROM `groups` WHERE `name`=:name LIMIT 1"; //szukam nazwy i identyfikatora grupy dla takiego uzytkownika
			$stm = $this->_db->prepare($sql);
			$stm->execute(array(':name' => $this->_anonymousGroupName));
			$groups = $stm->fetchAll(); //powinien byc tylko jeden rekord dla takiej grupy
		}
		else { //w przypadku kiedy znany jest login uzytkownika
			$sql = "SELECT `groups`.* FROM `groups`
				LEFT OUTER JOIN `groups_users` ON `groups`.`id`=`groups_users`.`group_id` 
				LEFT OUTER JOIN `users` ON `groups_users`.`user_id`=`users`.`id`
				WHERE `users`.`login`=:login ORDER BY `groups`.`name` ASC";
			$stm = $this->_db->prepare($sql);
			$stm->execute(array(':login' => $userLogin));
			$groups = $stm->fetchAll(); //tutaj moze byc wiele wynikow
		}
		return $groups; //tutaj moze byc wiele grup, kazdy uzytkownik moze nalezec do wielu grup lub do zadnej
	}		
	
	/**
	 * 
	 * Pobiera zasoby dozwolone dla danego uzytkownika
	 * 
	 * @access protected
	 * @param string Login uzytkownika
	 * @return array
	 * 
	 */		
	protected function fetchUserPermissions($userLogin=null) {
		$userResourcesAllowed = array(); //tutaj beda uprawnienia do zasobow dla uzytkownika
		$groups = $this->fetchUserGroups($userLogin);

		if(count($groups) < 1) { //jesli uzytkownik nie nalezy do zadnej grupy to nie ma co szukac
			return $userResourcesAllowed; //zwrocenie pustej tablicy uprawnien do zasobow
		}
		elseif(count($groups) > 1) { //jesli uzytkownik nalezy do wielu grup
			$groupIds = array();
			foreach($groups AS $i=>$g) {
				$groupIds[':gid_'.$i] = $g['id'];
			}
			$groupIdsIndexes = array_keys($groupIds);
			$groupIdsIndexesString = implode(', ', $groupIdsIndexes);
			$sql = "SELECT `resources`.`resource` FROM `resources` LEFT OUTER JOIN `groups_resources` ON `resources`.`id`=`groups_resources`.`resource_id` LEFT OUTER JOIN `groups` ON `groups`.`id`=`groups_resources`.`group_id` WHERE `group_id` IN(" . $groupIdsIndexesString . ") ORDER BY `resources`.`resource` ASC";
			$stm = $this->_db->prepare($sql);
			$stm->execute($groupIds); //uzyskanie uprawnien dla grup do ktorych nalezy uzytkownik
		}
		else { //jesli uzytkownik nalezy tylko do jednej grupy
			$sql = "SELECT `resources`.`resource` FROM `resources` LEFT OUTER JOIN `groups_resources` ON `resources`.`id`=`groups_resources`.`resource_id` LEFT OUTER JOIN `groups` ON `groups`.`id`=`groups_resources`.`group_id` WHERE `group_id`=:groupId ORDER BY `resources`.`resource` ASC";
			$stm = $this->_db->prepare($sql);
			$group = current($groups);
			$stm->execute(array(':groupId'=>$group['id'])); //uzyskanie uprawnien dla tej grupy
		}
		$data = $stm->fetchAll();
		foreach($data AS $perm) {
			$userResourcesAllowed[] = $perm['resource'];
		}
		return $userResourcesAllowed; //zwrocenie tablicy uprawnien, jesli znaleziono jakies
	}
	
	/**
	 * 
	 * Zwraca sciezke do akcji logowania
	 * 
	 * @access public
	 * @return string
	 * 
	 */			
	public function getLoginPagePath() {
		return $this->_loginPath;
	}
	
	/**
	 * 
	 * Zwraca sciezke do akcji jaka ma wystapic po napotkaniu bledu
	 * 
	 * @access public
	 * @return string
	 * 
	 */			
	public function getErrorPagePath() {
		return $this->_errorPath;
	}
	
}

?>
