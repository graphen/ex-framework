<?php

class RecipeEditForm extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_recipeMapper = null;
	protected $_categoryMapper = null;
	protected $_ingredientMapper = null;
	protected $_unitMapper = null;
	protected $_itemMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'recipeEditForm';
	protected $_layoutName = 'defaultLayout';
	
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
		$baseUrl = $this->_request->getBaseUrl();
		$values = $this->_session->getFlashData('recipeEditFormValues');
		$errors = $this->_session->getFlashData('recipeEditErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');				
		if(!is_array($values)) {
			$recipeId = (int)$this->_request->get('id');
		}
		else {
			$recipeId = (int)$values['id'];
		}
		if(($recipeId == '') || ($recipeId == null)) {
			throw new ControllerException('Brak identyfikatora przepisu');
		}
		
		//pobranie identyfikatora uzytkownika
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
		$categoryFieldsNames = $this->_categoryMapper->getFieldsNames();
		$ingredientFieldsNames = $this->_ingredientMapper->getFieldsNames();
		$unitFieldsNames = $this->_unitMapper->getFieldsNames();
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi przepisu, jego kategorii i skladnikow
		$recipeToView = array();
		if(!is_array($values)) {
			$recipesCollection = $this->_recipeMapper->getById($recipeId);
			if(count($recipesCollection) != 1) {
				throw new ControllerException('Przepis nie istnieje w bazie danych');
			}
			$recipeObject = $recipesCollection[0];
			
			//sprawdzenie czy uzytkownik ma prawo do edycji przepisu
			$recipeOwnerCollection = $recipeObject->users->getCollection();
			if(count($recipeOwnerCollection) == 0) {
				throw new ControllerException('Nie masz praw do edycji przepisu. Przepis uzytkownika anonimowego');
			}
			$recipeOwner = $recipeOwnerCollection[0];
			$recipeOwnerLogin = $recipeOwner->login;
			$recipeOwnerId = $recipeOwner->id;
			if(($recipeOwnerId != $userId) && ($recipeOwnerLogin != $userLogin)) {
				throw new ControllerException('Nie masz praw do edycji przepisu');
			}
			
			$recipeToView = $recipeObject->toArray($recipeFieldsNames);
			$recipeToView = array_map('htmlspecialchars', $recipeToView);
			
			$categoriesCollection = $recipeObject->categories->getCollection();
			$category = $categoriesCollection[0];
			$recipeToView['cats'] = htmlspecialchars($category->id);			
			$itemsCollection = $recipeObject->items->getCollection();
			$items = array();
			$i = 0;
			foreach($itemsCollection AS $item) {
				$items[$i]['amount'] = htmlspecialchars($item->amount);
				$ingredientsCollection = $item->ingredients->getCollection(); 
				$ingredient = $ingredientsCollection[0];
				$ingredientArr = $ingredient->toArray($ingredientFieldsNames);
				$items[$i]['ingredients'] = htmlspecialchars($ingredientArr['id']);
				$unitsCollection = $item->units->getCollection();
				if(count($unitsCollection) > 0) {
					$unit = $unitsCollection[0];
					$unitArr = $unit->toArray($unitFieldsNames); 
					$items[$i]['units'] = htmlspecialchars($unitArr['id']);
				}
				$i++;
			}
			$itemsCnt = count($items); //liczba skladnikow, chce aby bylo 15
			while($itemsCnt < 15) {
				$items[] = array();
				$itemsCnt++;
			}
			$recipeToView['itms'] = $items;			
			
		}
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi kategorii, skladnikow do przepisu i jednostek
		$categories = $this->_categoryMapper->getAll();
		$categoriesToView = array();
		foreach($categories AS $category) {
			$ca = array();
			$ca = $category->toArray($categoryFieldsNames);
			$ca = array_map('htmlspecialchars', $ca);
			$categoriesToView[] = $ca;
		}
		$ingredients = $this->_ingredientMapper->getAll();
		$ingredientsToView = array();
		foreach($ingredients AS $ingredient) {
			$in = array();
			$in = $ingredient->toArray($ingredientFieldsNames);
			$in = array_map('htmlspecialchars', $in);
			$ingredientsToView[] = $in;
		}				
		$units = $this->_unitMapper->getAll();
		$unitsToView = array();
		foreach($units AS $unit) {
			$un = array();
			$un = $unit->toArray($unitFieldsNames);
			$un = array_map('htmlspecialchars', $un);
			$unitsToView[] = $un;
		}					
		
		//przygotowanie danych formularza po wystapieniu bledu
		if(is_array($values)) {
			$items = array();
			if(isset($values['ingredients'])) {
				foreach($values['ingredients'] AS $index=>$ingredient) {
					$items[$index]['ingredients'] = htmlspecialchars($ingredient);
					if(isset($values['amount'][$index])) {
						$items[$index]['amount'] = htmlspecialchars($values['amount'][$index]); 
					}
					else {
						$items[$index]['amount'] = '';
					}
					if(isset($values['units'][$index])) {
						$items[$index]['units'] = htmlspecialchars($values['units'][$index]); 
					}
					else {
						$items[$index]['units'] = '';
					}
				}
				$itemsCnt = count($items); //liczba skladnikow, chce aby bylo 15
				while($itemsCnt < 15) {
					$items[] = array();
					$itemsCnt++;
				}				
			}
			unset($values['ingredients']);
			unset($values['amount']);
			unset($values['units']);
			
			foreach($values AS $index=>$value) {
				if(is_array($value)) {
					$recipeToView[$index]  = array_map('htmlspecialchars',$value);
				}
				$recipeToView[$index] = htmlspecialchars($value);
			}
			
			$recipeToView['itms'] = $items;
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$actionLink = $baseUrl.'/recipe/edit';
		$recipeTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $recipeFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('recipe', $recipeToView);
		$this->_view->assign('categories', $categoriesToView);
		$this->_view->assign('ingredients', $ingredientsToView);
		$this->_view->assign('units', $unitsToView);
		$this->_view->assign('errors', $errors);
		$this->_view->assign('flashError', $flashError);		
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('recipeTableHeadersStrings', $recipeTableHeadersStrings);
		$this->_view->assign('categoryString', $this->_i18n->translate($recipeRelationsNames['categories']));
		$this->_view->assign('ingredientsString', $this->_i18n->translate('ingredients'));
		$this->_view->assign('ingredientNameString', $this->_i18n->translate('Ingredient'));
		$this->_view->assign('unitNameString', $this->_i18n->translate('Unit'));
		$this->_view->assign('amountString', $this->_i18n->translate('Amount'));
		$this->_view->assign('recipeEditFormTitle', $this->_i18n->translate('Recipe edit form'));		
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
	
	}

}

?>
