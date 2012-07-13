<?php

class AdminUnitAdd extends AdminBaseControllerActionAbstract implements IController {
	
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
		
		//pobieranie danych z modelu, zapisywanie danych o definicji jednostki		
		$unit = $this->_unitMapper->create($postValues);
		$result = $this->_unitMapper->save($unit);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_unitMapper->hasErrors()) {
			$errors = $this->_unitMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Unit has not been added to data base'));
			$this->_session->setFlashData('unitAddFormValues', $postValues);
			$this->_session->setFlashData('unitAddErrors', $errors);
			$this->redirect($baseUrl.'/admin/unit/addForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('Unit has been added to data base'));
			$this->redirect($baseUrl.'/admin/unit/list');
		}
	}
	
}
?>
