<?php

class AdminEntryAddForm extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_entryMapper = null;
	protected $_menuMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminEntryAddForm';
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
		
		//pobieranie danych z zadania i sesji
		$userLogin = null;
		$userIsAuth = false;
		$userLogin = $this->_session->userLogin;
		$userIsAuth = $this->_session->userIsAuth;
		if(empty($userLogin) || ($userIsAuth == false)) {
			throw new ControllerException('Brak dostepu');
		}			
		
		$baseUrl = $this->_request->getBaseUrl();
		$values = $this->_session->getFlashData('entryAddFormValues');
		$errors = $this->_session->getFlashData('entryAddErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');
		
		//pobieranie danych z modelu
		$entryFieldsNames = $this->_entryMapper->getFieldsNames();
		$entryRelationsNames = $this->_entryMapper->getRelationsNames();
		$menuFieldsNames = $this->_menuMapper->getFieldsNames();
		$menus = $this->_menuMapper->getAll();
		
		//przygotowanie tablicy z danymi menu
		$menusToView = array();
		foreach($menus AS $menu) {
			$m = array();
			$m = $menu->toArray($menuFieldsNames);
			$m = array_map('htmlspecialchars', $m);
			$menusToView[] = $m;
		}
		
		//przygotowanie tablicy z danymi wpisu menu i jego menu w przypadku kiedy podczas wypelniania wystapily bledy
		$entryToView = array(); 
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
		$actionLink = $baseUrl.'/admin/entry/add';
		$entryTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $entryFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('entry', $entryToView);
		$this->_view->assign('menus', $menusToView);
		$this->_view->assign('errors', $errors);
		$this->_view->assign('flashError', $flashError);		
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('entryTableHeadersStrings', $entryTableHeadersStrings);
		$this->_view->assign('menusString', $this->_i18n->translate($entryRelationsNames['menus']));
		$this->_view->assign('entryAddFormTitle', $this->_i18n->translate('Entry add form'));		
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
				
	}
	
}

?>
