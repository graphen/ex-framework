<?php

class AdminCategoryDelete extends AdminBaseControllerActionAbstract implements IController {
	
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
		$categoryId = (int)$this->_request->get('id');	
		if(($categoryId == '') || $categoryId == null) {
			throw new ControllerException('Brak identyfikatora kategorii');
		}
		
		//pobieranie danych z modelu, kasowanie danych o kategorii
		$categoriesCollection = $this->_categoryMapper->getById($categoryId);
		if(count($categoriesCollection) != 1) {
			throw new ControllerException('Kategoria nie istnieje w bazie danych');
		}
		$category = $categoriesCollection[0];
		$result = $this->_categoryMapper->delete($category);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$this->_session->setFlashData('notice', $this->_i18n->translate('Category has been deleted from database'));
		$this->redirect($baseUrl.'/admin/category/list');
		
	}
	
}

?>
