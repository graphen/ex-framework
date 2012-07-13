<?php

class AdminMenuView extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_entryMapper = null;
	protected $_menuMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminMenuView';
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
		$menuId = (int)$this->_request->get('id');		
		if(($menuId == '') || ($menuId == null)) {
			throw new ControllerException('Brak identyfikatora menu');
		}

		//pobieranie danych z modelu
		$menuFieldsNames = $this->_menuMapper->getFieldsNames();
		$menuRelationsNames = $this->_menuMapper->getRelationsNames();	
		$entryFieldsNames = $this->_entryMapper->getFieldsNames();
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi menu
		$menusCollection = $this->_menuMapper->getById($menuId);
		if(count($menusCollection) != 1) {
			throw new ControllerException('Menu nie istnieje w bazie danych');
		}
		$menuObject = $menusCollection[0];
		$menuToView = $menuObject->toArray($menuFieldsNames);
		$entriesCollection = $menuObject->entries->getCollection();
		$entriesToView = array();
		foreach($entriesCollection AS $entryObject) {
			$entry = array();
			$entry = $entryObject->toArray($entryFieldsNames);
			$entriesToView[] = $entry; 
		}
		$menuToView['entries'] = $entriesToView;
				
		//przygotowanie dodatkowych danych dla widoku
		$links = array('edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/menu/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/menu/delete/id/'));
		$menuTableHeadersNames = array_map(array($this->_i18n, 'translate'), $menuFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('menu', $menuToView);
		$this->_view->assign('links', $links);
		$this->_view->assign('menuTableHeadersNames', $menuTableHeadersNames);
		$this->_view->assign('entriesString', $this->_i18n->translate($menuRelationsNames['entries']));		
		$this->_view->assign('menuViewTitle', $this->_i18n->translate('Menu view'));
		$this->_view->assign('noEntryMessage', $this->_i18n->translate('Menu has no entry'));
		
	}
	
}

?>
