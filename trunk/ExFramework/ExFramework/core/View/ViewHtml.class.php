<?php

/**
 * @class ViewHtml
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ViewHtml extends ViewTemplate implements IViewHtml {
	
	/**
	 * Kompozyt widokow
	 * 
	 * @var object
	 * 
	 */ 	
	protected $_layout = null;
	
	/*
	 * Konstruktor
	 * 
	 * @access public
	 * @param object ITemplateEngine Silnik szablonow
	 * @param object ILayout Kompozyt widokow
	 * @return void
	 * 
	 */
	public function __construct(ITemplate $templateEngine, ILayout $layout) {
		parent::__construct($templateEngine);
		$this->_layout = $layout;
		$this->setMimeType('text/html');
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
		parent::assign($var, $value);
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
				if($value instanceof IViewHtmlHelper) {
					$this->_data[$index] = $value->fetchHtml();
				}
			}
		}
		return parent::fetch($templatePath, $cacheId, $compileId, $display, $lifeTime);
	}
	
	/*
	 * Zwraca obiekt Layoutu
	 * 
	 * @access public
	 * @return ILayout
	 * 
	 */ 	
	public function getLayout() {
		return $this->_layout;
	}	
	
	/*
	 * Zwraca obiekt rendera
	 * 
	 * @access public
	 * @return object
	 * 
	 */ 	
	public function getTemplateEngine() {
		return parent::getTemplateEngine();
	}	
	
	/*
	 * Zwraca typ MIME widoku jesli ustawiono
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getMimeType() {
		return parent::getMimeType();
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
		parent::setMimeType($mimeType);
	}
	
	/*
	 * Zwraca sciezke do szablonu
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getTemplatePath() {
		return parent::getTemplatePath();
	}		
	
	/*
	 * Ustawia sciezke do szablonu
	 * 
	 * @access public
	 * @param string Sciezka do szablonu
	 * @return void
	 * 
	 */ 	
	public function setTemplatePath($tpl) {
		parent::setTemplatePath($tpl);
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
		parent::setCacheId($cacheId);
	}
	
	/*
	 * Zwraca id cache przetwarzanego szablonu
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getCacheId() {
		return parent::getCacheId();
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
		parent::setCompileId($compileId);
	}
	
	/*
	 * Zwraca id kompilacji przetwarzanego szablonu
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getCompileId() {
		return parent::getCompileId();
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
		parent::setDisplay($display);
	}
	
	/*
	 * Zwraca wartosc bool odp za natychmiatowe wyswietlanie szablonu po przetworzeniu
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 	
	public function getDisplay() {
		return parent::getDisplay();
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
		parent::setLifeTime($lifeTime);
	}
	
	/*
	 * Zwraca czas zycia przetwarzanego szablonu
	 * 
	 * @access public
	 * @return int
	 * 
	 */ 	
	public function getLifeTime() {
		return parent::getLifeTime();
	}
	
}

?>
