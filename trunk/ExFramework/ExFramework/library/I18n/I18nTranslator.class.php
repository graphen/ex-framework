<?php

/**
 * @class I18nTranslator
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class I18nTranslator extends ParserComposite implements II18nTranslator {
	
	/**
	 * 
	 * Konstruktor
	 * 
	 * @access public
	 * @param ParserPhp
	 * @param ParserIni
	 * @param ParserXml
	 * 
	 */
	public function __construct(ParserPhp $phpParser, ParserIni $iniParser, ParserXml $xmlParser) {	 
		parent::__construct($phpParser, $iniParser, $xmlParser);
		if(isset($this->_parsers['php']) && ($this->_parsers['php'] instanceof ParserPhp)) {
			$this->_parsers['php']->setArrayName('lang');
		}
	}
	
	/**
	 * Dodaje plik z tlumaczeniem
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */
	public function addLanguageFile($fileName) {
		$this->addFile($fileName);
	}	
	
	/**
	 * Pobranie tlumaczenia
	 * Jesli sa dwa takie same indeksy w roznych plikach zostanie zwrocony ostatni napotkany przez ostatniego menadzera
	 * 
	 * @access public
	 * @param string Haslo do rzetlumaczenia
	 * @return mixed
	 * 
	 */	 
	public function translate($word) {
		$word = strtolower($word);	
		$result = $this->getData($word);
		if(empty($result)) {
			return $word;
		}
		else {
			return $result;
		}
	}	
	
	/**
	 * Pobranie tlumaczenia
	 * Jesli sa dwa takie same indeksy w roznych plikach zostanie zwrocony ostatni napotkany przez ostatniego menadzera
	 * 
	 * @access public
	 * @param string Haslo do rzetlumaczenia
	 * @return mixed
	 * 
	 */	 
	public function _($word) {
		$word = strtolower($word);
		$result = $this->getData($word);
		if(empty($result)) {
			return $word;
		}
		else {
			return $result;
		}
	}
	
	/**
	 * Pobranie tabeli z tlumaczeniami
	 * Jesli indeksy sie powtarzaja zostana nadpisane
	 *
	 * @access public
	 * @return array
	 * 
	 */	
	public function getTranslations() {
		return $this->getAll();
	}	
	
}

?>
