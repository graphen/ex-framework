<?php

/**
 * @class IocClassMap
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class IocClassMap {
	
	/**
	 * Nazwa klasy
	 *
	 * @var string
	 * 
	 */		
	private $_className = null;
	
	/**
	 * Sciezka do pliku klasy
	 *
	 * @var string
	 * 
	 */		
	private $_classFile = null;

	/**
	 * Nazwa klasy(klas) rodzica(ow)
	 *
	 * @var string|array
	 * 
	 */		
	private $_parentClassName = null;
	
	/**
	 * Sciezka(i) do pliku klasy(klas) rodzica(ow)
	 *
	 * @var string|array
	 * 
	 */		
	private $_parentClassFile = null;
	
	/**
	 * Nazwa interfejsu(ow)
	 *
	 * @var string|array
	 * 
	 */		
	private $_interfaceName = null;
	
	/**
	 * Sciezka(i) do pliku interfejsu(ow)
	 *
	 * @var string|array
	 * 
	 */		
	private $_interfaceFile = null;
	
	/**
	 * Wskazuje czy ma byc tylko jedna instancja obiektu klasy
	 *
	 * @var bool
	 * 
	 */		
	private $_singleton = false;
	
	/**
	 * Nazwa konstruktora, zwykle __construct, ale tez byc metoda fabryczna np getInstance itp
	 *
	 * @var string
	 * 
	 */		
	private $_constructor = '__construct';
	
	/**
	 * Tablica mapowan argumentow konstruktora
	 *
	 * @var array
	 * 
	 */		
	private $_constructorArgs = array();
	
	/**
	 * Tablica mapowan argumentow setterow, z ich nazwami
	 *
	 * @var array
	 * 
	 */		
	private $_settersProps = array();	
	
	/**
	 * 
	 * Konstruktor
	 * 
	 * @access public
	 * @param string Nazwa klasy
	 * @param string Sciezka do pliku klasy
	 * @param string|array Nazwa klasy rodzica(ow)
	 * @param string|array Sciezka do pliku klasy rodzica(ow)
	 * @param string|array Nazwa interfejsu(ow)
	 * @param string|array Sciezka do pliku interfejsu(ow)
	 * @param string Nazwa konstruktora
	 * @param bool Wskazuje czy ma byc tylko jedna instancja obiektu klasy
	 * 
	 */		
	public function __construct($className, $classFile, $parentClassName, $parentClassFile, $interfaceName, $interfaceFile, $constructor='__construct', $singleton=false) {
		$this->_className = $className;
		$this->_classFile = $classFile;
		$this->_parentClassName = $parentClassName;
		$this->_parentClassFile = $parentClassFile;
		$this->_interfaceName = $interfaceName;
		$this->_interfaceFile = $interfaceFile;
		$this->_constructor = $constructor;
		$this->_singleton = $singleton;
	}
	
	/**
	 * 
	 * Zwraca nazwe klasy
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getClassName() {
		return $this->_className;
	}
	
	/**
	 * 
	 * Zwraca nazwe(y) klasy(s) rodzica
	 * 
	 * @access public
	 * @return string|array
	 * 
	 */		
	public function getParentClassName() {
		return $this->_parentClassName;
	}
	
	/**
	 * 
	 * Zwraca sciezke do pliku klasy
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getClassFile() {
		return $this->_classFile;
	}

	/**
	 * 
	 * Zwraca sciezke do pliku klasy(s) rodzica(ow)
	 * 
	 * @access public
	 * @return string|array
	 * 
	 */		
	public function getParentClassFile() {
		return $this->_parentClassFile;
	}
	
	/**
	 * 
	 * Zwraca nazwe interfejsu(ow)
	 * 
	 * @access public
	 * @return string|array
	 * 
	 */	
	public function getInterfaceName() {
		return $this->_interfaceName;
	}
	
	/**
	 * 
	 * Zwraca sciezke do pliku(ow) interfejsu(ow)
	 * 
	 * @access public
	 * @return string|array
	 * 
	 */		
	public function getInterfaceFile() {
		return $this->_interfaceFile;
	}
	
	/**
	 * 
	 * Zwraca nazwe konstruktora
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getConstructor() {
		return $this->_constructor;
	}
		
	/**
	 * 
	 * Zwraca wartosc flagi wskazujacej czy mamy do czynienia z pojedynczym obiektem klasy
	 * 
	 * @access public
	 * @return bool
	 * 
	 */		
	public function isSingleton() {
		return (bool) $this->_singleton;
	}
	
	/**
	 * 
	 * Zwraca tablice argumentow konstruktora
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function getConstructorArgs() {
		return $this->_constructorArgs;
	}
	
	/**
	 * 
	 * Zwraca tablice argumentow setterow z ich nazwami 
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function getSettersProps() {
		return $this->_settersProps;
	}
	
	/**
	 * 
	 * Zwraca wartosc argumentow pojedynczego settera
	 * 
	 * @access public
	 * @return mixed
	 * 
	 */		
	public function getProp($name) {
		if(isset($this->_settersProps[$name])) {
			return $this->_settersProps[$name];
		}
		return null;
	}
	
	/**
	 * 
	 * Ustawia nazwe klasy
	 * 
	 * @access public
	 * @param string Nazwa klasy
	 * @return void
	 * 
	 */			
	public function setClassName($name) {
		$this->_className = $name;
	}

	/**
	 * 
	 * Ustawia nazwe(y) klasy(s) rodzica(ow)
	 * 
	 * @access public
	 * @param string|array Nazwa(y) klasy(s)
	 * @return void
	 * 
	 */			
	public function setParentClassName($name) {
		$this->_parentClassName = $name;
	}
	
	/**
	 * 
	 * Ustawia sciezke do pliku klasy
	 * 
	 * @access public
	 * @param string Sciezka do pliku klasy
	 * @return void
	 * 
	 */			
	public function setClassFile($file) {
		if(!file_exists($file)) {
			throw new IocException('Plik: ' . $file . ' nie istnieje');
		}
		$this->_classFile = $file;
	}
	
	/**
	 * 
	 * Ustawia sciezke(i) do pliku(ow) klasy(s) rodzica
	 * 
	 * @access public
	 * @param string Sciezka(i) do pliku(ow)
	 * @return void
	 * 
	 */			
	public function setParentClassFile($file) {
		if(is_array($file)) {
			foreach($file AS $f) {
				if(!file_exists($f)) {
					throw new IocException('Plik: ' . $f . ' nie istnieje');
				}				
			}
		}
		else {		
			if(!file_exists($file)) {
				throw new IocException('Plik: ' . $file . ' nie istnieje');
			}
		}
		$this->_parentClassFile = $file;
	}	
	
	/**
	 * 
	 * Ustawia nazwe(y) interfejsu(ow) klasy(s)
	 * 
	 * @access public
	 * @param string|array Nazwa(y) interfejsu(ow)
	 * @return void
	 * 
	 */			
	public function setInterfaceName($name) {
		$this->_interfaceName = $name;
	}
	
	
	/**
	 * 
	 * Ustawia sciezke(ki) do pliku(ow) interfejsu(ow)
	 * 
	 * @access public
	 * @param string|array Sciezka(i) do pliku(ow)
	 * @return void
	 * 
	 */			
	public function setInterfaceFile($file) {
		if(is_array($file)) {
			foreach($file AS $f) {
				if(!file_exists($f)) {
					throw new IocException('Plik: ' . $f . ' nie istnieje');
				}				
			}
		}
		else {
			if(!file_exists($file)) {
				throw new IocException('Plik: ' . $file . ' nie istnieje');
			}
		}
		$this->_interfaceFile = $file;
	}
	
	
	/**
	 * 
	 * Ustawia nazwe konstruktora
	 * 
	 * @access public
	 * @param string Nazwa konstruktora lub metody fabrycznej
	 * @return void
	 * 
	 */			
	public function setConstructor($constructor) {
		$this->_constructor = $constructor;
	}
	
	/**
	 * 
	 * Ustawia flage singletona
	 * 
	 * @access public
	 * @param bool Czy to jest singleton?
	 * @return void
	 * 
	 */			
	public function setSingleton($isSingleton) {
		$this->_singleton = (bool) $isSingleton;
	}
	
	/**
	 * 
	 * Ustawia tablice argumentow konstruktora
	 * 
	 * @access public
	 * @param object IocConstructArgMap Zmapowany argument konstruktora
	 * @return void
	 * 
	 */			
	public function setConstructorArg(IocConstructArgMap $cArgMap) {
		$this->_constructorArgs[] = $cArgMap;
	}
	
	/**
	 * 
	 * Ustawia tablice argumentow setterow
	 * 
	 * @access public
	 * @param object IocSetterArgMap Zmapowany argument dla settera
	 * @return void
	 * 
	 */			
	public function setProp(IocSetterArgMap $sPropMap) {
		$this->_settersProps[$sPropMap->getArgName()] = $sPropMap;
	}
	
}

?>
