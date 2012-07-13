<?php

/**
 * @class Config
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Config extends ParserComposite implements IConfig {
	
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
			$this->_parsers['php']->setArrayName('config');
		}
	}
	
	/**
	 * Dodaje plik z danymi
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */
	public function addConfigFile($fileName) {
		$this->addFile($fileName);
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
	public function getConfig($dataIndex) {
		return $this->getData($dataIndex);
	}

	/**
	 * Pobranie grupy danych
	 *
	 * @access public
	 * @param string Grupa danych
	 * @return array or null
	 * 
	 */	 
	public function getConfigGroup($group) {
		return $this->getDataGroup($group);
	}

	/**
	 * Usuniecie grupy danych
	 *
	 * @access public
	 * @param string Grupa danych, ktora ma zostac usunieta
	 * @return void
	 * 
	 */		 		
	public function unsetConfigGroup($group) {
		$this->unsetDataGroup($group);
	}

	/**
	 * Pobranie tabeli z danymi
	 * Jesli indeksy sie powtarzaja zostana nadpisane
	 *
	 * @access public
	 * @return array
	 * 
	 */	
	public function getConfigs() {
		return $this->getAll();
	}
	
}

?>
