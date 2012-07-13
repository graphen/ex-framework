<?

/**
 * @interface ICrypt
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface ICrypt {
	
	/**
	 * Sprawdza czy jest dostepne rozszerzenie mcrypt
	 * 
	 * @access public
	 * @return bool
	 * 
	 */
	public function isMcrypt();
	
	/**
	 * Ustawia algorytm szyfrowania
	 * 
	 * @access public
	 * @param string Algorytm szyfrujacy
	 * @return void
	 * 
	 */
	public function setCipherAlgorithm($alg=null);
	
	/**
	 * Ustawia metode szyfrowania
	 * 
	 * @access public
	 * @param string Metoda szyfrowania
	 * @return void
	 * 
	 */
	public function setCryptMode($mode=null);
	
	/**
	 * Ustawia ciag bedacy podstawa utworzenia klucza
	 * 
	 * @access public
	 * @param string Metoda szyfrowania
	 * @return void
	 * 
	 */
	public function setKey($key);
	
	/**
	 * Ustawia ciag bedacy podstawa dla utworzenia wektora
	 * 
	 * @access public
	 * @param string Metoda szyfrowania
	 * @return void
	 * 
	 */
	public function setIv($iv);
	
	/**
	 * Ustawia flage odp za dolaczanie lub nie wektora do danych 
	 * 
	 * @access public
	 * @param bool  //true jesli dolaczyc wektor
	 * @return void
	 * 
	 */
	public function setSaveIv($saveIv);
	
	/**
	 * Pobiera ciag bedacy podstawa tworzenia klucza
	 * 
	 * @access public
	 * @return string
	 * 
	 */
	public function getKey();
	
	/**
	 * Pobiera ciag bedacy podstawa tworzenia wektora
	 * 
	 * @access public
	 * @return string
	 * 
	 */
	public function getIv();
	
	/**
	 * Sprawdza czy wektor ma byc dolaczany do danych
	 * 
	 * @access public
	 * @return bool
	 * 
	 */
	public function getSaveIv();
	
	/**
	 * Pobiera metode szyfrowania
	 * 
	 * @access public
	 * @return string
	 * 
	 */
	public function getCryptMode();
	
	/**
	 * Pobiera algorytm szyfrujacy
	 * 
	 * @access public
	 * @return string
	 * 
	 */
	public function getCipherAlgorithm();
	
	/**
	 * Szyfruje podany ciag
	 * 
	 * @access public
	 * @param string Dane do szyfrowania
	 * @return string
	 * 
	 */
	public function encrypt($source);
	
	/**
	 * Odszyfrowuje podany ciag
	 * 
	 * @access public
	 * @param string Dane do odszyfrowania
	 * @return string
	 * 
	 */
	public function decrypt($cryptedSource);
	
}

?>
