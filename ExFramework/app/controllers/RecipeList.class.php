<?php

class RecipeList extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_recipeMapper = null;
	protected $_categoryMapper = null;
	protected $_viewResolver = null;
	protected $_paginator = null;
	
	protected $_templateName = 'recipeList';
	protected $_layoutName = 'defaultLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, RecipeMapper $recipeMapper, CategoryMapper $categoryMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_recipeMapper = $recipeMapper;
		$this->_categoryMapper = $categoryMapper;
		$this->_viewResolver = $viewResolver;
		$this->_paginator = $paginator;
		$this->_view = $this->_viewResolver->resolve($this->_templateName, $this->_layoutName, true);
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();
		$flashNotice = $this->_session->getFlashData('notice');
		$cId = $this->_request->get('cId');
		$uId = $this->_request->get('uId');
		$searchQuery = $this->_request->get('q');
		$currentPageNumber = $this->_request->get('page');
		
		//pobieranie danych z modelu
		$recipeFieldsNames = $this->_recipeMapper->getFieldsNames();
		$recipeRelationsNames = $this->_recipeMapper->getRelationsNames();
		
		if(($searchQuery != null) && ($searchQuery != '')) {
			$searchKeys = urldecode($searchQuery);
			$searchKeysArr = explode(' ', $searchKeys);
			$countAll = $this->_recipeMapper->countAllApprovedSearched($searchKeysArr, $cId, $uId);
			$cIdStr = '';
			$uIdStr = '';
			if($cId !== null) {
				$cIdStr = '/cId/'.$cId;
			}
			if($uId !== null) {
				$uIdStr = '/uId/'.$uId;
			}
			$this->_paginator->setLink($baseUrl.'/recipe/list/q/'.$searchQuery.$cIdStr.$uIdStr); 
			$this->_paginator->setNumberOfRecords($countAll);
			$this->_paginator->setCurrentPageNumber($currentPageNumber);			
			$recipes = $this->_recipeMapper->searchApprovedRecipes($searchKeysArr, $cId, $uId, $this->_paginator->getLimit(), $this->_paginator->getOffset());
			$this->_view->assign('paginator', $this->_paginator->getPaginator());
		}
		else {
			if(($cId == null) && ($uId == null)) {
				$countAll = $this->_recipeMapper->countAllApproved();
				$this->_paginator->setLink($baseUrl.'/recipe/list/'); 
				$this->_paginator->setNumberOfRecords($countAll);
				$this->_paginator->setCurrentPageNumber($currentPageNumber); 
				$recipes = $this->_recipeMapper->getAllApprovedRecipes($this->_paginator->getLimit(), $this->_paginator->getOffset());
				$this->_view->assign('paginator', $this->_paginator->getPaginator());
			}
			elseif(($cId != null) && ($uId == null)) {
				$countAll = $this->_recipeMapper->countAllApprovedByCategory($cId);
				$this->_paginator->setLink($baseUrl.'/recipe/list/cId/'.$cId); 
				$this->_paginator->setNumberOfRecords($countAll);
				$this->_paginator->setCurrentPageNumber($currentPageNumber);
				$recipes = $this->_recipeMapper->getAllApprovedRecipesByCategory($cId, $this->_paginator->getLimit(), $this->_paginator->getOffset());
				$this->_view->assign('paginator', $this->_paginator->getPaginator());				
			}
			elseif(($cId == null) && ($uId != null)) {
				$countAll = $this->_recipeMapper->countAllApprovedByUser($uId);
				$this->_paginator->setLink($baseUrl.'/recipe/list/uId/'.$uId); 
				$this->_paginator->setNumberOfRecords($countAll);
				$this->_paginator->setCurrentPageNumber($currentPageNumber);
				$recipes = $this->_recipeMapper->getAllApprovedRecipesByUser($uId, $this->_paginator->getLimit(), $this->_paginator->getOffset());
				$this->_view->assign('paginator', $this->_paginator->getPaginator());				
			}
			elseif(($cId != null) && ($uId != null)) {
				$countAll = $this->_recipeMapper->countAllApprovedByUserAndCategory($uId, $cId);
				$this->_paginator->setLink($baseUrl.'/recipe/list/uId/'.$uId.'/cId/'.$cId); 
				$this->_paginator->setNumberOfRecords($countAll);
				$this->_paginator->setCurrentPageNumber($currentPageNumber);
				$recipes = $this->_recipeMapper->getAllApprovedRecipesByUserAndCategory($uId, $cId, $this->_paginator->getLimit(), $this->_paginator->getOffset());
				$this->_view->assign('paginator', $this->_paginator->getPaginator());					
			}		
		}
		
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
		$links = array('view'=>array($this->_i18n->translate('view'), $baseUrl.'/recipe/view/id/'));
		$recipeTableHeadersNames = array_map(array($this->_i18n, 'translate'), $recipeFieldsNames);
				
		//dolaczenie danych do widoku
		$this->_view->assign('recipes', $recipesToView);
		$this->_view->assign('flashNotice', $flashNotice);
		$this->_view->assign('links', $links);
		$this->_view->assign('recipeTableHeadersNames', $recipeTableHeadersNames);
		$this->_view->assign('categoriesString', $this->_i18n->translate($recipeRelationsNames['categories']));		
		$this->_view->assign('recipeListTitle', $this->_i18n->translate('Recipes list'));
		$this->_view->assign('noRecipeMessage', $this->_i18n->translate('No recipes in data base'));			
		
	}
			
}

?>
