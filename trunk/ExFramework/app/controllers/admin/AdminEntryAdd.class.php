<?php

class AdminEntryAdd extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_entryMapper = null;
	protected $_menuMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, EntryMapper $entryMapper, MenuMapper $menuMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_entryMapper = $entryMapper;
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
		
		//zapisywanie danych o wpisie menu
		$entry = $this->_entryMapper->create($postValues);
		//wstepne strawdzanie
		$result = $this->_entryMapper->validate($postValues, 'insert');
		$errors = array();
		if($this->_entryMapper->hasErrors()) {
			$errors = $this->_entryMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Entry has not been added to data base'));
			$this->_session->setFlashData('entryAddFormValues', $postValues);
			$this->_session->setFlashData('entryAddErrors', $errors);
			$this->redirect($baseUrl.'/admin/entry/addForm');
		}			
		
		$menu = null;
		if(isset($postValues['menuId'])) {
			$menusCollection = $this->_menuMapper->getById($postValues['menuId']);
			if(count($menusCollection) == 0) {
				throw new ControllerException('Menu o podanym identyfikatorze nie istnieje w bazie danych');
			}
			$menu = $menusCollection[0];
		}
		$result = $this->_entryMapper->save($entry, $menu);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_entryMapper->hasErrors()) {
			$errors = $this->_entryMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Entry has not been added to data base'));
			$this->_session->setFlashData('entryAddFormValues', $postValues);
			$this->_session->setFlashData('entryAddErrors', $errors);
			$this->redirect($baseUrl.'/admin/entry/addForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('Entry has been added to data base'));
			$this->redirect($baseUrl.'/admin/entry/list');
		}
		
	}
	
}

?>
