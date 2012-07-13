<?php

class AdminCategoryEdit extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_categoryMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, CategoryMapper $categoryMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_categoryMapper = $categoryMapper;
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
		$postValues = $this->_request->post();
		$categoryId = (int)$postValues['id'];
		if(($categoryId == '') || ($categoryId == null)) {
			throw new ControllerException('Brak identyfikatora kategorii');
		}
		
		//pobieranie danych z modelu		
		$categoryRelationsNames = $this->_categoryMapper->getRelationsNames();
		$categoriesCollection = $this->_categoryMapper->getById($categoryId);
		if(count($categoriesCollection) != 1) {
			throw new ControllerException('Kategoria nie istnieje w bazie danych');
		}
		$category = $categoriesCollection[0];
		
		//wypelnienie obiektu kategorii, danymi z formularza
		foreach($postValues AS $index=>$value) {
			$category->{$index} = $value;
		}
		
		//zapisywanie danych o kategorii
		$result = $this->_categoryMapper->save($category);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_categoryMapper->hasErrors()) {
			$errors = $this->_categoryMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Category has not been updated in data base'));
			$this->_session->setFlashData('categoryEditFormValues', $postValues);
			$this->_session->setFlashData('categoryEditErrors', $errors);
			$this->redirect($baseUrl.'/admin/category/editForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('Category has been updated in data base'));
			$this->redirect($baseUrl.'/admin/category/list');
		}
	}
	
}

?>
