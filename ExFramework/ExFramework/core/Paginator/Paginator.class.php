<?php

/**
 * @class Paginator
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Paginator implements IPaginator {
	
	/**
	 * Numer aktualnej strony, liczony od zera
	 *
	 * @var int
	 * 
	 */	
	protected $_currentPageNumber = 0;
	
	/**
	 * Ilosc rekordow
	 *
	 * @var int
	 * 
	 */	
	protected $_numberOfRecords = null;
	
	/**
	 * Ilosc rekordow na strone
	 *
	 * @var int
	 * 
	 */	
	protected $_recordsPerPage = 10;	
	
	/**
	 * Ilosc linkow jaka bedzie wyswietlona maksymalnie pomiedzy linkami - nastepny, poprzedni
	 *
	 * @var int
	 * 
	 */	
	protected $_midRange = 10;
	
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
	 * Paginator View Helper
	 *
	 * @var object
	 * 
	 */	
	protected $_paginatorViewHelper = null;		
	
	/**
	 * Ilosc stron
	 *
	 * @var int
	 * 
	 */	
	protected $_numberOfPages = null;	
	
	/**
	 * Czy generowac linki do strony pierwszej i ostatniej
	 *
	 * @var bool
	 * 
	 */	
	protected $_firstLast = null;	
	
	/**
	 * Delimiter
	 *
	 * @var string
	 * 
	 */	
	protected $_delimiter = '...';
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * @return void
	 * 
	 */
	public function __construct(IViewHtmlHelper $paginatorViewHelper, $recordsPerPage=10, $getVarName='page', $midRange=10, $firstLast=true, $delimiter='...') {
		$this->_paginatorViewHelper = $paginatorViewHelper;
		$this->setRecordsPerPage($recordsPerPage);
		$this->setGetVarName($getVarName);
		$this->setMidRange($midRange);
		$this->setFirstLast($firstLast);
		$this->setDelimiter($delimiter);
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
		$this->_currentPageNumber = (int)$currentPageNumber; //jesli nie bedzie ustawionej pierwszej strony, jej numer automatycznie bedzie wynosil 0
	}
	
	/**
	 * Ustawia ilosc rekordow
	 * 
	 * @access public
	 * @param int Ilosc rekordow
	 * @return void
	 * 
	 */	
	public function setNumberOfRecords($numberOfRecords) {
		$this->_numberOfRecords = (int)$numberOfRecords;
	}
	
	/**
	 * Ustawia ilosc rekordow na strone
	 * 
	 * @access public
	 * @param int Ilosc rekordow na strone
	 * @return void
	 * 
	 */		
	public function setRecordsPerPage($recordsPerPage=10) {
		if($recordsPerPage == 0) {
			$recordsPerPage = 1;
		}
		$this->_recordsPerPage = (int)$recordsPerPage;
	}
	
	/**
	 * Ustawia maksymalna ilosc wyswietlanych linkow 
	 * 
	 * @access public
	 * @param int Ilosc linkow
	 * @return void
	 * 
	 */		
	public function setMidRange($midRange=10) {
		$this->_midRange = (int)$midRange-1;
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
	 * Ustawia czy generowac linki do pierwszej i ostatniej strony
	 * 
	 * @access public
	 * @param bool Czy generowac linki do krancowych stron
	 * @return void
	 * 
	 */		
	public function setFirstLast($firstLast=true) {
		$this->_firstLast = $firstLast;
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
	 * Zwraca limit dla zapytania SQL
	 * 
	 * @access public
	 * @return int
	 * 
	 */			
	public function getLimit() {
		return $this->_currentPageNumber * $this->_recordsPerPage;
	}
	
	/**
	 * Zwraca offset dla zapytania SQL
	 * 
	 * @access public
	 * @return int
	 * 
	 */			
	public function getOffset() {
		return $this->_recordsPerPage;
	}	
	
	/**
	 * Zwraca tablice z numerami stron i informacja o linkach
	 * 
	 * @access public
	 * @return array
	 * 
	 */			
	public function getNavigationArray() {
		return $this->createNavigationArray();
	}		
	
	/**
	 * Zwraca obiekt helpera tworzacy linki nawigacyjne
	 * 
	 * @access public
	 * @return object
	 * 
	 */			
	public function getPaginator() {
		$this->_paginatorViewHelper->setLink($this->_link);
		$this->_paginatorViewHelper->setGetVarName($this->_getVarName);
		$this->_paginatorViewHelper->setDelimiter($this->_delimiter);
		$this->_paginatorViewHelper->setCurrentPageNumber($this->_currentPageNumber);
		
		$navigationArray = $this->createNavigationArray();
		$this->_paginatorViewHelper->setNavigationArray($navigationArray);
		$this->_paginatorViewHelper->setNumberOfPages($this->_numberOfPages);		
		return $this->_paginatorViewHelper;
	}	
	
	/**
	 * Ustawia ilosc stron
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function setNumberOfPages(){
		$numberOfPages = ceil($this->_numberOfRecords / $this->_recordsPerPage);
		if(!$numberOfPages){
			$numberOfPages++;
		}
		$this->_numberOfPages = $numberOfPages;
	}
		
	/**
	 * Tworzy tablice z numerami stron, informacja o aktywnych linkach i aktywnej stronie
	 * 
	 * @access protected
	 * @return array
	 * 
	 */		
	protected function createNavigationArray() {
		$navigationArray = array(); //tablica z numerami stron
		if($this->_numberOfRecords === null) {
			throw new PaginatorException('Nie ustawiono liczby rekordow');
		}
		if($this->_link === null) {
			throw new PaginatorException('Nie ustawiono adresu strony');
		}
		if($this->_numberOfRecords === 0) {
			return $navigationArray;
		}
		$this->setNumberOfPages(); //ustawia liczbe stron
		if($this->_currentPageNumber >= $this->_numberOfPages) { //jesli aktualna strona ma wiekszy numer niz stron jest w rzeczywistosci, ustawiony zostaje ostatni numer strony
			$this->_currentPageNumber = $this->_numberOfPages - 1;
		}
		if($this->_currentPageNumber < 0) { //jesli aktualny numer strony jest mniejszy od zera, ustawiony zostaje numer strony 0
			$this->_currentPageNumber = 0;
		}
		
		if($this->_currentPageNumber > 0){ //obsluga lnkow do strony pierszej i poprzedniej
			$prevPage = $this->_currentPageNumber - 1;
			if($this->_firstLast == true) {
				$navigationArray['first']['number'] = 0;
				$navigationArray['first']['visible'] = 1;
				$navigationArray['first']['active'] = 1;
				$navigationArray['first']['text'] = 'First';				
			}
			$navigationArray['prev']['number'] = $prevPage;
			$navigationArray['prev']['visible'] = $prevPage + 1;
			$navigationArray['prev']['active'] = 1;
			$navigationArray['prev']['text'] = 'Prev';
		}
		else{
			$prevPage = $this->_currentPageNumber - 1;
			if($this->_firstLast == true) {
				$navigationArray['first']['number'] = 0;
				$navigationArray['first']['visible'] = 1;
				$navigationArray['first']['active'] = 0;
				$navigationArray['first']['text'] = 'First';
			}			
			$navigationArray['prev']['number'] = $prevPage;
			$navigationArray['prev']['visible'] = $prevPage + 1;
			$navigationArray['prev']['active'] = 0;
			$navigationArray['prev']['text'] = 'Prev';			
		}

		if($this->_currentPageNumber < ($this->_numberOfPages - 1)) { //obsluga linkow do strony nastepnej i ostatniej
			$nextPage = $this->_currentPageNumber + 1;
			$lastPage = $this->_numberOfPages - 1;
			if($this->_firstLast == true) {
				$navigationArray['last']['number'] = $lastPage;
				$navigationArray['last']['visible'] = $lastPage + 1;
				$navigationArray['last']['active'] = 1;
				$navigationArray['last']['text'] = 'Last';					
			}				
			$navigationArray['next']['number'] = $nextPage;
			$navigationArray['next']['visible'] = $nextPage + 1;
			$navigationArray['next']['active'] = 1;
			$navigationArray['next']['text'] = 'Next';	
		}
		else{
			$nextPage = $this->_currentPageNumber + 1;
			$lastPage = $this->_numberOfPages - 1;
			if($this->_firstLast == true) {
				$navigationArray['last']['number'] = $lastPage;
				$navigationArray['last']['visible'] = $lastPage + 1;
				$navigationArray['last']['active'] = 0;
				$navigationArray['last']['text'] = 'Last';
			}				
			$navigationArray['next']['number'] = $nextPage;
			$navigationArray['next']['visible'] = $nextPage + 1;
			$navigationArray['next']['active'] = 0;
			$navigationArray['next']['text'] = 'Next';
		}
		
		//obsluga pozostalych linkow
		$endTemp = null;
		$startTemp = null;
		$halfRange = ceil($this->_midRange / 2);
		if($this->_currentPageNumber < $halfRange){
			$endTemp = (2 * $halfRange); //np jesli page=4 to ostatnie wyswietlane link bedzie z page=10, jesli aktualny nr strony jest mniejszy niz polowa z wyswietlanych wtedy koncowa liczba na liscie bedzie wynosic 2 * polowa wyswietlanych + 1
		}
		else{
			$endTemp = $this->_currentPageNumber + $halfRange; //np jesli page=5 to ostatni link bedzie z page=10 itd (5+5 , 6+5 ...), jesli aktualny nr strony jest wiekszy lub rowny polowie wtedy koncowa liczba wyswietlana bedzie rowna aktualnemu numerowi strony + polowa 
		}
		if($endTemp > $this->_numberOfPages){
			$endTemp = $this->_numberOfPages - 1; //np liczba stron 20 ostatni link widoczny bedzie z page=19
		}
		if($this->_currentPageNumber >= ($this->_numberOfPages - $halfRange)){
			$startTemp = $this->_numberOfPages - (2 * $halfRange) - 1; //np jesli aktualny nr page=15, a stron jest 20 to, pierwszy link bedzie z cp=20-2*5-1 czyli =9
		}
		else{
			$startTemp = $this->_currentPageNumber - $halfRange;
		}
		if($startTemp < 0){
			$startTemp = 0;
		}
		
		for($i=$startTemp; $i<=$endTemp; $i++){
			$tmpArr = array();
			if($i != $this->_currentPageNumber){
				$tmpArr['number'] = $i;
				$tmpArr['visible'] = $i+1;
				$tmpArr['active'] = 1;
			}
			else{
				$tmpArr['number'] = $i;
				$tmpArr['visible'] = $i+1;
				$tmpArr['active'] = 0;
			}
			$navigationArray['midlinks'][] = $tmpArr;
		}
		return $navigationArray;
	}	
	
}

?>
