<?php

class RecipeQuickAddForm extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_recipeMapper = null;
	protected $_categoryMapper = null;
	protected $_ingredientMapper = null;
	protected $_unitMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'recipeQuickAddForm';
	protected $_layoutName = 'defaultLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, RecipeMapper $recipeMapper, CategoryMapper $categoryMapper, IngredientMapper $ingredientMapper, UnitMapper $unitMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_recipeMapper = $recipeMapper;
		$this->_categoryMapper = $categoryMapper;
		$this->_ingredientMapper = $ingredientMapper;
		$this->_unitMapper = $unitMapper;
		$this->_viewResolver = $viewResolver;
		$this->_view = $this->_viewResolver->resolve($this->_templateName, $this->_layoutName, true);
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();
		$values = $this->_session->getFlashData('recipeAddFormValues');
		$errors = $this->_session->getFlashData('recipeAddErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');
		
		//pobieranie danych z modelu
		$recipeFieldsNames = $this->_recipeMapper->getFieldsNames();
		$recipeRelationsNames = $this->_recipeMapper->getRelationsNames();
		$categoryFieldsNames = $this->_categoryMapper->getFieldsNames();
		$ingredientFieldsNames = $this->_ingredientMapper->getFieldsNames();
		$unitFieldsNames = $this->_unitMapper->getFieldsNames();
		$categories = $this->_categoryMapper->getAll();		
		$ingredients = $this->_ingredientMapper->getAll();
		$units = $this->_unitMapper->getAll();
		
		//przygotowanie tablicy z danymi kategorii przepisu
		$categoriesToView = array();
		foreach($categories AS $category) {
			$cat = array();
			$cat = $category->toArray($categoryFieldsNames);
			$cat = array_map('htmlspecialchars', $cat);
			$categoriesToView[] = $cat;
		}
		//przygotowanie tablicy z danymi skladnikow przepisu
		$ingredientsToView = array();
		foreach($ingredients AS $ingredient) {
			$in = array();
			$in = $ingredient->toArray($ingredientFieldsNames);
			$in = array_map('htmlspecialchars', $in);
			$ingredientsToView[] = $in;
		}
		//przygotowanie tablicy z danymi jednostek skladnikow
		$unitsToView = array();
		foreach($units AS $unit) {
			$un = array();
			$un = $unit->toArray($unitFieldsNames);
			$un = array_map('htmlspecialchars', $un);
			$unitsToView[] = $un;
		}	
		//przygotowanie tablicy z danymi przepisu
		$recipeToView = array();
		if(!is_array($values)) {
			$itemsCnt = 0; //liczba skladnikow, chce aby bylo 15, musze przygotowac tyle pustych tablic
			while($itemsCnt < 15) {
				$items[] = array();
				$itemsCnt++;
			}
			$recipeToView['itms'] = $items;
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
		$actionLink = $baseUrl.'/recipe/quickAdd';
		$recipeTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $recipeFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('recipe', $recipeToView);
		$this->_view->assign('categories', $categoriesToView);
		$this->_view->assign('ingredients', $ingredientsToView);
		$this->_view->assign('units', $unitsToView);		
		$this->_view->assign('categories', $categoriesToView);
		$this->_view->assign('errors', $errors);
		$this->_view->assign('flashError', $flashError);		
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('recipeTableHeadersStrings', $recipeTableHeadersStrings);
		$this->_view->assign('categoryString', $this->_i18n->translate($recipeRelationsNames['categories']));
		$this->_view->assign('ingredientsString', $this->_i18n->translate('ingredients'));
		$this->_view->assign('ingredientNameString', $this->_i18n->translate('Ingredient'));
		$this->_view->assign('unitNameString', $this->_i18n->translate('Unit'));
		$this->_view->assign('amountString', $this->_i18n->translate('Amount'));
		$this->_view->assign('recipeAddFormTitle', $this->_i18n->translate('Recipe add form'));		
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
				
	}
	
}

?>
