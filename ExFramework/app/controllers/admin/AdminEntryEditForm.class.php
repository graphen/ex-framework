<?php

class AdminEntryEditForm extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_entryMapper = null;
	protected $_menuMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminEntryEditForm';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, EntryMapper $entryMapper, MenuMapper $menuMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_entryMapper = $entryMapper;
		$this->_menuMapper = $menuMapper;
		$this->_viewResolver = $viewResolver;
		$this->_view = $this->_viewResolver->resolve($this->_templateName, $this->_layoutName, true);
	}
	
	public function execute() {
		
		//pobieranie danych z modelu
		$userLogin = null;
		$userIsAuth = false;
		$userLogin = $this->_session->userLogin;
		$userIsAuth = $this->_session->userIsAuth;
		if(empty($userLogin) || ($userIsAuth == false)) {
			throw new ControllerException('Brak dostepu');
		}			
		
		$entryFieldsNames = $this->_entryMapper->getFieldsNames();
		$entryVirtualFieldsNames = $this->_entryMapper->getVirtualFieldsNames();
		$entryRelationsNames = $this->_entryMapper->getRelationsNames();
		$menuFieldsNames = $this->_menuMapper->getFieldsNames();
				
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();
		$values = $this->_session->getFlashData('entryEditFormValues');
		$errors = $this->_session->getFlashData('entryEditErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');				
		if(!is_array($values)) {
			$entryId = (int)$this->_request->get($entryFieldsNames['id']);		
		}
		else {
			$entryId = (int)$values['id'];
		}
		if(($entryId == '') || ($entryId == null)) {
			throw new ControllerException('Brak identyfikatora wpisu menu');
		}
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi wpisu menu
		$entryToView = null;
		if(!is_array($values)) {
			$entriesCollection = $this->_entryMapper->getById($entryId);
			if(count($entriesCollection) != 1) {
				throw new ControllerException('Wpis menu nie istnieje w bazie danych');
			}
			$entryObject = $entriesCollection[0];
			$entryToView = $entryObject->toArray($entryFieldsNames);
			$entryToView = array_map('htmlspecialchars', $entryToView);
			$menusCollection = $entryObject->menus->getCollection();
			$menuArr = $menusCollection[0]->toArray($menuFieldsNames);
			$entryToView['menuId'] = htmlspecialchars($menuArr['id']);
		}
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi menu
		$menus = $this->_menuMapper->getAll();
		$menusToView = array();
		foreach($menus AS $menu) {
			$m = array();
			$m = $menu->toArray($menuFieldsNames);
			$menusToView[] = array_map('htmlspecialchars', $m);
		}		
		
		//przygotowanie danych, jesli wystapily bledy
		if(is_array($values)) {
			foreach($values AS $index=>$value) {
				if(is_array($value)) {
					$entryToView[$index] = array_map('htmlspecialchars', $value);
				}
				else {
					$entryToView[$index] = htmlspecialchars($value);
				}
			}		
		}		
		
		//przygotowanie dodatkowych danych dla widoku
		$actionLink = $baseUrl.'/admin/entry/edit';
		$entryTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $entryFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('entry', $entryToView);
		$this->_view->assign('menus', $menusToView);		
		$this->_view->assign('errors', $errors);
		$this->_view->assign('flashError', $flashError);
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('entryTableHeadersStrings', $entryTableHeadersStrings);
		$this->_view->assign('menusString', $this->_i18n->translate($entryRelationsNames['menus']));
		$this->_view->assign('entryEditFormTitle', $this->_i18n->translate('Entry edit form'));
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
		
	}
	
}

?>
