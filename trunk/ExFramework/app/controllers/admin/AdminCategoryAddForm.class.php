<?php

class AdminCategoryAddForm extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_categoryMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminCategoryAddForm';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, CategoryMapper $categoryMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_categoryMapper = $categoryMapper;
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
		$values = $this->_session->getFlashData('categoryAddFormValues');
		$errors = $this->_session->getFlashData('categoryAddErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');
		
		//pobieranie danych z modelu
		$categoryFieldsNames = $this->_categoryMapper->getFieldsNames();
		
		//przygotowanie tablicy z danymi kategorii w przypadku kiedy podczas wypelniania wystapily bledy
		$categoryToView = array();
		if(is_array($values)) {
			foreach($values AS $index=>$value) {
				$categoryToView[$index] = htmlspecialchars($value);
			}
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$actionLink = $baseUrl.'/admin/category/add';
		$categoryTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $categoryFieldsNames);
	
		//dolaczenie danych do widoku
		$this->_view->assign('category', $categoryToView);
		$this->_view->assign('errors', $errors);
		$this->_view->assign('flashError', $flashError);
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('categoryTableHeadersStrings', $categoryTableHeadersStrings);
		$this->_view->assign('categoryAddFormTitle', $this->_i18n->translate('Category add form'));		
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
			
	}
	
}

?>
