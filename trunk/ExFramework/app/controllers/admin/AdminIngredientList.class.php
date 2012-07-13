<?php

class AdminIngredientList extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_ingredientMapper = null;
	protected $_viewResolver = null;
	protected $_paginator = null;
	
	protected $_templateName = 'adminIngredientList';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, IngredientMapper $ingredientMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_ingredientMapper = $ingredientMapper;
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
		$ingredientFieldsNames = $this->_ingredientMapper->getFieldsNames();
		//$ingredients = $this->_ingredientMapper->getAll();
		$countAll = $this->_ingredientMapper->countAll();
		$this->_paginator->setLink($baseUrl.'/admin/ingredient/list/'); 
		$this->_paginator->setNumberOfRecords($countAll);
		$this->_paginator->setCurrentPageNumber($currentPageNumber);
		$this->_paginator->setRecordsPerPage(40);		
		$ingredients = $this->_ingredientMapper->getAllIngredients($this->_paginator->getLimit(), $this->_paginator->getOffset());
				
		
		//przygotowanie tablicy z lista skladnikow
		$ingredientsToView = array();
		foreach($ingredients AS $ingredient) {
			$i = array();
			$i = $ingredient->toArray($ingredientFieldsNames);
			$ingredientsToView[] = $i;
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$links = array('view'=>array($this->_i18n->translate('view'), $baseUrl.'/admin/ingredient/view/id/'), 'edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/ingredient/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/ingredient/delete/id/'));
		$ingredientTableHeadersNames = array_map(array($this->_i18n, 'translate'), $ingredientFieldsNames);
				
		//dolaczenie danych do widoku
		$this->_view->assign('ingredients', $ingredientsToView);	
		$this->_view->assign('flashNotice', $flashNotice);
		$this->_view->assign('links', $links);
		$this->_view->assign('paginator', $this->_paginator->getPaginator());					
		$this->_view->assign('ingredientTableHeadersNames', $ingredientTableHeadersNames);
		$this->_view->assign('ingredientListTitle', $this->_i18n->translate('Ingredients list'));
		$this->_view->assign('noIngredientMessage', $this->_i18n->translate('No ingredient in data base'));			
		
	}
			
}

?>
