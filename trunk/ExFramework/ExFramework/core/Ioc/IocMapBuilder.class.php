<?php

/**
 * @class IocMapBuilder
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class IocMapBuilder {
	
	/**
	 * Obiekt mapowan klas
	 *
	 * @var object
	 * 
	 */	
	protected $_appMap = null;
	
	/**
	 * Obiekt onslugi konfiguracji
	 *
	 * @var object
	 * 
	 */	
	protected $_config = null;
	
	/**
	 * Obiekt cache
	 *
	 * @var object
	 * 
	 */	
	protected $_cache = null;
	
	/**
	 * 
	 * Konstruktor
	 * 
	 * @access public
	 * @param object Obiekt konfiguracji
	 * @param string Sciezka do pliku z konfiguracja
	 * 
	 */	
	public function __construct(IConfig $config, ICache $cache=null, $configFile=null) {
		$this->_config = $config;
		$this->_cache = $cache;
		if($configFile !== null) {
			$this->_config->addConfigFile($configFile);
		}
	}
	
	/**
	 * 
	 * Dodaje plik konfiguracyjny
	 * 
	 * @access public
	 * @param string Sciezka do pliku z konfiguracja
	 * @return void
	 * 
	 */		
	public function addConfigFile($configFile) {
		$this->_config->addConfigFile($configFile);
	}
	
	/**
	 * 
	 * Zwraca rzeczywista wartosc zmiennej zapisanej w konfiguracji poprzez zamienna etykiete lub wartosc przekazana jesli okaze sie prawdziwa
	 * 
	 * @access protected
	 * @param string Nazwa zmiennej, ktorej wartosc ma zostac odczytana z konfiguracji
	 * @return mixed
	 * 
	 */	
	protected function getRealValue($str) {
		$realValue = $str;
		if(is_string($str) && strstr($str, '%')) {
			$configVar = preg_match('/([^%]*)%([^%]*)%([^%]*)/i', $str, $arr);
			$value = $this->_config->getConfig($arr[2]);
			if(!empty($arr[1]) || !empty($arr[3])) {
				$realValue = preg_replace('/([^%]*)%([^%]*)%([^%]*)/i', '${1}'. $value . '${3}', $str);
			}
			else {
				$realValue = $value;
			}
		}
		return $realValue;
	}
	
	/**
	 * 
	 * Wykonuje mapowanie danych konfiguracyjnych na obiekty mapowan
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function build() {		
		$iocMap = $this->_config->getConfigGroup('ioc');
		if(($this->_appMap === null) || !($this->_appMap instanceof IocAppMap)) {
			$this->_appMap = new IocAppMap();
		}
		foreach($iocMap AS $key => $val) {
			if(isset($val['redirect'])) {
				$id = ucfirst($this->getRealValue($val['redirect']));
				if(!isset($iocMap[$key][$id])) {
					throw new IocException('Brak definicji obiektu: "' . $id . '". Blad mapowania');
				}
				$tab = $iocMap[$key][$id];
				$label = $key;
			}
			else {
				$id = $key;
				if(!isset($iocMap[$id])) {
					throw new IocException('Brak definicji obiektu: "' . $id . '". Blad mapowania');
				}				
				$tab = $iocMap[$id];
				$label = $key;
			}
			if(isset($this->_appMap->$id)) {
				continue;
			}
			if(!isset($tab['className'])) {
				throw new IocException('Nie zdefiniowano nazwy klasy. Blad mapowania');
			}
			$className = $tab['className'];
			$classFile = (isset($tab['classFile'])) ? $tab['classFile'] : null;
			$parentClassName = (isset($tab['parentClassName'])) ? $tab['parentClassName'] : null;
			$parentClassFile = (isset($tab['parentClassFile'])) ? $tab['parentClassFile'] : null;
			$interfaceName = (isset($tab['interfaceName'])) ? $tab['interfaceName'] : null;
			$interfaceFile = (isset($tab['interfaceFile'])) ? $tab['interfaceFile'] : null;
			$singleton = ((isset($tab['singleton'])) && ($tab['singleton'] == true)) ? true : false;
			$constructor = ((isset($tab['constructor'])) && (($tab['constructor'] !== '__construct') && ($tab['constructor'] !== ''))) ? $tab['constructor'] : '__construct';
			$classObject = new IocClassMap($className, $classFile, $parentClassName, $parentClassFile, $interfaceName, $interfaceFile, $constructor, $singleton);
			
			$constructorArgs = (isset($tab['constructorArgs'])) ? $tab['constructorArgs'] : array();				
			foreach($constructorArgs AS $arg) {
				if(strstr((string)$arg, '&')) {
					$arg = str_replace('&', '', $arg);
					$arg = $this->getRealValue($arg);
					$type = 'reference';
				}
				else {
					$arg = $this->getRealValue($arg);
					$type = 'value';
				}
				$classObject->setConstructorArg(new IocConstructArgMap($arg, $type));
			}
			
			$settersProps = (isset($tab['settersProps'])) ? $tab['settersProps'] : array();	
			foreach($settersProps AS $key => $arg) {
				if(strstr($arg, '&')) {
					$arg = str_replace('&', '', $arg);
					$arg = $this->getRealValue($arg);
					$type = 'reference';
					$name = $key;
				}
				else {
					$arg = $this->getRealValue($arg);
					$type = 'value';
					$name = $key;
				}
				$classObject->setProp(new IocSetterArgMap($arg, $type, $name));
			}
			$this->_appMap->setClassMap($label, $classObject);
		} 
	}
	
	/**
	 * 
	 * Zwraca obiekt mapowania aplikacji
	 * 
	 * @access public
	 * @return object
	 * 
	 */	
	public function getAppMap() {
		if(is_object($this->_cache)) {
			$cached = false;
			$cached = $this->_cache->fetch('classMap');
			
			if($cached !== false) {
				return $cached; 
			}
			else {
				$this->build();
				$this->_cache->store('classMap', $this->_appMap);
				return $this->_appMap;
			}
		}
		else {
			$this->build();
			return $this->_appMap;			
		}
	}
	
}

?>
