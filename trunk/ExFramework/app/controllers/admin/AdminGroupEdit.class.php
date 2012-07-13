<?php

class AdminGroupEdit extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_groupMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, GroupMapper $groupMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_groupMapper = $groupMapper;
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
		$groupId = (int)$postValues['id'];
		if(($groupId == '') || ($groupId == null)) {
			throw new ControllerException('Brak identyfikatora grupy');
		}
		
		//pobieranie danych z modelu		
		$groupRelationsNames = $this->_groupMapper->getRelationsNames();
		$groupsCollection = $this->_groupMapper->getById($groupId);
		if(count($groupsCollection) != 1) {
			throw new ControllerException('Grupa nie istnieje w bazie danych');
		}
		$group = $groupsCollection[0];
		
		//wypelnienie obiektu uzytkownika, danymi z formularza
		foreach($postValues AS $index=>$value) {
			$group->{$index} = $value;
		}
		
		//zapisywanie danych o uzytkowniku
		$result = $this->_groupMapper->save($group);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_groupMapper->hasErrors()) {
			$errors = $this->_groupMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Group has not been updated in data base'));
			$this->_session->setFlashData('groupEditFormValues', $postValues);
			$this->_session->setFlashData('groupEditErrors', $errors);
			$this->redirect($baseUrl.'/admin/group/editForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('Group has been updated in data base'));
			$this->redirect($baseUrl.'/admin/group/list');
		}
		
	}
	
}

?>
