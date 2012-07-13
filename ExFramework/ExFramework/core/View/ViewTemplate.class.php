<?php

/**
 * @class ViewTemplate
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ViewTemplate implements IViewTemplate {

	/**
	 * Typ MIME dokumentu
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_viewType = 'text/plain';
	
	/**
	 * Silnik szablonow
	 * 
	 * @var object
	 * 
	 */ 	
	protected $_templateEngine = null;
	
	/**
	 * Sciezka do szablonu
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_templatePath = null;

	/**
	 * Id Cache
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_cacheId = null;
	
	/**
	 * Id dla kompilowanego szablonu
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_compileId = null;
	
	/**
	 * Czy wyeswietlic szablon
	 * 
	 * @var bool
	 * 
	 */ 	
	protected $_display = false;	
	
	/**
	 * Czas zycia cache dla przetwarzanego szablonu
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_lifeTime = null;	
	
	/**
	 * Tablica zmiennych dla szablonu
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_data = array();
	
	/*
	 * Konstruktor
	 * 
	 * @access public
	 * @param object ITemplateEngine Silnik szablonow
	 * @return void
	 * 
	 */ 			
	public function __construct(ITemplate $templateEngine) {
		$this->_templateEngine = $templateEngine;
	}
	
	/*
	 * Dodaje zmienna o okreslonej etykiecie do tablicy zmiennych dla szablonu
	 * 
	 * @access public
	 * @param string Etykieta zmiennej
	 * @param mixed Wartosc zmiennej
	 * @return void
	 * 
	 */ 	
	public function assign($var, $value) {
		$this->_data[(string)$var] = $value;
	}
	
	/*
	 * Przetwarza zmienne w szablonie, zwracajac ciag tekstowy
	 * 
	 * @access public
	 * @param Sciezka do szablonu domyslnie = null
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param bool Czy wyswietlic przetworzony szablon?
	 * @param int Czas zycia cache
	 * @return string
	 * 
	 */ 			
	public function fetch($templatePath=null, $cacheId=null, $compileId=null, $display=false, $lifeTime=null) {
		foreach($this->_data AS $index=>$value) {
			if(is_object($value)) {
				$this->_data[$index] = null;
			}
		}
		if($templatePath !== null) {
			$this->_templatePath = $templatePath;
		}
		if($cacheId !== null) {
			$this->_cacheId = $cacheId;
		}
		if($compileId !== null) {
			$this->_compileId = $compileId;
		}
		if($display !== null) {
			$this->_display = $display;
		}
		if($lifeTime !== null) {
			$this->_lifeTime = $lifeTime;
		}
		if($this->_templatePath == null) {
			throw new ViewException('Nie podano sciezki do szablonu');
		}
		if((strstr($this->_templatePath, '/')) && !strstr($this->_templatePath, 'file:')) {
			$this->_templatePath = 'file:' . $this->_templatePath;
		}
		$this->_templateEngine->clearAllAssign();
		foreach($this->_data AS $index=>$value) {						
			$this->_templateEngine->assign($index, $value);
		}
		//while ($exist = current($this->_data)) { //zmiana na assignByRef(), 2011.08.14 proba sie nie powiodla co nie chce foreach dzialac z referencjam do tablic w szablonach
		//	$this->_templateEngine->assignByRef(key($this->_data), current($this->_data));
		//	next($this->_data);
		//}
		return $this->_templateEngine->fetch($this->_templatePath, $this->_cacheId, $this->_compileId, $this->_display, $this->_lifeTime);
	}	
	
	/*
	 * Ustawia sciezke do szablonu
	 * 
	 * @access public
	 * @param string Sciezka do szablonu
	 * @return void
	 * 
	 */ 		
	public function setTemplatePath($templatePath) {
		$this->_templatePath = $templatePath;
	}
	
	/*
	 * Zwraca sciezke do szablonu
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getTemplatePath() {
		return $this->_templatePath;
	}			
	
	/*
	 * Zwraca typ MIME widoku jesli ustawiono
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getMimeType() {
		return $this->_viewType;
	}
		
	/*
	 * Ustawia typ MIME widoku
	 * 
	 * @access public
	 * @param string Typ MIME dla widoku
	 * @return void
	 * 
	 */ 	
	public function setMimeType($mimeType) {
		return $this->_viewType = (string)$mimeType;
	}
	
	/*
	 * Zwraca obiekt rendera
	 * 
	 * @access public
	 * @return object
	 * 
	 */ 	
	public function getTemplateEngine() {
		return $this->_templateEngine;
	}		
	
	/*
	 * Ustawia id szablonu
	 * 
	 * @access public
	 * @param string Id cache dla przetwarzanego szablonu
	 * @return void
	 * 
	 */ 		
	public function setCacheId($cacheId) {
		$this->_cacheId = $cacheId;
	}
	
	/*
	 * Zwraca id cache przetwarzanego szablonu
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getCacheId() {
		return $this->_cacheId;
	}	

	/*
	 * Ustawia id kompilacji dla przetwarzanego szablonu
	 * 
	 * @access public
	 * @param string Id kompilacji dla przetwarzanego szablonu
	 * @return void
	 * 
	 */ 		
	public function setCompileId($compileId) {
		$this->_compileId = $compileId;
	}
	
	/*
	 * Zwraca id kompilacji przetwarzanego szablonu
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getCompileId() {
		return $this->_compileId;
	}
	
	/*
	 * Ustawia czy szablon po przetworzeniu ma zostac natychmiast wyswietlony
	 * 
	 * @access public
	 * @param bool True jesli wyswietlic
	 * @return void
	 * 
	 */ 		
	public function setDisplay($display) {
		$this->_display = $display;
	}
	
	/*
	 * Zwraca wartosc bool odp za natychmiatowe wyswietlanie szablonu po przetworzeniu
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 	
	public function getDisplay() {
		return $this->_display;
	}
	
	/*
	 * Ustawia czas zycia przetwarzanego szablonu
	 * 
	 * @access public
	 * @param int Czas zycia szablonu
	 * @return void
	 * 
	 */ 		
	public function setLifeTime($lifeTime) {
		$this->_lifeTime = $lifeTime;
	}
	
	/*
	 * Zwraca czas zycia przetwarzanego szablonu
	 * 
	 * @access public
	 * @return int
	 * 
	 */ 	
	public function getLifeTime() {
		return $this->_lifeTime;
	}
	
	
	
	/*
	 * Zwraca informacje tekstowa o pewnych wlasciwosciach obiektu
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function __toString() {
		$str = "";
		$str .= "View Type: " . $this->_viewType . "<br />";
		$str .= "Template Path: " . $this->_templatePath . "<br />"; 
		$str .= "Cache Id: " . $this->_cacheId . "<br />";
		$str .= "Compile Id: " . $this->_compileId . "<br />";
		$str .= "Life Time: " . $this->_lifeTime . "<br />";
		$str .= "Display: " . (int)$this->_display . "<br />"; 		 		 		 
		$str .= "Data: \n<pre>" . print_r($this->_data, true) . "</pre><br />";
		return $str;
	}
	
}

?>
