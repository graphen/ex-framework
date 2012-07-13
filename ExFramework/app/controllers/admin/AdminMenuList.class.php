<?php

class AdminMenuList extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_menuMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminMenuList';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, MenuMapper $menuMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
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
		$menuFieldsNames = $this->_menuMapper->getFieldsNames();
		$menus = $this->_menuMapper->getAll();
		
		//przygotowanie tablicy z lista menu
		$menusToView = array();
		foreach($menus AS $menu) {
			$g = array();
			$g = $menu->toArray($menuFieldsNames);
			$menusToView[] = $g;
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$links = array('view'=>array($this->_i18n->translate('view'), $baseUrl.'/admin/menu/view/id/'), 'edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/menu/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/menu/delete/id/'));
		$menuTableHeadersNames = array_map(array($this->_i18n, 'translate'), $menuFieldsNames);
				
		//dolaczenie danych do widoku
		$this->_view->assign('menus', $menusToView);	
		$this->_view->assign('flashNotice', $flashNotice);
		$this->_view->assign('links', $links);
		$this->_view->assign('menuTableHeadersNames', $menuTableHeadersNames);
		$this->_view->assign('menuListTitle', $this->_i18n->translate('Menus list'));
		$this->_view->assign('noMenuMessage', $this->_i18n->translate('No menus in data base'));			
		
	}
			
}

?>
