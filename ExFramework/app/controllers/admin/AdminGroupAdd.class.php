<?php

class AdminGroupAdd extends AdminBaseControllerActionAbstract implements IController {
	
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
		
		//pobieranie danych z modelu, zapisywanie danych o grupie		
		$group = $this->_groupMapper->create($postValues);
		$result = $this->_groupMapper->save($group);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_groupMapper->hasErrors()) {
			$errors = $this->_groupMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Group has not been added to data base'));
			$this->_session->setFlashData('groupAddFormValues', $postValues);
			$this->_session->setFlashData('groupAddErrors', $errors);
			$this->redirect($baseUrl.'/admin/group/addForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('Group has been added to data base'));
			$this->redirect($baseUrl.'/admin/group/list');
		}
		
	}
	
}

?>
