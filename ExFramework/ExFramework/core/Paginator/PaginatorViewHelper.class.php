<?php

/**
 * @class PaginatorViewHelper
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class PaginatorViewHelper implements IViewHtmlHelper {
	
	/**
	 * Adres, do ktorego beda doklejane zmienne z kolejnymi numerami stron
	 *
	 * @var string
	 * 
	 */	
	protected $_link = null;	
	
	/**
	 * Nazwa zmiennej doklejana do adresu, przechowujaca kolejne numery stron
	 *
	 * @var string
	 * 
	 */	
	protected $_getVarName = 'page';
	
	/**
	 * Tablica z informacjami do tworzonych odnosnikow
	 *
	 * @var array
	 * 
	 */		
	protected $_navArray = array();
	
	/**
	 * Delimiter
	 *
	 * @var string
	 * 
	 */	
	protected $_delimiter = '...';
	
	/**
	 * Numer aktualnej strony, liczony od zera
	 *
	 * @var int
	 * 
	 */		
	protected $_currentPageNumber = 0;

	/**
	 * Ilosc stron
	 *
	 * @var int
	 * 
	 */	
	protected $_numberOfPages = null;	

	/**
	 * Obiekt i18n
	 *
	 * @var object
	 * 
	 */	
	protected $_i18n = null;	
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @return void
	 * 
	 */
	public function __construct(II18n $i18n) {
		$this->_i18n = $i18n;
	}
	
	/**
	 * Ustawia adres do ktorego beda doklejane zmienne przechowujace numery stron
	 * 
	 * @access public
	 * @param string Adres strony
	 * @return void
	 * 
	 */		
	public function setLink($link) {
		if(!strstr($link, 'http://')) {
			$link = 'http://' . $link;
		}
		$this->_link = rtrim($link, '/') . '/';
	}
	
	/**
	 * Ustawia nazwe zmiennej przechowujacej numery stron
	 * 
	 * @access public
	 * @param string Nazwa zmiennej _GET dla numerow stron
	 * @return void
	 * 
	 */		
	public function setGetVarName($getVarName='page') {
		$this->_getVarName = $getVarName;
	}
	
	/**
	 * Ustawia delimiter
	 * 
	 * @access public
	 * @param string Delimiter
	 * @return void
	 * 
	 */		
	public function setDelimiter($delimiter='...') {
		$this->_delimiter = $delimiter;
	}
	
	/**
	 * Ustawia aktualny numer strony
	 * 
	 * @access public
	 * @param int Numer strony, liczony od zera
	 * @return void
	 * 
	 */
	public function setCurrentPageNumber($currentPageNumber=0) {
		$this->_currentPageNumber = (int)$currentPageNumber;
	}
	
	/**
	 * Ustawia ilosc stron
	 * 
	 * @access public
	 * @param int Ilosc stron
	 * @return void
	 * 
	 */		
	public function setNumberOfPages($numberOfPages){
		$this->_numberOfPages = $numberOfPages;
	}	
	
	/**
	 * Ustawia tablice z numerami i informacjami o linkach
	 * 
	 * @access public
	 * @param array Tablica z numerami stron i informacjami dla linkow
	 * @return void
	 * 
	 */	
	public function setNavigationArray($navArray) {
		$this->_navArray = $navArray;
	}
	
	/**
	 * Tworzy i zwraca ciag HTML
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function fetchHtml() {
		if(count($this->_navArray) === 0) {
			return '';
		}
		$linksHtml = '';
		$linksHtml .= "<div class=\"paginator\">\n";
		if(isset($this->_navArray['first'])) {
			if($this->_navArray['first']['active'] == 1) {
				$linksHtml .= $this->createHtmlLink($this->_navArray['first']['number'], $this->_i18n->translate($this->_navArray['first']['text']));
			}
			else {
				$linksHtml .= '['. $this->_i18n->translate($this->_navArray['first']['text']) .'] ';
			}
		}
		if($this->_navArray['prev']['active'] == 1) {
			$linksHtml .= $this->createHtmlLink($this->_navArray['prev']['number'], $this->_i18n->translate($this->_navArray['prev']['text']));
		}
		else {
			$linksHtml .= '['. $this->_i18n->translate($this->_navArray['prev']['text']) .'] ';
		}
		$linksHtml .= $this->_delimiter . ' ';		
		foreach($this->_navArray['midlinks'] AS $midLink) {
			if($midLink['active'] == 1) {
				$linksHtml .= $this->createHtmlLink($midLink['number'], $midLink['visible']);
			}
			else {
				$linksHtml .= '['. $midLink['visible'] .'] ';
			}				
		}
		$linksHtml .= $this->_delimiter . ' ';
		if($this->_navArray['next']['active'] == 1) {
			$linksHtml .= $this->createHtmlLink($this->_navArray['next']['number'], $this->_i18n->translate($this->_navArray['next']['text']));
		}
		else {
			$linksHtml .= '['. $this->_i18n->translate($this->_navArray['next']['text']) .'] ';
		}		
		if(isset($this->_navArray['last'])) {
			if($this->_navArray['last']['active'] == 1) {
				$linksHtml .= $this->createHtmlLink($this->_navArray['last']['number'], $this->_i18n->translate($this->_navArray['last']['text']));
			}
			else {
				$linksHtml .= '['. $this->_i18n->translate($this->_navArray['last']['text']) .'] ';
			}
		}
		$linksHtml .= "</div>\n";
		$linksHtml .= "<div class=\"paginator_info\">\n";
		$visiblePageNumber = $this->_currentPageNumber + 1;
		$linksHtml .= $this->_i18n->translate('Page') . ' ' . $visiblePageNumber . ' ' . $this->_i18n->translate('of') . ' ' . $this->_numberOfPages . '';
		$linksHtml .= "</div>\n";
		return $linksHtml;

		//$this->_navigationSystemLinks = $startLinks . $numberLinks . $endLinks;
		//$this->_navigationSystemInfo = 'Page ' . $this->_currentPageNumber + 1 . ' of ' . $this->_numberOfPages . '.';			
		
	}
	
	/**
	 * Tworzy odnosnik z dostarczonych informacji
	 * 
	 * @access public
	 * @param int Numer strony - 1
	 * @param string Tekst odnosnika
	 * @return string
	 * 
	 */		
	protected function createHtmlLink($pageNumber, $text){
		$linkHtml = '[<a href="' . $this->_link . $this->_getVarName . '/'. $pageNumber . '">' . $text . '</a>] ';
		return $linkHtml;
	}	
	
}
