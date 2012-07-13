<?php

/**
 * @class Crypt
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Crypt implements ICrypt {
	
	/**
	 * Ciag z ktorego tworzony jest klucz
	 * 
	 * @var string
	 * 
	 */ 
	protected $_keyString = null;

	/**
	 * Ciag na podstawie ktorego tworzony jest wektor
	 * 
	 * @var string
	 * 
	 */ 
	protected $_ivString = null;
	
	/**
	 * Rodzaj algorytmu szyfrujacego
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_cipherAlgorithm = null;
	
	/**
	 * Metoda szyfrowania
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_cryptMode = null;
	
	/**
	 * Zasob reprezentujacy otwarty modul szyfrujacy
	 * 
	 * @var resource
	 * 
	 */ 	
	protected $_encDescriptor = null;
	
	/**
	 * Czy wektor dolaczyc do ciagu
	 * 
	 * @var bool
	 * 
	 */ 	
	protected $_saveIv = true;
	
	/**
	 * Ciag oddzielajacy dane od wektora w zaszyfrowanym ciagu
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_pad = 'F^Dq3e6wGDl&kds5fTEk^j5R6T$Efdg5635Egf|rdg';
	
	/**
	 * Domyslna metoda szyfrowania
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_defaultCryptMode = MCRYPT_MODE_CFB;
	
	/**
	 * Domyslny algorytm szyfrujacy
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_defaultCipherAlgorithm = MCRYPT_TRIPLEDES;

	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param string Klucz
	 * @param string Algorytm
	 * @param string Metoda szyfrowania
	 * @param string Wektor
	 * @param bool Czy do danych dolaczyc wektor
	 * 
	 */ 
	public function __construct($key='$#%#45%e*fgG?#^F$%fd}09#$?!-+sd*&$%)%_VZx{z(vcOGS=09--.<65erRT', $alg=null, $mode=null, $iv=null, $saveIv=true) {
		//sprawdzenie czy rozszerzenie istnieje
		if(!$this->isMcrypt()) {
			throw new CryptException('Rozszerzenie Mcrypt nie istnieje');
		}
		//wybor algorytmu szyfrowania
		$this->setCipherAlgorithm($alg);
		//wybor metody szyfrowania
		$this->setCryptMode($mode);
		//ustawienie wektora szyfrowania
		$this->_ivString = (string)$iv;
		//czy zapisac wektor razem z zaszyfrowana wiadomoscia
		$this->_saveIv = (bool)$saveIv;
		//ustawienie klucza szyfrowania
		$this->_keyString = (string)$key;
	}
	
	/**
	 * Tworzy wektor
	 * 
	 * @access protected
	 * @return string
	 * 
	 */
	protected function createIv() {
		$ivSize = mcrypt_get_iv_size($this->_cipherAlgorithm, $this->_cryptMode);
		if($ivSize === false) {
			throw new CryptException('Nie mozna pobrac dlugosci wektora');
		}
		if($this->_ivString === null || $this->_ivString === '') {
			$randomSeed = (strstr(PHP_OS, "WIN")) ? MCRYPT_RAND : MCRYPT_DEV_RANDOM; 
			$iv = mcrypt_create_iv($ivSize, $randomSeed);
			if($iv === false) {
				throw new CryptException('Nie mozna utworzyc wektora');
			}
		}
		else {
			if(strlen($this->_ivString) < $ivSize) {
				$iv = str_pad($this->_ivString, $ivSize, '0');
			}
			else {
				$iv = substr($this->_ivString, 0, $ivSize);
			}
		}
		return $iv;
	}
	
	/**
	 * Tworzy klucz
	 * 
	 * @access protected
	 * @return string Klucz dla danej metody szyfrowania i danego algorytmu
	 * 
	 */
	protected function createKey() {
		$keySize = mcrypt_get_key_size($this->_cipherAlgorithm, $this->_cryptMode);
		$key = substr(md5($this->_keyString), 0, $keySize);
		return $key;		
	}
	
	/**
	 * Otwiera modul szyfrowania
	 * 
	 * @access protected
	 * @return void
	 * 
	 */
	protected function open() {
		if(is_resource($this->_encDescriptor)) {
			return;
		}
		$encDescriptor = mcrypt_module_open($this->_cipherAlgorithm, '', $this->_cryptMode, '');
		if($encDescriptor === false) {
			throw CryptException('Nie mozna zainicjalizowac modulu szyfrujacego');
		}
		$this->_encDescriptor = $encDescriptor;
	}
	
	/**
	 * Sprawdza czy jest dostepne rozszerzenie mcrypt
	 * 
	 * @access public
	 * @return bool
	 * 
	 */
	public function isMcrypt() {
		if(!extension_loaded('mcrypt')) {
			return false;
		}
		return true;
	}
	
	/**
	 * Ustawia algorytm szyfrowania
	 * 
	 * @access public
	 * @param string Algorytm szyfrujacy
	 * @return void
	 * 
	 */
	public function setCipherAlgorithm($alg=null) {
		if($alg !== null) {
			$alg = str_replace('mcrypt_', '', strtolower($alg));
		}
		$cipherAlgorithms = mcrypt_list_algorithms();
		if($alg === null OR (!in_array($alg, $cipherAlgorithms))) {
			$this->_cipherAlgorithm = $this->_defaultCipherAlgorithm;
		}
		else {
			if($alg !== null) {
				$alg = constant(strtoupper('MCRYPT_'.$alg));
			}			
			$this->_cipherAlgorithm = $alg;
		}
	}
	
	/**
	 * Ustawia metode szyfrowania
	 * 
	 * @access public
	 * @param string Metoda szyfrowania
	 * @return void
	 * 
	 */
	public function setCryptMode($mode=null) {	
		if($mode !== null) {
			$mode = str_replace('mcrypt_mode_', '', strtolower($mode));
		}
		$cryptModes = mcrypt_list_modes();
		if($mode === null OR (!in_array($mode, $cryptModes))) {
			$this->_cryptMode = $this->_defaultCryptMode;
		}
		else {
			if($mode !== null) {
				$mode = constant(strtoupper('MCRYPT_MODE_'.$mode));
			}				
			$this->_cryptMode = $mode;
		}		
	}
	
	/**
	 * Ustawia ciag bedacy podstawa utworzenia klucza
	 * 
	 * @access public
	 * @param string Metoda szyfrowania
	 * @return void
	 * 
	 */
	public function setKey($key) {
		$this->_keyString = (string)$key;
	}
	
	/**
	 * Ustawia ciag bedacy podstawa dla utworzenia wektora
	 * 
	 * @access public
	 * @param string Metoda szyfrowania
	 * @return void
	 * 
	 */
	public function setIv($iv) {
		$this->_ivString = (string)$iv;
	}
	
	/**
	 * Ustawia flage odp za dolaczanie lub nie wektora do danych 
	 * 
	 * @access public
	 * @param bool  //true jesli dolaczyc wektor
	 * @return void
	 * 
	 */
	public function setSaveIv($saveIv) {
		$this->_saveIv = (bool)$saveIv;
	}
	
	/**
	 * Pobiera ciag bedacy podstawa tworzenia klucza
	 * 
	 * @access public
	 * @return string
	 * 
	 */
	public function getKey() {
		return $this->_keyString;
	}
	
	/**
	 * Pobiera ciag bedacy podstawa tworzenia wektora
	 * 
	 * @access public
	 * @return string
	 * 
	 */
	public function getIv() {
		return $this->_ivString;
	}
	
	/**
	 * Sprawdza czy wektor ma byc dolaczany do danych
	 * 
	 * @access public
	 * @return bool
	 * 
	 */
	public function getSaveIv() {
		return $this->_saveIv;
	}
	
	/**
	 * Pobiera metode szyfrowania
	 * 
	 * @access public
	 * @return string
	 * 
	 */
	public function getCryptMode() {
		return $this->_cryptMode;
	}
	
	/**
	 * Pobiera algorytm szyfrujacy
	 * 
	 * @access public
	 * @return string
	 * 
	 */
	public function getCipherAlgorithm() {
		return $this->_cipherAlgorithm;
	}
	
	/**
	 * Szyfruje podany ciag
	 * 
	 * @access public
	 * @param string Dane do szyfrowania
	 * @return string
	 * 
	 */
	public function encrypt($source) {
		//inicjalizacja algorytmu szyfrujacego
		$this->open(); //Moze rzucic wyjatkiem
		$iv = $this->createIv();
		$key = $this->createKey();
		$result = mcrypt_generic_init($this->_encDescriptor, $key, $iv);
		if($result < 0) {
			throw new CryptException('Wystapil problem podczas inicjalizowania modulu szyfrujacego', $result);
		}
		$crypted = mcrypt_generic($this->_encDescriptor, $source);
		if($this->_saveIv === true) {
			$crypted .= $this->_pad;
			$crypted .= $iv;
		}
		$crypted = base64_encode($crypted);
		mcrypt_generic_deinit($this->_encDescriptor);
		return $crypted;
	}
	
	/**
	 * Odszyfrowuje podany ciag
	 * 
	 * @access public
	 * @param string Dane do odszyfrowania
	 * @return string
	 * 
	 */
	public function decrypt($cryptedSource) {
		//inicjalizacja algorytmu szyfrujacego
		$this->open(); //Moze rzucic wyjatkiem
		$iv = $this->createIv(); //Moze rzucic wyjatkiem
		$key = $this->createKey();
		$cryptedSource = base64_decode(trim($cryptedSource));
		if(strstr($cryptedSource, $this->_pad)) {
			$tempArray = explode($this->_pad, $cryptedSource);
			$cryptedSource = $tempArray[0];
			$iv = $tempArray[1];
		}
		$result = mcrypt_generic_init($this->_encDescriptor, $key, $iv);		
		if($result < 0) {
			throw new CryptException('Wtstapil problem podczas inicjalizowania modulu szyfrujacego', $result);
		}
		$decrypted = mdecrypt_generic($this->_encDescriptor, $cryptedSource);
		mcrypt_generic_deinit($this->_encDescriptor);
		return $decrypted;
	}
	
	/**
	 * Destruktor
	 * 
	 * @access public
	 * 
	 */
	public function __destruct() {
		if(is_resource($this->_encDescriptor)) {
			mcrypt_module_close($this->_encDescriptor);
		}
	}
	
	/**
	 * Wyswietlenie zawartosci pol obiektu klasy
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function __toString() {
		$str = "";
		$str .= "Algorytm szyfrowania: " . $this->getCipherAlgorithm() . "\n <br />";
		$str .= "Metoda szyfrowania: " . $this->getCryptMode() . "\n <br />";
		$str .= "Ciag klucz: " . $this->getKey() . "\n <br />";
		$str .= "Ciag iv: " . $this->getIv() . "\n <br />";
		return $str;
	}
	
}

?>
