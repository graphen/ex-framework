<?php

/**
 * @class ValidatorInput
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ValidatorInput extends ValidatorAbstract implements IValidatorInput {
	
	/**
	 * Fabryka walidatorow
	 *
	 * @var object
	 * 
	 */			
	protected $_validatorFactory = null;
	
	/**
	 * Tabela regul walidacji
	 *
	 * @var array
	 * 
	 */			
	protected $_rules = array();
	
	/**
	 * Tabela utworzonych walidatorow dla kazdego zadanego pola
	 *
	 * @var array
	 * 
	 */			
	protected $_preparedValidators = array();
	
	/**
	 * Domyslna wartosc okreslajaca czy przerwac lancuch walidacji dla danego pola po napotkaniu bledu
	 *
	 * @var bool
	 * 
	 */			
	protected $_breakChainOnFailureDefault = false;
	
	/**
	 * Domyslna wartosc okreslajaca czy dopuscic pusta wartosc pola, jesli ustawiona na TRUE, to kazde pole moze byc puste
	 *
	 * @var array
	 * 
	 */			
	protected $_allowEmptyDefault = false;
	
	/**
	 * Domyslny komunikat w przypadku kiedy sprawdzane pole jest puste, ale nie zezwolono na to
	 *
	 * @var string
	 * 
	 */	
	protected $_emptyMessageDefault = 'Value is required';
	
	/**
	 * Konstruktor
	 *
	 * @access public
	 * @param object Fabryka obiektow walidatorow
	 * 
	 */		
	public function __construct(IFactory $validatorFactory) {
		$this->_validatorFactory = $validatorFactory;
	}
	
	/**
	 * Przeprowadza weryfikacje danych przekazanych w formie tablicy asocjacyjnej
	 *
	 * @access public
	 * @param string identyfikator, dla impl. interfejsu
	 * @param array Tablica asocjacyjna z danymi do weryfikacji
	 * @param array Tablica opcji/parametrow
	 * @return void
	 * 
	 */		
	public function isValid($id, $data, $options=array()) {
		$this->reset();
		if(count($options) > 0) {
			$this->setParams($options);
		}
		if(count($this->_preparedValidators) == 0) {
			$this->prepareValidators();
			if(count($this->_preparedValidators) == 0) {
				return true;
			}			
		}
		foreach($this->_preparedValidators AS $fieldValidatorName => $fieldValidators) {
			if(is_string($fieldValidators['fields'])) {
				if((!isset($data[$fieldValidators['fields']])) || (isset($data[$fieldValidators['fields']]) && ($data[$fieldValidators['fields']]===''))) { //==na===27.12.2011
					if($fieldValidators['allowEmpty'] === true) {
						if(isset($fieldValidators['default'])) {
							$data[$fieldValidators['fields']] = $fieldValidators['default'];
						}
						continue;
					}
					else {
						$this->_errors[$fieldValidatorName][] = $fieldValidators['emptyMessage'];
						$data[$fieldValidators['fields']] = ''; //jesli w ogole nie ma zmiennej ustawiona zostanie na pusta wartosc aby walidatory sie nie wywalaly jesli nie zostanie przerwany lancuch walidacji
						continue;
					}
				}
				if((isset($data[$fieldValidators['fields']])) && (!empty($data[$fieldValidators['fields']]))) {
					if(!isset($fieldValidators['object'])) { //2011.09.02
						continue;
					}
					$fieldValidators['object']->reset(); //2011.08.18
					if($fieldValidators['object']->isValid($fieldValidatorName, $data[$fieldValidators['fields']])) {
						continue;
					}
					else {
						$errorsFromValidator = $fieldValidators['object']->getErrors(); //pobranie bledow z walidatora
						foreach($errorsFromValidator[$fieldValidatorName] AS $error) { //i wpisanie ich do tablicy bledow obiektu walidatora kompozytu  
							$this->_errors[$fieldValidatorName][] = $error;
						} //tutaj nie trzeba przerywac lancucha walidacji po napotkaniu bledow, zostal on juz przerwany w walidatorze kompozycie
						continue;
					}
				}
			}
			if(is_array($fieldValidators['fields'])) {
				$argArray = array();
				foreach($fieldValidators['fields'] AS $fieldName) {
					$argArray[$fieldName] = isset($data[$fieldName]) ? $data[$fieldName] : '';
				}
				if(!isset($fieldValidators['object'])) { //2011.09.02
					continue;
				}				
				$fieldValidators['object']->reset(); //2011.08.18
				if($fieldValidators['object']->isValid($fieldValidatorName, $argArray)) {
					continue;
				}
				else {
					$errorsFromValidator = $fieldValidators['object']->getErrors();
					foreach($errorsFromValidator[$fieldValidatorName] AS $error) { //i wpisanie ich do tablicy bledow obiektu walidatora kompozytu  
						$this->_errors[$fieldValidatorName][] = $error;
					}
				}				
			}	
		}
		if($this->hasErrors() === false) {
			return true;
		}
		return false;
	}
		
	/**
	 * Ustawia tablice regul walidacji
	 *
	 * @access public
	 * @param array Tablica asocjacyjna z regulami walidacji
	 * @return void
	 * 
	 */		
	public function setRules(Array $rules) {
		$this->_preparedValidators = array();
		$this->_rules = $rules;
	}
	
	/**
	 * Ustawia domyslne parametry
	 *
	 * @access public
	 * @param array Tablica asocjacyjna z domyslnymi parametrami
	 * @return void
	 * 
	 */		
	public function setParams($options) {
		if(isset($options['breakChainOnFailure'])) {
			$this->_breakChainOnFailureDefault = (bool)$options['breakChainOnFailure'];
		}
		if(isset($options['allowEmpty'])) {
			$this->_allowEmptyDefault = (bool)$options['allowEmpty'];
		}	
		if(isset($options['emptyMessage'])) {
			$this->_emptyMessageDefault = (string)$options['emptyMessage'];
		}	
	}
	
	/**
	 * Przygotowuje zestaw walidatorow
	 *
	 * @access public
	 * @return void
	 * 
	 */		
	public function prepareValidators() {
		if(count($this->_rules) == 0) { 
			//throw new ValidatorException('Nie dodano regul walidacji');
			return;
		}
		if(isset($this->_rules['*'])) { //jesli uzyto * do oznaczenia wszystkich pol jakich ma tyczyc sie zestaw regul to
			if(isset($this->_rules['*']['breakChainOnFailure'])) { //wsrod regul moze znalesc sie tylko regula nakazujaca przerwanie lancucha walidacji po napotkaniu bledu
				$this->_breakChainOnFailureDefault = $this->_rules['*']['breakChainOnFailure'];
				unset($this->_rules['*']['breakChainOnFailure']);
			}
			if(isset($this->_rules['*']['allowEmpty'])) { //oraz regula pozwalajaca na puste wartosci wszystkich pol
				$this->_allowEmptyDefault = $this->_rules['*']['allowEmpty'];
				unset($this->_rules['*']['presence']);
			}
		}
		foreach($this->_rules AS $ruleSetName => $ruleSet) { //pozostale zestawy regul beda dotyczyc konkretnych pol
			if($ruleSetName == '*') {
				continue;
			}
			$this->prepareFieldValidators($ruleSetName, $ruleSet);
		}
	}
	
	/**
	 * Przygotowuje zestawy walidatorow dla poszczegolnych pol
	 *
	 * @access protected
	 * @param string Nazwa reguly
	 * @param array Zestaw regul dla pola
	 * @return void
	 * 
	 */		
	protected function prepareFieldValidators($ruleSetName, Array $ruleSet) {
		 //najpierw obsluga metapolecen
			//obsluga nazwy pola
		if(isset($ruleSet['fields'])) { //jesli zdefiniowano metapolecenie fields w zestawie regul dla pola to zestaw bedzie dotyczyc pola/pol oznaczonego/oznaczonych ta nazwa/nazwami, a nie nazwa zestawu regul
			$this->_preparedValidators[$ruleSetName]['fields'] = $ruleSet['fields']; //ta wartosc zostanie zapamietana do czasu wykonania regul walidacji
			unset($ruleSet['fields']);
		}
		else {
			$this->_preparedValidators[$ruleSetName]['fields'] = $ruleSetName; //jesli brak takiej definicji regula bedzie wykonana dla pola o nazwie zestawu regul
		}
			//obsluga przerwania wykonywania lancucha walidacji
		$breakChainTemp = $this->_breakChainOnFailureDefault; //najpierw sparwdzam co ustawiono domyslnie
		if(isset($ruleSet['breakChainOnFailure'])) { //jesli w zestawie regul dla danego pola ustawiono, co nalezy zrobic kiedy wystapi blad nadpisuje domyslna wartosc wartoscia ustawiana w zestawie regul
			$breakChainTemp = $ruleSet['breakChainOnFailure'];  
			unset($ruleSet['breakChainOnFailure']);
		}
		$this->_preparedValidators[$ruleSetName]['breakChainOnFailure'] = $breakChainTemp;
			//obsluga pustych wartosci
		if(isset($ruleSet['allowEmpty'])) { //jesli ustawiono w zestawie regul co zrobic kiedy napotkana zaostanie pusta wartosc
			if(!is_array($this->_preparedValidators[$ruleSetName]['fields'])) { //wartosc ta zostanie zapamietana o ile dotyczy pojedynczego pola
				$this->_preparedValidators[$ruleSetName]['allowEmpty'] = $ruleSet['allowEmpty']; //dla regul dotyczacych kilku pol wprowadzaloby to niejednoznacznosci
			}
			unset($ruleSet['allowEmpty']);
		}
		else {
			if(!is_array($this->_preparedValidators[$ruleSetName]['fields'])) {
				$this->_preparedValidators[$ruleSetName]['allowEmpty'] = $this->_allowEmptyDefault; //jesli nie ustawiono tego w zetswie regul to zostanie uzyta domyslna wartosc dla tego zestawu
			}
		}
			//obsluga domyslnych wartosci
		if(isset($ruleSet['default'])) { //jesli ustalono domyslna wartosc pola w zestawie regul
			if(!is_array($this->_preparedValidators[$ruleSetName]['fields'])) { //to wartosc ta zostanie zapamietana o ile dotyczy pojedynczego pola
				$this->_preparedValidators[$ruleSetName]['default'] = $ruleSet['default']; //jest to wartosc pola w przypadku kiedy dopuszczono mozliwosc pustego pola i nie ustawiono jego wartosci
			}
			unset($ruleSet['default']);
		}
			//obsluga komunikatu pustego pola
		$emptyMessage = $this->_emptyMessageDefault; //domyslna wartosc komunikatu dla sytuacji pustego pola
		if(isset($ruleSet['emptyMessage'])) {
			$emptyMessage = $ruleSet['emptyMessage']; //jesli zdefiniowano regule wiadomosci wyswietlanej jesli pole jest puste to zapamietani jej
			unset($ruleSet['emptyMessage']);
		}
		$this->_preparedValidators[$ruleSetName]['emptyMessage'] = $emptyMessage;

			//obsluga komunikatow bledow
		$tmpMessages = array();
		if(isset($ruleSet['message'])) {
			$tmpMessages = (is_array($ruleSet['message'])) ? $ruleSet['message'] : array($ruleSet['message']); //jesli zdefiniowano regule komunikatow w tym miejscu chwilowo zapamietuje sie jej wartosc jako tablice do dalszej analizy
			unset($ruleSet['message']);
		}
		//wszsytkie reguly zdefiniowane przez metalopolecenia zostaly przeanalizowane i usuniete
		reset($ruleSet); //powrot na poczatek tablicy zestawu regul
		$tmpValidatorsArray = array(); //tablica walidatorow, jesli bedzie ich wiecej niz jeden stworzy sie z nich kompozyt
		
		//echo "<pre>".print_r($ruleSet,true)."</pre><br />";
		
		foreach($ruleSet AS $validatorInd => $validatorDef) { //po usunieciu metapolecen pozostaly tylko definicje walidatorow
 			if(is_string($validatorDef)) { //jesli walidator zdefiniowano jako string, trzeba utworzyc odpowiedni obiekt i ewentualnie skonfigurowac, np dodajac komunikat
				$validatorObj = $this->_validatorFactory->create($validatorDef); //utworzenie walidatora
				$params = array();
				if(isset($tmpMessages[$validatorInd])) { //jesli jest komunikat dla tego walidatora, to dodanie go do parametrow
					$params = array();
					$params['message'] = $tmpMessages[$validatorInd];
				}	
				$validatorObj->reset();
				$tmpValidatorsArray[$validatorInd] = array($validatorObj, $breakChainTemp, $params); //zapamietanie obiektu walidatora razem z jego opcjami i zposobem reakcji na blad jako tablicy w tymczasowej tablicy
			}
			if(is_array($validatorDef)) { //jesli walidator zdefiniowano jako tablice, to jej pierwszy el. jest nazwa klasy walidatora, a drugi to tablica zaweirajaca dane konfiguracyjne walidatora
				if(is_string($validatorDef[0])) { //jesli pierwszy el. tablicy to string wiec musi byc nazwa walidatora
					$validatorObj = $this->_validatorFactory->create($validatorDef[0]); //utworzenie walidatora
					$params = array();
					if(isset($validatorDef[1])) { //jesli jest drugi el. tablicy, wiec sa to parametry konf. walidatora
						$params = (is_array($validatorDef[1])) ? $validatorDef[1] : array($validatorDef[1]); 
					}
					if(isset($tmpMessages[$validatorInd])) { //jesli jest komunikat dla tego walidatora
						$params['message'] = $tmpMessages[$validatorInd];
					}
					$validatorObj->reset();
					$tmpValidatorsArray[$validatorInd] = array($validatorObj, $breakChainTemp, $params); //zapamietanie obiektu walidatora razem z jego opcjami i zposobem reakcji na blad jako tablicy w tymczasowej tablicy
				}
				else {
					throw new ValidatorException('Pierwszy element tablicy musi byc nazwa klasy walidatora');
				}
			}
		}
		if(count($tmpValidatorsArray) > 0) { //jesli obiektow walidatrow jest wiecej to utworzenie kompozytu 
			$validatorComp = $this->_validatorFactory->create('ValidatorComposite');
			foreach($tmpValidatorsArray AS $validator) {
				$validatorComp->addValidator(array($validator[0], $validator[2], $validator[1])); //dodanie walidatora do kompozytu
			}
			$this->_preparedValidators[$ruleSetName]['object'] = $validatorComp; //i zapamietanie kompozytu walidatorow
		}
		//if(count($tmpValidatorsArray) == 1) { //jesli jest jeden
			//$this->_preparedValidators[$ruleSetName]['object'] = $tmpValidatorsArray[0]; //zapamietanie tablicy zawierajacej walidator i jego opcje
		//}
	}
	
	public function __toString() {
		$str = "";		
		$str .= "Reguly: ";
		$str .= "<pre>" . print_r($this->_rules,true) . "</pre><br />";
		$str .= "Walidatory: ";
		$str .= "<pre>" . print_r($this->_preparedValidators,true) . "</pre><br />";
		$str .= "Bledy: ";
		$str .= "<pre>" . print_r($this->_errors,true) . "</pre><br />";				
		return $str;		
	}	
	
}

?>
