<?php

class AdminCategoryEditForm extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_categoryMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminCategoryEditForm';
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
		$values = $this->_session->getFlashData('categoryEditFormValues');
		$errors = $this->_session->getFlashData('categoryEditErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');				
		if(!is_array($values)) {
			$categoryId = (int)$this->_request->get('id');		
		}
		else {
			$categoryId = (int)$values['id'];
		}
		if(($categoryId == '') || ($categoryId == null)) {
			throw new ControllerException('Brak identyfikatora kategorii');
		}
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi kategorii
		$categoryFieldsNames = $this->_categoryMapper->getFieldsNames();
		if(!is_array($values)) {
			$categoriesCollection = $this->_categoryMapper->getById($categoryId);
			if(count($categoriesCollection) != 1) {
				throw new ControllerException('Kategoria nie istnieje w bazie danych');
			}
			$categoryObject = $categoriesCollection[0];
			$categoryToView = $categoryObject->toArray($categoryFieldsNames);
			$categoryToView = array_map('htmlspecialchars', $categoryToView);
		}
		
		//przygotowanie wartosci formularza, jesli skrypt uruchamiany jesli wystapily bledy podczas wprowadzania danych w formularzu
		if(is_array($values)) {
			foreach($values AS $index=>$value) {
				$categoryToView[$index] = htmlspecialchars($value);
			}
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$actionLink = $baseUrl.'/admin/category/edit';
		$categoryTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $categoryFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('category', $categoryToView);
		$this->_view->assign('errors', $errors);
		$this->_view->assign('flashError', $flashError);
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('categoryTableHeadersStrings', $categoryTableHeadersStrings);
		$this->_view->assign('categoryEditFormTitle', $this->_i18n->translate('Category edit form'));
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
		
	}
	
}

?>
