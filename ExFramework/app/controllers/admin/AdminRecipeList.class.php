<?php

class AdminRecipeList extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_recipeMapper = null;
	protected $_categoryMapper = null;
	protected $_userMapper = null;
	protected $_viewResolver = null;
	protected $_paginator = null;	
	
	protected $_templateName = 'adminRecipeList';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, RecipeMapper $recipeMapper, CategoryMapper $categoryMapper, UserMapper $userMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_recipeMapper = $recipeMapper;
		$this->_categoryMapper = $categoryMapper;
		$this->_userMapper = $userMapper;
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
		$recipeFieldsNames = $this->_recipeMapper->getFieldsNames();
		$recipeRelationsNames = $this->_recipeMapper->getRelationsNames();
		
		$countAll = $this->_recipeMapper->countAll();
		$this->_paginator->setLink($baseUrl.'/admin/recipe/list/'); 
		$this->_paginator->setNumberOfRecords($countAll);
		$this->_paginator->setCurrentPageNumber($currentPageNumber); 
		$recipes = $this->_recipeMapper->getAllRecipes($this->_paginator->getLimit(), $this->_paginator->getOffset());
		
		//przygotowanie tablicy z lista przepisow
		$recipesToView = array();
		foreach($recipes AS $recipe) {
			$r = array();
			$r = $recipe->toArray($recipeFieldsNames);
			if($r['preparationTime'] > 60) {
				$tmpH = floor($r['preparationTime']/60);
				$hPart = ($tmpH != 0) ? $tmpH . $this->_i18n->translate('h') : '';
				$mPart = $r['preparationTime']%60 . $this->_i18n->translate('m');
				$r['preparationTime'] = $hPart . $mPart;
			}
			else {
				$r['preparationTime'] = $r['preparationTime'] . $this->_i18n->translate('m');
			}
			$categoriesCollection = $recipe->categories->getCollection();
			$category = $categoriesCollection[0];
			$categoryArr = $category->toArray(array('name'=>'name'));
			$r['category'] = $categoryArr['name'];			

			$usersCollection = $recipe->users->getCollection();
			if(count($usersCollection) > 0) {
				$user = $usersCollection[0];
				$userArr = $user->toArray(array('login'=>'login'));
				$r['userName'] = $userArr['login'];
			}
			$recipesToView[] = $r;
		}

		//przygotowanie dodatkowych danych dla widoku
		$links = array('view'=>array($this->_i18n->translate('view'), $baseUrl.'/admin/recipe/view/id/'), 'edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/recipe/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/recipe/delete/id/'));
		$recipeTableHeadersNames = array_map(array($this->_i18n, 'translate'), $recipeFieldsNames);
				
		//dolaczenie danych do widoku
		$this->_view->assign('recipes', $recipesToView);
		$this->_view->assign('paginator', $this->_paginator->getPaginator());
		$this->_view->assign('flashNotice', $flashNotice);
		$this->_view->assign('links', $links);
		$this->_view->assign('recipeTableHeadersNames', $recipeTableHeadersNames);
		$this->_view->assign('categoriesString', $this->_i18n->translate($recipeRelationsNames['categories']));		
		$this->_view->assign('recipeListTitle', $this->_i18n->translate('Recipes list'));
		$this->_view->assign('noRecipeMessage', $this->_i18n->translate('No recipes in data base'));			
		
	}
			
}

?>
