<?php

class AdminCategoryView extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_recipeMapper = null;
	protected $_categoryMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminCategoryView';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, RecipeMapper $recipeMapper, CategoryMapper $categoryMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_recipeMapper = $recipeMapper;
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
		$categoryId = (int)$this->_request->get('id');		
		if(($categoryId == '') || ($categoryId == null)) {
			throw new ControllerException('Brak identyfikatora kategorii');
		}
		
		//pobieranie danych z modelu
		$categoryFieldsNames = $this->_categoryMapper->getFieldsNames();
		$categoryRelationsNames = $this->_categoryMapper->getRelationsNames();	
		$recipeFieldsNames = $this->_recipeMapper->getFieldsNames();
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi kategorii
		$categoriesCollection = $this->_categoryMapper->getById($categoryId);
		if(count($categoriesCollection) != 1) {
			throw new ControllerException('Kategoria nie istnieje w bazie danych');
		}
		$categoryObject = $categoriesCollection[0];
		$categoryToView = $categoryObject->toArray($categoryFieldsNames);
		$recipesCollection = $categoryObject->recipes->getCollection();
		$recipesToView = array();
		foreach($recipesCollection AS $recipeObject) {
			$recipe = array();
			$recipe = $recipeObject->toArray($recipeFieldsNames);
			$recipesToView[] = $recipe; 
		}
		$categoryToView['recipes'] = $recipesToView;
				
		//przygotowanie dodatkowych danych dla widoku
		$links = array('edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/category/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/category/delete/id/'));
		$categoryTableHeadersNames = array_map(array($this->_i18n, 'translate'), $categoryFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('category', $categoryToView);
		$this->_view->assign('links', $links);
		$this->_view->assign('categoryTableHeadersNames', $categoryTableHeadersNames);
		$this->_view->assign('recipesString', $this->_i18n->translate($categoryRelationsNames['recipes']));		
		$this->_view->assign('categoryViewTitle', $this->_i18n->translate('Category view'));
		$this->_view->assign('noRecipeMessage', $this->_i18n->translate('Category has no recipe'));
		
	}
	
}

?>
