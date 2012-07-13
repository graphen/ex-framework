<?php

class AdminMenuEditForm extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_menuMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminMenuEditForm';
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
		$values = $this->_session->getFlashData('menuEditFormValues');
		$errors = $this->_session->getFlashData('menuEditErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');				
		if(!is_array($values)) {
			$menuId = (int)$this->_request->get('id');		
		}
		else {
			$menuId = (int)$values['id'];
		}
		if(($menuId == '') || ($menuId == null)) {
			throw new ControllerException('Brak identyfikatora menu');
		}
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi menu
		$menuFieldsNames = $this->_menuMapper->getFieldsNames();
		if(!is_array($values)) {
			$menuCollection = $this->_menuMapper->getById($menuId);
			if(count($menuCollection) != 1) {
				throw new ControllerException('Menu nie istnieje w bazie danych');
			}
			$menuObject = $menuCollection[0];
			$menuToView = $menuObject->toArray($menuFieldsNames);
			$menuToView = array_map('htmlspecialchars', $menuToView);
		}
		//przygotowanie wartosci formularza, jesli skrypt uruchamiany jesli wystapily bledy podczas wprowadzania danych w formularzu
		if(is_array($values)) {
			foreach($values AS $index=>$value) {
				$menuToView[$index] = htmlspecialchars($value);
			}
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$actionLink = $baseUrl.'/admin/menu/edit';
		$menuTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $menuFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('menu', $menuToView);
		$this->_view->assign('errors', $errors);
		$this->_view->assign('flashError', $flashError);
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('menuTableHeadersStrings', $menuTableHeadersStrings);
		$this->_view->assign('menuEditFormTitle', $this->_i18n->translate('Menu edit form'));
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
		
	}
	
}

?>
