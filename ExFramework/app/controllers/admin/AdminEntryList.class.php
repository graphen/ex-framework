<?php

class AdminEntryList extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_entryMapper = null;
	protected $_menuMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminEntryList';
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
		$flashNotice = $this->_session->getFlashData('notice');
		
		//pobieranie danych z modelu
		$entryFieldsNames = $this->_entryMapper->getFieldsNames();
		$entryRelationsNames = $this->_entryMapper->getRelationsNames();
		$menuFieldsNames = $this->_menuMapper->getFieldsNames();
		$menus = $this->_menuMapper->getAll();
		
		//przygotowanie tablicy z lista menu i wpisow
		$menusToView = array();
		foreach($menus AS $menu) {
			$m = array();
			$m = $menu->toArray($menuFieldsNames);
			$entries = $menu->entries->getCollection();
			$entriesToView = array();
			foreach($entries AS $entry) {
				$e = array();
				$e = $entry->toArray($entryFieldsNames);
				$entriesToView[] = $e;
			}
			$m['entries'] = $entriesToView;
			$menusToView[] = $m;
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$links = array('view'=>array($this->_i18n->translate('view'), $baseUrl.'/admin/entry/view/id/'), 'edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/entry/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/entry/delete/id/'));
		$entryTableHeadersNames = array_map(array($this->_i18n, 'translate'), $entryFieldsNames);
		$menuTableHeadersNames = array_map(array($this->_i18n, 'translate'), $menuFieldsNames);
				
		//dolaczenie danych do widoku
		$this->_view->assign('menus', $menusToView);
		$this->_view->assign('flashNotice', $flashNotice);
		$this->_view->assign('links', $links);
		$this->_view->assign('entryTableHeadersNames', $entryTableHeadersNames);
		$this->_view->assign('menuTableHeadersNames', $menuTableHeadersNames);
		$this->_view->assign('menusString', $this->_i18n->translate($entryRelationsNames['menus']));		
		$this->_view->assign('entryListTitle', $this->_i18n->translate('Entries list'));
		$this->_view->assign('noEntryMessage', $this->_i18n->translate('No entries in data base'));		
		$this->_view->assign('noMenuMessage', $this->_i18n->translate('No menus in data base'));	
						
	}
			
}

?>
