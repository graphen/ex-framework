<?php

class AdminUnitEdit extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_unitMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UnitMapper $unitMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_unitMapper = $unitMapper;
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$userLogin = null;
		$userIsAuth = false;
		$userLogin = $this->_session->userLogin;
		$userIsAuth = $this->_session->userIsAuth;
		if(empty($userLogin) || ($userIsAuth == false)) {
			throw new ControllerException('Brak dostepu');
		}		
		
		$baseUrl = $this->_request->getBaseUrl();
		$postValues = $this->_request->post();
		$unitId = (int)$postValues['id'];
		if(($unitId == '') || ($unitId == null)) {
			throw new ControllerException('Brak identyfikatora def. jednostki');
		}
		
		//pobieranie danych z modelu		
		$unitRelationsNames = $this->_unitMapper->getRelationsNames();
		$unitsCollection = $this->_unitMapper->getById($unitId);
		if(count($unitsCollection) != 1) {
			throw new ControllerException('Def. jednostki nie istnieje w bazie danych');
		}
		$unit = $unitsCollection[0];
		
		//wypelnienie obiektu jednostki, danymi z formularza
		foreach($postValues AS $index=>$value) {
			$unit->{$index} = $value;
		}
		
		//zapisywanie danych o jednostce
		$result = $this->_unitMapper->save($unit);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_unitMapper->hasErrors()) {
			$errors = $this->_unitMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Unit has not been updated in data base'));
			$this->_session->setFlashData('unitEditFormValues', $postValues);
			$this->_session->setFlashData('unitEditErrors', $errors);
			$this->redirect($baseUrl.'/admin/unit/editForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('Unit has been updated in data base'));
			$this->redirect($baseUrl.'/admin/unit/list');
		}
	}
	
}

?>
