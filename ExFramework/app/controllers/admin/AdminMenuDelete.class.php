<?php

class AdminMenuDelete extends AdminBaseControllerActionAbstract implements IController {
	
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
		$menuId = (int)$this->_request->get('id');	
		if(($menuId == '') || $menuId == null) {
			throw new ControllerException('Brak identyfikatora menu');
		}
		
		//pobieranie danych z modelu, kasowanie danych o menu
		$menuFieldsNames = $this->_menuMapper->getFieldsNames();
		$menusCollection = $this->_menuMapper->getById($menuId);
		if(count($menusCollection) != 1) {
			throw new ControllerException('Menu nie istnieje w bazie danych');
		}
		$menu = $menusCollection[0];
		$result = $this->_menuMapper->delete($menu);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$this->_session->setFlashData('notice', $this->_i18n->translate('Menu has been deleted from database'));
		$this->redirect($baseUrl.'/admin/menu/list');
		
	}
	
}

?>
