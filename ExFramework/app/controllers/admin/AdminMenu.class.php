<?php

class AdminMenu extends ControllerActionAbstract implements IController {
	
	protected $_request = null;	
	protected $_session = null;
	protected $_menuMapper = null;
	protected $_entryMapper = null;
	protected $_viewResolver = null;
	protected $_i18n = null;
	
	protected $_templateName = 'adminMenu';
	
	public function __construct(IRequest $request, ISession $session, MenuMapper $menuMapper, EntryMapper $entryMapper, IViewResolver $viewResolver, II18n $i18n) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_menuMapper = $menuMapper;
		$this->_entryMapper = $entryMapper;
		$this->_viewResolver = $viewResolver;
		$this->_i18n = $i18n;
		$this->_view = $this->_viewResolver->resolve($this->_templateName, $this->_layoutName, true);
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$userLogin = null;
		$userIsAuth = false;
		$userLogin = $this->_session->userLogin;
		$userIsAuth = $this->_session->userIsAuth;
		if(empty($userLogin) || ($userIsAuth == false)) {
			//throw new ControllerException('Brak dostepu');
		}			
		
		$baseUrl = $this->_request->getBaseUrl();
				
		//pobieranie danych z modelu
		$menusCollection = $this->_menuMapper->getById(1); //Pod id 1 jest menu panelu adm.
		if(count($menusCollection) != 1) {
			$this->_view->assign('menuEntries', '');
		}
		else {
			$menu = $menusCollection[0];
			//przygotowanie tablicy z lista wpisow menu administracyjnego
			$entriesToView = array();
			$entries = $menu->entries->getCollection();
			foreach($entries AS $entry) {
				$entriesToView[] = array('title'=>$entry->title, 'url'=>$entry->url);
			}
					
			//dolaczenie danych do widoku
			$this->_view->assign('adminMenuEntries', $entriesToView);
		}
		$this->_view->assign('adminMenuTitle', $this->_i18n->translate('Admin Menu'));
	}
			
}

?>
