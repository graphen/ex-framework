<?php

class AdminRecipeView extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_recipeMapper = null;
	protected $_categoryMapper = null;
	protected $_ingredientMapper = null;
	protected $_unitMapper = null;
	protected $_itemMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminRecipeView';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, RecipeMapper $recipeMapper, CategoryMapper $categoryMapper, IngredientMapper $ingredientMapper, UnitMapper $unitMapper, ItemMapper $itemMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_recipeMapper = $recipeMapper;
		$this->_categoryMapper = $categoryMapper;
		$this->_ingredientMapper = $ingredientMapper;
		$this->_unitMapper = $unitMapper;
		$this->_itemMapper = $itemMapper;
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
		$recipeId = (int)$this->_request->get('id');		
		if(($recipeId == '') || ($recipeId == null)) {
			throw new ControllerException('Brak identyfikatora przepisu');
		}
		
		//pobieranie danych z modelu
		$recipeFieldsNames = $this->_recipeMapper->getFieldsNames();
		$recipeRelationsNames = $this->_recipeMapper->getRelationsNames();
		$itemFieldsNames = $this->_itemMapper->getFieldsNames();
		$itemRelationsNames = $this->_itemMapper->getRelationsNames();
		$ingredientFieldsNames = $this->_ingredientMapper->getFieldsNames();
		$unitFieldsNames = $this->_unitMapper->getFieldsNames();
		$categoryFieldsNames = $this->_categoryMapper->getFieldsNames();
				
		//pobieranie danych z modelu, przygotowanie tablicy z danymi przepisu
		$recipesCollection = $this->_recipeMapper->getById($recipeId);
		if(count($recipesCollection) != 1) {
			throw new ControllerException('Przepis nie istnieje w bazie danych');
		}
		$recipeObject = $recipesCollection[0];
		$recipeToView = $recipeObject->toArray($recipeFieldsNames);
		$categoriesCollection = $recipeObject->categories->getCollection();
		$category = $categoriesCollection[0];
		$categoryArr = $category->toArray(array('name'));
		$recipeToView['category'] = $categoryArr['name'];
		if($recipeToView['preparationTime'] > 60) {
			$tmpH = floor($recipeToView['preparationTime']/60);
			$hPart = ($tmpH != 0) ? $tmpH . $this->_i18n->translate('h') : '';
			$mPart = $recipeToView['preparationTime']%60 . $this->_i18n->translate('m');
			$recipeToView['preparationTime'] = $hPart . $mPart;
		}
		else {
			$recipeToView['preparationTime'] = $recipeToView['preparationTime'] . $this->_i18n->translate('m');
		}		
		$itemsCollection = $recipeObject->items->getCollection();
		$itemsToView = array();
		foreach($itemsCollection AS $itemObject) {
			$item = array();
			$amountArr = $itemObject->toArray(array('amount'=>'amount'));
			$item['amount'] = $amountArr['amount'];
			$ingredientsCollection = $itemObject->ingredients->getCollection();
			$ingredient = $ingredientsCollection[0];
			$ingredientArr = $ingredient->toArray(array('name'=>'name'));
			$item['ingredient'] = $ingredientArr['name'];
			$unitsCollection = $itemObject->units->getCollection();
			if(count($unitsCollection) > 0) {
				$unit = $unitsCollection[0];
				$unitArr = $unit->toArray(array('abbreviation'=>'abbreviation'));
				$item['unit'] = $unitArr['abbreviation'];
			}
			$itemsToView[] = $item; 
			
		}
		$recipeToView['items'] = $itemsToView;
		
		//przygotowanie dodatkowych danych dla widoku
		$links = array('edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/recipe/editForm/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/recipe/delete/'));
		$recipeTableHeadersNames = array_map(array($this->_i18n, 'translate'), $recipeFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('recipe', $recipeToView);
		$this->_view->assign('links', $links);
		$this->_view->assign('recipeTableHeadersNames', $recipeTableHeadersNames);
		$this->_view->assign('ingredientsString', $this->_i18n->translate($itemRelationsNames['ingredients']));
		$this->_view->assign('categoriesString', $this->_i18n->translate($recipeRelationsNames['categories']));		
		$this->_view->assign('recipeViewTitle', $this->_i18n->translate('Recipe view'));
		
	}
	
}

?>
