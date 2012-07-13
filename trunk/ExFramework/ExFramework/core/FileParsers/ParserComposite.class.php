<?

/**
 * @class ParserComposite
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ParserComposite extends ParserAbstract implements IParserComposite {
	
	/**
	 * Tablica parserow
	 *
	 * @var array
	 * 
	 */	
	protected $_parsers = array();
	
	/**
	 * 
	 * Konstruktor
	 * 
	 * @param ParserPhp
	 * @param ParserIni
	 * @param ParserXml
	 * @access public
	 * 
	 */
	public function __construct(ParserPhp $phpParser, ParserIni $iniParser, ParserXml $xmlParser) {
		$this->addDataParser($phpParser, 'php');
		$this->addDataParser($iniParser, 'ini');
		$this->addDataParser($xmlParser, 'xml');
	}

	/**
	 * 
	 * Dodaje parsery do tablicy
	 * 
	 * @access public
	 * @param object Parser plikow
	 * @param string Id
	 * @return void
	 * 
	 */	
	public function addDataParser(IParser $parser, $id) {
		$this->_parsers[$id] = $parser;
	}
	
	/**
	 * Zwraca obiekt parsera
	 *
	 * @access public
	 * @param string Identyfikator parsera
	 * @return object
	 * 
	 */	
	public function getParser($id) {
		if(isset($this->_parsers[$id])) {
			return $this->_parsers[$id];
		}
		return null;
	}
	
	/**
	 * Dodaje plik z danymi
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */
	public function addFile($fileName) {
		foreach($this->_parsers as $parser) {
			$parser->addFile($fileName);
		}
	}
	 
	/**
	 * Pobranie wartosci ustawienia konfiguracyjnego dla podanego indeksu
	 * Jesli sa dwa takie same indeksy w roznych plikach zostanie zwrocony ostatni napotkany przez ostatniego menadzera
	 * 
	 * @access public
	 * @param string Indeks dla danych
	 * @return mixed 
	 * 
	 */	 
	public function getData($dataIndex) {
		$dataValue = null;
		foreach($this->_parsers as $parser) {
			if(($result = $parser->getData($dataIndex)) !== null) {
				$dataValue = $result;
			}
		}
		return $dataValue;
	}

	/**
	 * Pobranie grupy danych
	 *
	 * @access public
	 * @param string Grupa danych
	 * @return array or null
	 * 
	 */	 
	public function getDataGroup($group) {
		$dataGroup = array();
		$arrTmp = array();
		foreach($this->_parsers as $parser) {
			if(($arrTmp = $parser->getDataGroup($group)) != null) {
				$dataGroup = array_merge($dataGroup, $arrTmp);
			}
		}
		if(count($dataGroup) > 0) {
			return $dataGroup;
		}
		else {
			return null;
		}
	}

	/**
	 * Usuniecie grupy danych
	 *
	 * @access public
	 * @param string Grupa danych, ktora ma zostac usunieta
	 * @return void
	 * 
	 */		 		
	public function unsetDataGroup($group) {
		foreach($this->_parsers as $parser) {
			$parser->unsetDataGroup($group);
		}	
	}

	/**
	 * Pobranie tabeli z danymi
	 * Jesli indeksy sie powtarzaja zostana nadpisane
	 *
	 * @access public
	 * @return array
	 * 
	 */	
	public function getAll() {
		$data = array();
		$arrTmp = array();
		foreach($this->_parsers as $parser) {
			$arrTmp = $parser->getAll();
			$data = array_merge($data, $arrTmp);
		}
		return $data;
	}

	/**
	 * Zrzut tabeli z danymi
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function __toString() {
		foreach($this->_parsers as $parser) {
			return $parser->__toString();
		}
	}

}

?>
