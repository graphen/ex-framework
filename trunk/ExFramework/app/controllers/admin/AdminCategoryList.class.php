<?php

class AdminCategoryList extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_categoryMapper = null;
	protected $_viewResolver = null;
	protected $_paginator = null;
	
	protected $_templateName = 'adminCategoryList';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, CategoryMapper $categoryMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_categoryMapper = $categoryMapper;
		$this->_viewResolver = $viewResolver;
		$this->_paginator = $paginator;
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
		$currentPageNumber = $this->_request->get('page');
				
		//pobieranie danych z modelu
		$categoryFieldsNames = $this->_categoryMapper->getFieldsNames();
		$countAll = $this->_categoryMapper->countAll();
		$this->_paginator->setLink($baseUrl.'/admin/category/list/'); 
		$this->_paginator->setNumberOfRecords($countAll);
		$this->_paginator->setCurrentPageNumber($currentPageNumber); 
		$categories = $this->_categoryMapper->getAllCategories($this->_paginator->getLimit(), $this->_paginator->getOffset());
		
		//przygotowanie tablicy z lista kategorii
		$categoriesToView = array();
		foreach($categories AS $category) {
			$c = array();
			$c = $category->toArray($categoryFieldsNames);
			$categoriesToView[] = $c;
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$links = array('view'=>array($this->_i18n->translate('view'), $baseUrl.'/admin/category/view/id/'), 'edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/category/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/category/delete/id/'));
		$categoryTableHeadersNames = array_map(array($this->_i18n, 'translate'), $categoryFieldsNames);
				
		//dolaczenie danych do widoku
		$this->_view->assign('categories', $categoriesToView);
		$this->_view->assign('paginator', $this->_paginator->getPaginator());			
		$this->_view->assign('flashNotice', $flashNotice);
		$this->_view->assign('links', $links);
		$this->_view->assign('categoryTableHeadersNames', $categoryTableHeadersNames);
		$this->_view->assign('categoryListTitle', $this->_i18n->translate('Categories list'));
		$this->_view->assign('noCategoryMessage', $this->_i18n->translate('No category in data base'));			
		
	}
			
}

?>
