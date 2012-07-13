<?php

class AdminCategoryAdd extends AdminBaseControllerActionAbstract implements IController {
	
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
		
		//pobieranie danych z modelu, zapisywanie danych o kategorii		
		$category = $this->_categoryMapper->create($postValues);
		$result = $this->_categoryMapper->save($category);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_categoryMapper->hasErrors()) {
			$errors = $this->_categoryMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Category has not been added to data base'));
			$this->_session->setFlashData('categoryAddFormValues', $postValues);
			$this->_session->setFlashData('categoryAddErrors', $errors);
			$this->redirect($baseUrl.'/admin/category/addForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('Category has been added to data base'));
			$this->redirect($baseUrl.'/admin/category/list');
		}
		
	}
	
}

?>
