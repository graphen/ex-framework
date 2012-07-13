<?php

class AdminMenuAdd extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_menuMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, MenuMapper $menuMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_menuMapper = $menuMapper;
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
		
		//pobieranie danych z modelu, zapisywanie danych o menu		
		$menu = $this->_menuMapper->create($postValues);
		$result = $this->_menuMapper->save($menu);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_menuMapper->hasErrors()) {
			$errors = $this->_menuMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Menu has not been added to data base'));
			$this->_session->setFlashData('menuAddFormValues', $postValues);
			$this->_session->setFlashData('menuAddErrors', $errors);
			$this->redirect($baseUrl.'/admin/menu/addForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('Menu has been added to data base'));
			$this->redirect($baseUrl.'/admin/menu/list');
		}
	}
	
}
?>
