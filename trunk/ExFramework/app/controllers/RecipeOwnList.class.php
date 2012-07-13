<?php

class RecipeOwnList extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_recipeMapper = null;
	protected $_categoryMapper = null;
	protected $_viewResolver = null;
	protected $_paginator = null;
	
	protected $_templateName = 'recipeOwnList';
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
		$currentPageNumber = $this->_request->get('page');		
		
		$userLogin = null;
		$userIsAuth = false;
		$userLogin = $this->_session->userLogin;
		$userIsAuth = $this->_session->userIsAuth;
		if(empty($userLogin) || ($userIsAuth == false)) {
			throw new ControllerException('Brak dostepu');
		}		
		
		$userId = null;
		if(isset($this->_session->userData) && isset($this->_session->userData->id) && ($this->_session->userData->id != '')) {
			$userId = $this->_session->userData->id;
		}
		if(($userId == '') || ($userId == null)) {
			throw new ControllerException('Brak identyfikatora uzytkownika profilu');
		}
		
		//pobieranie danych z modelu
		$recipeFieldsNames = $this->_recipeMapper->getFieldsNames();
		$recipeRelationsNames = $this->_recipeMapper->getRelationsNames();
		
		$countAll = $this->_recipeMapper->countAllByUser($userId);
		$this->_paginator->setLink($baseUrl.'/recipe/ownList/');
		$this->_paginator->setNumberOfRecords($countAll);
		$this->_paginator->setCurrentPageNumber($currentPageNumber); 
		$recipes = $this->_recipeMapper->getAllRecipesByUser($userId, $this->_paginator->getLimit(), $this->_paginator->getOffset());		
		
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
					
			$recipesToView[] = $r;
		}

		//przygotowanie dodatkowych danych dla widoku
		$links = array('view'=>array($this->_i18n->translate('view'), $baseUrl.'/recipe/view/id/'), 'edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/recipe/editForm/id/'));
		$recipeTableHeadersNames = array_map(array($this->_i18n, 'translate'), $recipeFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('recipes', $recipesToView);
		$this->_view->assign('paginator', $this->_paginator->getPaginator());
		$this->_view->assign('flashNotice', $flashNotice);
		$this->_view->assign('links', $links);
		$this->_view->assign('recipeTableHeadersNames', $recipeTableHeadersNames);
		$this->_view->assign('categoriesString', $this->_i18n->translate($recipeRelationsNames['categories']));		
		$this->_view->assign('recipeListTitle', $this->_i18n->translate('User recipes list'));
		$this->_view->assign('noRecipeMessage', $this->_i18n->translate('No recipes in data base'));			
		
	}
			
}

?>
