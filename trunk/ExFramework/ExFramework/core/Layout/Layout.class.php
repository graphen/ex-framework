<?php

/**
 * @class Layout
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Layout implements ILayout {

	/**
	 * Typ MIME dokumentu
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_viewType = 'text/html';

	/**
	 * Silnik szablonow
	 * 
	 * @var object
	 * 
	 */ 	
	protected $_templateEngine = null;
	
	/**
	 * Tablica z widgetami, obiektami widokow
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_widgets = array();
	
	/**
	 * Sciezka do szablonu layoutu
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_layoutPath = null;
	
	/**
	 * Zawartosc strony, obiekt widoku zawartosci strony
	 * 
	 * @var object
	 * 
	 */ 	
	protected $_content = null;
	
	/**
	 * Zawartosc menu, obiekt widoku menu
	 * 
	 * @var object
	 * 
	 */ 	
	protected $_mainMenu = null;
	
	/**
	 * Sparsowane czesci strony
	 * 
	 * @var object
	 * 
	 */ 	
	protected $_parsedBlocks = array();
	
	/**
	 * Sciezka do katalogu publicznego
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_publicPath = null;
	
	/**
	 * Sciezka do katalogu z plikami css
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_cssPath = null;
	
	/**
	 * Sciezka do katalogu z plikami js
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_javaScriptPath = null;
	
	/**
	 * Sciezka do kataloguz plikami graficznymi
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_gfxPath = null;
	
	/**
	 * Typ dokumentu wysylanego jako strona do przegladarki
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_docType = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	
	/**
	 * Tytul strony
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_title = '';
	
	/**
	 * Sciezka do ikonki
	 * 
	 * @var string
	 * 
	 */ 	
	protected $_favicon = '';
	
	/**
	 * Tablica tagow meta
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_meta = array();
	
	/**
	 * Tablica tagow http meta
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_httpMeta = array();
	
	/**
	 * Tablica z definicjami dolaczanych plikow css
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_css = array();
	
	/**
	 * Tablica z definicjami dolaczanych plikow js
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_javaScript = array();
	
	/**
	 * Tablica z kodami srodlowymi dolaczanych skryptow js
	 * 
	 * @var array
	 * 
	 */ 	
	protected $_scripts = array();	
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @param object ITemplate Silnik szablonow
	 * @param string Sciezka do katalogu publicznego
	 * @param string Sciezka do katalogu z plikami css
	 * @param string Sciezka do plikow ze skryptami js
	 * @param string Sciezka do katalogu z plikami graficznymi
	 * @param string DOCTYPE
	 * @param string Tytul
	 * @param string Nazwa pliku ikonki
	 * @param array Zestaw znacznikow meta
	 * @param array Zestaw znacznikow htta meta
	 * @param array Zestaw zancznikow opisujacych dolaczane pliki css
	 * @param array Zestaw znacznikow opisujacych dolaczane pliki js
	 * 
	 */		
	public function __construct(ITemplate $templateEngine, $publicPath=null, $cssPath=null, $jsPath=null, $gfxPath=null, $docType=null, $title=null, $icon=null, $meta=null, $httpMeta=null, $css=null, $js=null) {
		$this->_templateEngine = $templateEngine;
		
		if(!empty($publicPath)) {
			$this->_publicPath = $publicPath;
		}
		if(!empty($cssPath)) {
			$this->_cssPath = $cssPath;
		}
		if(!empty($jsPath)) {
			$this->_javaScriptPath = $jsPath;
		}
		if(!empty($gfxPath)) {
			$this->_gfxPath = $gfxPath;
		}
		if(!empty($docType)) {
			$this->_docType = $docType;
		}	
		if(!empty($title)) {
			$this->_title = $title;
		}
		if(!empty($icon)) {
			$this->_favicon = $icon;
		}
		if(!empty($meta)) {
			$this->_meta = $meta;
		}
		if(!empty($httpMeta)) {
			$this->_httpMeta = $httpMeta;
		}
		if(!empty($css)) {
			$this->_css = $css;
		}
		if(!empty($js)) {
			$this->_javaScript = $js;
		}
	}
	
	/**
	 * Buduje cala strone z widokow i dodatkowych zmiennych
	 * 
	 * @access public
	 * @param string Sciezka do szablonu layoutu, domyslnie = null
	 * @return string
	 * 
	 */ 		
	public function fetch($layoutPath=null) {
		if($layoutPath !== null) {
			$this->_layoutPath = $layoutPath;
		} 		
		if($this->_layoutPath === null) {
			throw new LayoutException('Nie podano sciezki do szablonu layoutu');
		}
		if($this->_content !== null) {
			$this->_parsedBlocks['content'] = $this->_content->fetch();
		}
		if($this->_mainMenu !== null) {
			$this->_parsedBlocks['mainMenu'] = $this->_mainMenu->fetch();
		}
		foreach($this->_widgets AS $widgetName => $widget) {
			$this->_parsedBlocks[$widgetName] = $widget->fetch();
		}

		//wszystkie bloki zostaly sparsowane teraz kolej na layout
		$this->_templateEngine->clearAllAssign();
		
		foreach($this->_parsedBlocks AS $blockName => $block) {
			$this->_templateEngine->assign($blockName, $block);
		}
		
		$this->buildDocType();
		$this->buildMeta();
		$this->buildTitle();
		$this->buildCss();
		$this->buildFavicon();
		$this->buildJavaScript();
		$this->buildScriptSource();

		return $this->_templateEngine->fetch($this->_layoutPath);
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
	
	/**
	 * Dodaje widget
	 * 
	 * @access public
	 * @param string Nazwa widgetu
	 * @param object IViewTemplate Widget
	 * @return void
	 * 
	 */ 		
	public function addWidget($widgetName, IViewTemplate $widget) {
		$this->_widgets[$widgetName] = $widget;
	}
	
	/**
	 * Usuwa widget
	 * 
	 * @access public
	 * @param string Nazwa widgetu
	 * @return void
	 * 
	 */ 		
	public function removeWidget($widgetName) {
		if(isset($this->_widgets[$widgetName])) {
			unset($this->_widgets[$widgetName]);
		}
	} 
	
	/**
	 * Ustawia czesciowy widok zawierajacy zawartosc strony
	 * 
	 * @access public
	 * @param object IViewTemplate Widok zawierajacy zawartosc strony
	 * @return void
	 * 
	 */ 		
	public function setContent(IViewTemplate $content) {
		$this->_content = $content;
	}
	
	/**
	 * Ustawia czesciowy widok zawierajacy menu
	 * 
	 * @access public
	 * @param object IViewTemplate Widok zawierajacy menu
	 * @return void
	 * 
	 */ 		
	public function setMainMenu(IViewTemplate $mainMenu) {
		$this->_mainMenu = $mainMenu;
	}	
	
	/**
	 * Ustawia sciezke do szablonu layoutu
	 * 
	 * @access public
	 * @param string Sciezka do szablonu layoutu
	 * @return void
	 * 
	 */ 		
	public function setLayoutPath($layoutPath) {
		$this->_layoutPath = $layoutPath;
	}
	
	//obsluga naglowka
	
	/**
	 * Dodaje typ dokumentu
	 * 
	 * @access public
	 * @param string Typ dokumentu, domyslnie dla XHMTL 1.0
	 * @return void
	 * 
	 */ 		
	public function setDocType($docType='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">') {
		$this->_docType = $docType;
	}
	
	/**
	 * Dodaje tytul strony
	 * 
	 * @access public
	 * @param string Tytul, domyslnie ''
	 * @return void
	 * 
	 */ 		
	public function setTitle($title='') {
		$this->_title = $title;
	}

	/**
	 * Dodaje http meta tag
	 * 
	 * @access public
	 * @param string Wartosc parametru http-equiv
	 * @param string Wartosc parametru content
	 * @return void
	 * 
	 */ 		
	public function addHttpMeta($httpMeta, $content) {
		$this->_httpMeta[(string)$httpMeta] = $content;
	}	
	
	/**
	 * Dodaje meta tag
	 * 
	 * @access public
	 * @param string Wartosc parametru name
	 * @param string Wartosc parametru content
	 * @return void
	 * 
	 */ 	
	public function addMeta($name, $content) {
		$this->_meta[(string)$name] = $content;
	}
	
	/**
	 * Dodaje zrodlo z arkuszem styli
	 * 
	 * @access public
	 * @param string Sciezka do pliku .css
	 * @param string Informacja jakiego typu styl zostanie dolaczony
	 * @param string Informacja o dolaczeniu arkusza styli
	 * @return void
	 * 
	 */ 	
	public function addCss($href, $media='screen', $rel='stylesheet') {
		$fileName = basename($href);
		if(!strstr($href, '/')) {
			$href = trim($this->_cssPath, '/') . '/' . $href;
		}
		$this->_css[(string)$fileName] = array('href'=>$href, 'media'=>$media, 'rel'=>$rel);
	}
	
	/**
	 * Dodaje ikonke
	 * 
	 * @access public
	 * @param string Sciezka do pliku ikonki
	 * @return void
	 * 
	 */ 	
	public function addFavicon($href) {
		$fileName = basename($href);
		if(!strstr($href, '/')) {
			$href = trim($this->_gfxPath, '/') . '/' . $href;
		}		
		$this->_favicon = $href;
	}
	
	/**
	 * Dodaje plik JavaScript
	 * 
	 * @access public
	 * @param string Sciezka do pliku .js
	 * @param string Jezyk skryptu, domyslnie JavaScript
	 * @return void
	 * 
	 */ 	
	public function addJavaScript($src, $language='JavaScript') {
		$fileName = basename($src);
		if(!strstr($src, '/')) {
			$src = trim($this->_javaScriptPath, '/') . '/' . $src;
		}
		$this->_javaScript[(string)$fileName] = array('src'=>$src, 'language'=>$language);
	}
	
	/**
	 * Dodaje zrodlo z kodem JavaScript
	 * 
	 * @access public
	 * @return void
	 * 
	 */ 	
	public function addScriptSource($scriptSource, $language='JavaScript', $type='text/javascript') {
		$this->_scripts[] = array('source'=>$scriptSource, 'language'=>$language, 'type'=>$type);
	}
	
	//------------------------------------------------------------------
	
	/**
	 * Buduje blok wstawiajacy linijke w typem dokumentu
	 * 
	 * @access protected
	 * @return void
	 * 
	 */ 	
	protected function buildDocType() {
		$this->_templateEngine->assign('docType', $this->_docType);		
	}	
	
	/**
	 * Buduje blok wstawiajacyc linijke z tytulem strony
	 * 
	 * @access protected
	 * @return void
	 * 
	 */
	protected function buildTitle() {
		$titleString = ($this->_title != '') ? '<title>' . $this->_title . '</title>' : '';
		$this->_templateEngine->assign('title', $titleString);
	}	
	
	/**
	 * Buduje blok Meta tagow
	 * 
	 * @access protected
	 * @return void
	 * 
	 */ 	
	protected function buildMeta() {
		$metaString = '';
		foreach($this->_httpMeta AS $httpequiv=>$content) {
			$metaString .= "<meta http-equiv=\"" . $httpequiv . "\" content=\"" . $content . "\" />\n";
		}
		foreach($this->_meta AS $name=>$content) {
			$metaString .= "<meta name=\"" . $name . "\" content=\"" . $content . "\" />\n";
		}
		$this->_templateEngine->assign('meta', $metaString);		
	}	
	
	/**
	 * Buduje blok linii wstawiajacych pliki styli
	 * 
	 * @access protected
	 * @return void
	 * 
	 */ 	
	protected function buildCss() {
		$cssString = '';
		foreach($this->_css AS $css) {
			$cssString .= "<link rel=\"" . $css["rel"] . "\" type=\"text/css\" media=\"" . $css["media"] . "\" href=\"" . $css["href"] . "\" />\n";
		}
		$this->_templateEngine->assign('css', $cssString);		
	}
	
	/**
	 * Buduje linijke wstawiajaca ikone
	 * 
	 * @access protected
	 * @return void
	 * 
	 */ 
	protected function buildFavicon() {
		$faviconString = ($this->_favicon != '') ? "<link rel=\"icon\" href=\"" . $this->_favicon . "\" type=\"image/x-icon\" />\n<link rel=\"shortcut icon\" href=\"" . $this->_favicon . "\" type=\"image/x-icon\" />\n" : '';
		$this->_templateEngine->assign('favicon', $faviconString);
	}
	
	/**
	 * Buduje blok JavaScript
	 * 
	 * @access protected
	 * @return void
	 * 
	 */ 	
	protected function buildJavaScript() {
		$javaScriptString = '';
		foreach($this->_javaScript AS $js) {
			$javaScriptString .= "<script language=\"" . $js["language"] . "\" type=\"text/javascript\" src=\"" . $js["src"] . "\"></script>\n";
		}
		$this->_templateEngine->assign('javaScript', $javaScriptString);		
	}
	
	/**
	 * Buduje blok skryptu
	 * 
	 * @access protected
	 * @return void
	 * 
	 */ 	
	protected function buildScriptSource() {
		$src = '';
		foreach($this->_scripts AS $source) {
			$src = "<script language=\"" . $source["language"] . "\" type=\"" . $source["type"] . "\">";
			if(!strstr($source["source"], '<![CDATA[')) {
				$src .= '//<![CDATA[' . "\n" . $source["source"] . "\n" . '//]]>' . "\n";
			}
			$src .= "</script>\n";
		}
		$this->_templateEngine->assign('javaScriptSource', $src);	
	}
	
	//koniec obslugi naglowka
	
}

?>
