<?php

class AdminEntryEdit extends AdminBaseControllerActionAbstract implements IController {
	
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
		$entryId = null;
		if(isset($postValues['id'])) {
			$entryId = (int)$postValues['id'];
		}
		if(($entryId == '') || ($entryId == null)) {
			throw new ControllerException('Brak identyfikatora wpisu menu');
		}
		
		//pobieranie danych z modelu
		$entriesCollection = $this->_entryMapper->getById($entryId);
		if(count($entriesCollection) != 1) {
			throw new ControllerException('Wpis menu nie istnieje w bazie danych');
		}
		$entry = $entriesCollection[0];

		//wypelnienie obiektu wpisu menu, danymi z formularza
		foreach($postValues AS $index=>$value) {
			$entry->{$index} = $value;
		}
		//wstepne sprawdzanie
		$result = $this->_entryMapper->validate($postValues, 'update');
		$errors = array();
		if($this->_entryMapper->hasErrors()) {
			$errors = $this->_entryMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Entry has not been updated in data base'));
			$this->_session->setFlashData('entryEditFormValues', $postValues);
			$this->_session->setFlashData('entryEditErrors', $errors);
			$this->redirect($baseUrl.'/admin/entry/editForm');
		}		
		
		//obsluga menu
		$menuToAdd = null;
		$menuToDelete = null;
		if(isset($postValues['menuId'])) {
			$inDbMenusCollection =  $entry->menus->getCollection();				
			$inDbMenu = $inDbMenusCollection[0];
			if($inDbMenu->id != $postValues['menuId']) {
				$menuToDelete = $inDbMenu;
				$postMenuCollection = $this->_menuMapper->getById($postValues['menuId']);
				if(count($postMenuCollection) == 0) {
					throw new ControllerException('Menu o podanym identyfikatorze nie istnieje w bazie danych');
				}	
				$menuToAdd = $postMenuCollection[0];
			}

		}
		
		//usuniecie powiazan  
		if($menuToDelete != null) {
			$delResult = $this->_entryMapper->delete($entry, $menuToDelete);
		}
		//zapisywanie danych o wpisie menu
		$result = $this->_entryMapper->save($entry, $menuToAdd);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_entryMapper->hasErrors()) {
			$errors = $this->_entryMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Entry has not been updated in data base'));
			$this->_session->setFlashData('entryEditFormValues', $postValues);
			$this->_session->setFlashData('entryEditErrors', $errors);
			$this->redirect($baseUrl.'/admin/entry/editForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('Entry has been updated in data base'));
			$this->redirect($baseUrl.'/admin/entry/list');
		}
		
	}
	
}

?>
