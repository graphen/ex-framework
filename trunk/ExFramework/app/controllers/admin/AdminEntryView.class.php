<?php

class AdminEntryView extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_entryMapper = null;
	protected $_menuMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminEntryView';
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
		$entryId = (int)$this->_request->get('id');		
		if(($entryId == '') || ($entryId == null)) {
			throw new ControllerException('Brak identyfikatora wpisu menu');
		}		
		
		//pobieranie danych z modelu
		$entryFieldsNames = $this->_entryMapper->getFieldsNames();
		$entryRelationsNames = $this->_entryMapper->getRelationsNames();
		$menuFieldsNames = $this->_menuMapper->getFieldsNames();
				
		//pobieranie danych z modelu, przygotowanie tablicy z danymi wpisu menu
		$entriesCollection = $this->_entryMapper->getById($entryId);
		if(count($entriesCollection) != 1) {
			throw new ControllerException('Wpis menu nie istnieje w bazie danych');
		}
		$entryObject = $entriesCollection[0];
		$entryToView = $entryObject->toArray($entryFieldsNames);
		$menusCollection = $entryObject->menus->getCollection();
		$entryToView['menus'] = $menusCollection[0]->toArray($menuFieldsNames);
		
		//przygotowanie dodatkowych danych dla widoku
		$links = array('edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/entry/editForm/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/entry/delete/'));
		$entryTableHeadersNames = array_map(array($this->_i18n, 'translate'), $entryFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('entry', $entryToView);
		$this->_view->assign('links', $links);
		$this->_view->assign('entryTableHeadersNames', $entryTableHeadersNames);
		$this->_view->assign('menusString', $this->_i18n->translate($entryRelationsNames['menus']));		
		$this->_view->assign('entryViewTitle', $this->_i18n->translate('Entry view'));
		$this->_view->assign('noMenuMessage', $this->_i18n->translate('Entry has no menu'));
		
	}
	
}

?>
