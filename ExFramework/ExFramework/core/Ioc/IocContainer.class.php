<?php

/**
 * @class IocContainer
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 * 
 */
class IocContainer implements IIocContainer {
	
	/**
	 * Mapa zaleznosci klas
	 *
	 * @var array
	 * 
	 */		
	protected $_appMap = null;
	
	/**
	 * Tablica zawierajaca obiekty, ktore maja byc tworzone jako pojedyncze instancje, zwykle nie sa to singletony!
	 *
	 * @var array
	 * 
	 */		
	static protected $_objects = array();

	/**
	 * Tablica zawierajaca powiazania nazw zmiennych bedacych nazwami argumentow konstruktora i setterow z ich wartosciami. Nazwy poprzedzone sa znakiem :
	 *
	 * @var array
	 * 
	 */			
	protected $_binded = array();
	
	/**
	 * 
	 * AutoLoader wlaczony/wylaczony
	 *
	 * @var bool
	 * 
	 */			
	protected $_useAutoLoader = false;	
	
	
	/**
	 * 
	 * Konstruktor
	 * 
	 * @param object IocAppMap Mapa klas aplikacji 
	 * @param bool Ustawia czy Ioc uzywa autoloadera
	 * @access public
	 * 
	 */		
	public function __construct(IocAppMap $map, $useAutoLoader=false, $config=null) {
		$this->_appMap = $map;
		$containerClassName = get_class($this);
		$this->_objects[$containerClassName] = $this;
		$this->_useAutoLoader = $useAutoLoader;
		if($config !== null) {
			if(is_array($config) && (count($config)==2)) {
				$key = $config[0];
				$object = $config[1];
				$this->_objects[$key] = $object;
			}
		}
	}
	
	/**
	 * 
	 * Zwraca mape aplikacji
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function getAppMap() {
		return $this->_appMap;
	}
	
	/**
	 * 
	 * Zwraca nazwe argumentu
	 * 
	 * @access public
	 * @param object IocAppMap Mapa aplikacji
	 * @return void
	 * 
	 */		
	public function setAppMap(IocAppMap $map) {
		$this->_appMap = $map;
	}
	
	/**
	 * 
	 * Dodaje do tablicy powiazan nowe powiazanie nazwy argumentu z jego wartoscia
	 * 
	 * @access public
	 * @param string Nazwa argumentu konstruktora lub settera
	 * @param mixed Wartosc tego argumentu
	 * @return void
	 * 
	 */		
	public function bind($var, $value) {
		$this->_binded[$var] = $value;
	}
	
	/**
	 * 
	 * Tworzy obiekty wraz z wszystkimi zaleznosciami
	 * 
	 * @access public
	 * @param string Nazwa klasy
	 * @return object
	 * 
	 */		
	public function create($className) {

		$containerClassName = get_class($this);
		if($className == $containerClassName) {
			return $this;
		}		
		$classMap = $this->_appMap->getClassMap($className);
		if($classMap->isSingleton()) {
			if(isset($this->_objects[$className])) {
				return $this->_objects[$className];
			}
			else {
				$object = $this->createObject($classMap);
				$this->_objects[$className] = $object;
				return $object;
			}
		}
		else {	
			$object = $this->createObject($classMap);
			return $object;
		}
	}
	
