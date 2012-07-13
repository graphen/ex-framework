<?php

/**
 * @interface ITemplate
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */

interface ITemplate {
	
	/**
	 * Metoda pozwala dolaczyc w obiekcie dane do istniejacych identyfikatorow
	 * 
	 * @access public
	 * @param mixed Identyfikator zmiennej lub tablica wartosci
	 * @param mixed Wartosc lub tablica wartosci
	 * @param bool Czy polaczyc z tablica, w takim wypadku istniejace wartosci beda zastepowane nowymi
	 * @return void
	 * @todo Sprawdzic ta metode
	 * 
	 */  	
	public function append($var, $value=null, $merge=false);

	/**
	 * Metoda pozwala dolaczyc w obiekcie dane do istniejacych identyfikatorow przez referencje
	 * 
	 * @access public
	 * @param string Identyfikator zmiennej
	 * @param mixed Wartosc lub tablica wartosci
	 * @param bool  Czy polaczyc z tablica, w takim wypadku istniejace wartosci beda zastepowane nowymi
	 * @return void
	 * @todo Sprawdzic ta metode
	 * 
	 */ 
	public function appendByRef($var, &$value=null, $merge=false);

	/**
	 * Metoda pozwala dolaczyc do obiektu dane, ktore beda wyswietlane w szablonie i powiazac je z identyfikatorami
	 * 
	 * @access public
	 * @param mixed Identyfikator lub tablica
	 * @param mixed $value
	 * @return void
	 * @todo Sprawdzic ta metode
	 * 
	 */
	public function assign($var, $value);

	/**
	 * Metoda pozwala dolaczyc do obiektu dane (przez referencje), ktore beda wyswietlane w szablonie i powiazac je z identyfikatorami
	 * 
	 * @access public
	 * @param mixed $var
	 * @param mixed $value
	 * @return void
	 * @todo Sprawdzic ta metode
	 * 
	 */
	public function assignByRef($var, &$value);

	/**
	 * Metoda pozwala usunac z obiektu wszystkie dane, ktore byly wyswietlane w szablonie
	 * 
	 * @access public
	 * @return void
	 * 
	 */
	public function clearAllAssign();

	/**
	 * @Metoda pozwala usunac pliki cache, wszystkie lub te ktore sa starsze niz $expireTime
	 * 
	 * @access public
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */
	public function clearAllCache($expireTime=null);

	/**
	 * Metoda pozwala usunac z obiektu dane, ktore byly wyswietlane w szablonie, identyfikowane przez identyfikator $var
	 * 
	 * @access public
	 * @param string|array Identyfikator lub tablica
	 * @return void
	 * 
	 */
	public function clearAssign($var);

	/**
	 * Metoda pozwala usunac plik cache, o identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, starsze niz $expireTime
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */

	public function clearCache($templateFile, $cacheId=null, $compileId=null, $expireTime=null);

	/**
	 * Metoda dla zgodnosci z interfejsem
	 * 
	 * @access public
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */ 
	public function clearCompiledTpl($templateFile=null, $compileId=null, $expireTime=null);
	
	/**
	 * Metoda pozwala usunac z obiektu dane konfiguracyjne, wszystkie lub identyfikowane przez identyfikator $var
	 * 
	 * @access public
	 * @param mixed Identyfikator zmiennej konfiguracyjnej
	 * @return void
	 * 
	 */	
	public function clearConfig($var=null);

	/**
	 * Metoda pozwala zaladowac do tablicy z konfiguracja dane z pliku konfiguracyjnego, w postaci tablicy o nazwie config, lub jej czesc
	 * Dane te beda wykorzystywane w szablonie
	 * 
	 * @access public
	 * @param string Sceizka do pliku konfiguracyjnego
	 * @param string Nazwa sekcji
	 * @return void
	 * 
	 */
	public function configLoad($configFile, $section=null);

	/**
	 * Metoda wyswietla przetworzony szablon, identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, gdzie $lifeTime to czas cache'owania danego szablonu
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu lub sciezka do niego
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache
	 * @return void
	 * 
	 */
	public function display($template, $cacheId=null, $compileId=null, $lifeTime=null);
	
	/**
	 * Metoda zwraca przetworzony szablon, identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, gdzie $lifeTime to czas cache'owania danego szablonu
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu lub sciezka do niego
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param bool Czy wyswietlic przetworzony szablon?
	 * @param int Czas zycia cache
	 * @return string
	 * 
	 */
	public function fetch($template, $cacheId=null, $compileId=null, $lifeTime=null);

	/**
	 * Metoda pozwalajaca pobrac wartosc z konfiguracji, ktora bedzie wyswietlana w szablonie, poprzez jej identyfikator, lub wszystkie wartosci
	 * 
	 * @access public
	 * @param string Identyfikator zmiennej konfiguracyjnej
	 * @return mixed
	 *  
	 */
	public function getConfigVars($varname=null);

	/**
	 * Metoda pozwalajaca pobrac wartosc z danych, ktore beda wyswietlane w szablonie, poprzez jej identyfikator, lub wszystkie wartosci
	 * 
	 * @access public
	 * @param string Identyfikator zmiennej szablonu
	 * @return mixed
	 *  
	 */
	public function getTemplateVars($varname=null);

	/**
	 * Metoda sprawdza czy istnieje przetworzony szablon w cache o identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, gdzie $lifeTime to czas cache'owania danego szablonu
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu lub sciezka do niego
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int Czas zycia cache, moze byc cokolwiek, wtedy czasa zycia bedzie sprawdzany indywidualnie w pliku
	 * @return bool
	 * 
	 */
	public function isCached($template, $cacheId=null, $compileId=null, $lifeTime=null);

	/**
	 * Metoda sprawdza czy dany szablon istnieje na dysku
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu lub sciezka do niego 
	 * @return bool
	 * 
	 */
	public function templateExists($template); 

	/**
	 * Metoda wyswietla przetworzony szablon, identyfikatorze $cacheId, z grupy identyfikowanej przez $compileId, gdzie $lifeTime to czas cache'owania danego szablonu
	 * 
	 * @access public
	 * @param string Nazwa pliku szablonu lub sciezka do niego
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param int czas zycia cache
	 * @return void
	 * 
	 */
	public function render($template, $cacheId=null, $compileId=null, $lifeTime=null);

}

?>