	/**
	 * 
	 * Tworzy zadany obiekt wraz z wszystkimi zaleznosciami
	 * 
	 * @access protected
	 * @param object IocClassMap Mapa klasy
	 * @return object
	 * 
	 */	
	protected function createObject(IocClassMap $cMap) {
		if($this->_useAutoLoader === false) {
			$interfaceName = $cMap->getInterfaceName();
			if(($interfaceName != '') && ($interfaceName != null)) {
				if(is_array($interfaceName)) {
					foreach($interfaceName AS $index=>$intName) {
						if(!interface_exists($intName)) {
							$intFile = $cMap->getInterfaceFile();
							if($intFile != '' && $intFile != null) { //jeszcze sprzwdzic czy pliki istnieja
								if(is_array($intFile)) {
									if(!file_exists($intFile[$index])) {
										throw new IocException('Plik interfejsu: ' . $intFile[$index] . ' nie istnieje');
									}
									require_once($intFile[$index]);
									if(!interface_exists($intFile)) {
										throw new IocException('Plik: ' . $intFile[$index] . ' nie zawieral definicji interfejsu: ' . $intName);
									}
								}
								else {
									throw new IocException('Nie podano sciezek do wszystkich plikow interfejsow');
								}
							}
							else {
								throw new IocException('Zdefiniowano interfejsy ale nie podano sciezek do ich plikow');
							}
						}
					}
				}
				else {
					if(!interface_exists($interfaceName)) {
						$interfaceFile = $cMap->getInterfaceFile();
						if($interfaceFile != '' && $interfaceFile != null) { //jeszcze sprzwdzic czy pliki istnieja
							if(!file_exists($interfaceFile)) {
								throw new IocException('Plik interfejsu: ' . $interfaceFile . ' nie istnieje');
							}
							require_once($interfaceFile);
						}
						else {
							throw new IocException('Zdefiniowano interfejs: ' . $interfaceName . ' ale nie podano sciezki do jego pliku');
						}
					}
				}
			}
			$abstractClassName = $cMap->getParentClassName();
			if(($abstractClassName != '') && ($abstractClassName != null)) {
				 //UWAGA! Klasy musza byc ladowane od najbardziej ogolnej i w takiej kolejnosci musza byc w konfiguracji
				if(is_array($abstractClassName)) {
					foreach($abstractClassName AS $index=>$abClsName) {
						if(!class_exists($abClsName)) {
							$abClsFile = $cMap->getParentClassFile();
							if($abClsFile != '' && $abClsFile != null) { //jeszcze sprawdzic czy pliki istnieja
								if(is_array($abClsFile)) {
									if(!file_exists($abClsFile[$index])) {
										throw new IocException('Plik klasy rodzica: ' . $abClsFile[$index] . ' nie istnieje');
									}
									require_once($abClsFile[$index]);
									if(!class_exists($abClsName)) {
										throw new IocException('Plik: ' . $abClsFile[$index] . ' nie zawieral definicji klasy: ' . $abClsName);
									}
								}
								else {
									throw new IocException('Nie podano sciezek do wszystkich plikow klas rodzicow');
								}
							}
							else {
								throw new IocException('Zdefiniowano klasy rodzicow ale nie podano sciezek do ich plikow');
							}
						}
					}
				}
				else {
					if(!class_exists($abstractClassName)) {
						$abstractClassFile = $cMap->getParentClassFile();
						if($abstractClassFile != '' && $abstractClassFile != null) { //jeszcze sprzwdzic czy pliki istnieja
							if(!file_exists($abstractClassFile)) {
								throw new IocException('Plik klasy abstrakcyjnej: ' . $abstractClassFile . ' nie istnieje');
							}
							require_once($abstractClassFile);
						}
						else {
							throw new IocException('Zdefiniowano klase rodzica: ' . $abstractClassName . ' ale nie podano sciezki do jej pliku');
						}
					}
				}
			}
		}
		$className = $cMap->getClassName();
		if($className == '' || $className == null) {
			throw new IocException('Nie zdefiniowano klasy obiektow');
		}
		if($this->_useAutoLoader === false) {
			if(!class_exists($className)) {
				$classFile = $cMap->getClassFile();
				if($classFile != '' && $classFile != null) {
					if(!file_exists($classFile)) {
						throw new IocException('Plik klasy: ' . $classFile . ' nie istnieje');
					}				
					require_once($classFile);
				}
				else {
					throw new IocException('Nie podano sciezki do pliku klasy: ' . $className);
				}
			}
		}
		$constructor = $cMap->getConstructor();
		$constructorArgs = $cMap->getConstructorArgs();
		$argsValues = array();
		if(count($constructorArgs) > 0) {
			foreach($constructorArgs AS $arg) {
				$argsValues[] = $this->createArgOrProp($arg);
			}
			if(empty($constructor) || ($constructor == '__construct')) {
				$refl = new ReflectionClass($className);
				$object = $refl->newInstanceArgs($argsValues);				
			}
			else {
				$object = call_user_func_array(array(new ReflectionClass($className), $constructor), $argsValues);
				//$object = call_user_func_array(array($className, $constructor), $argsValues); //?
			}		
		}
		else {
			if(empty($constructor) || ($constructor == '__construct')) {
				//$object = new $className();
				$refl = new ReflectionClass($className);
				$object = $refl->newInstance();					
			}
			else {
				//$object = call_user_func(array($className, $constructor));
				$object = call_user_func(array(new ReflectionClass($className), $constructor));
			}
		}
		$settersProps = $cMap->getSettersProps();
		foreach($settersProps AS $prop) {
			$setterName = $prop->getSetterName();
			$propValue = $this->createArgOrProp($prop);
			$object->{$setterName}($propValue);
		}
		return $object;
	}
	
	/**
	 * 
	 * Przetwarza argumenty dla konstruktorow i setterow na podstawie map zaleznosci, tworzac obiekty jesli jest taka potrzeba
	 * 
	 * @access protected
	 * @param object IocArgMap Mapa zaleznosci argumentow
	 * @return mixed
	 * 
	 */	
	protected function createArgOrProp(IocArgMap $arg) {
		$value = $arg->getArgValue();
		$type = $arg->getArgType();
		if($type == 'reference') {
			$value = $this->create($value);
			return $value;
		}
		if($type == 'value') {
			if(is_string($value) && preg_match('/^:/', $value)) {
				if(isset($this->_binded[$value])) {
					return $this->_binded[$value];
				}
				else {
					throw new IocException('Wystapila nazwa niepowiazana z zadna wartoscia: ' . $value);
				}
			}
			else {
				return $value;
			}
		}
		throw new IocException('Nieprawidlowy typ parametru/atrybutu: ' . $type);
	}
	
	/**
	 * 
	 * Pozwala wyswietlic tablice zaleznosci miedzy obiektami i obiekty "statyczne"
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function __toString() {
		$str = '';
		$str .= 'Mapa zaleznosci: <br />';
		$str .= '<pre>';
		$str .= print_r($this->_appMap, true);
		$str .= '</pre>'; 
		$str .= 'Obiekty statyczne: <br />';
		$str .= '<pre>';
		$str .= print_r($this->_objects, true);
		$str .= '</pre>';
		return $str;
	}
	
}

?>
