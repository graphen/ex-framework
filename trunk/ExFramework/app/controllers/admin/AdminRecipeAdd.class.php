<?php

class AdminRecipeAdd extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_recipeMapper = null;
	protected $_categoryMapper = null;
	protected $_ingredientMapper = null;
	protected $_unitMapper = null;
	protected $_itemMapper = null;
	protected $_userMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, RecipeMapper $recipeMapper, CategoryMapper $categoryMapper, IngredientMapper $ingredientMapper, UnitMapper $unitMapper, ItemMapper $itemMapper, UserMapper $userMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_recipeMapper = $recipeMapper;
		$this->_categoryMapper = $categoryMapper;
		$this->_ingredientMapper = $ingredientMapper;
		$this->_unitMapper = $unitMapper;
		$this->_itemMapper = $itemMapper;
		$this->_userMapper = $userMapper;
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
		$postValues = $this->_request->post();
		
		//utworzenie obiektu Recipe i wstepne sprawdzenie danych z formularza dla tego obiektu
		$postValues['approved'] = 1; //auto approved
		$recipe = $this->_recipeMapper->create($postValues);
		$this->_recipeMapper->validate($postValues, 'insert');
		
		//jesli wystapily bledy przekierowanie do strony formularza
		$errors = array();
		if($this->_recipeMapper->hasErrors()) {
			$errors = $this->_recipeMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Recipe has not been added to data base'));
			$this->_session->setFlashData('recipeAddFormValues', $postValues);
			$this->_session->setFlashData('recipeAddErrors', $errors);
			$this->redirect($baseUrl.'/admin/recipe/addForm');
		}
		//przygotowanie tablicy obiektow items i units na podstawie danych przeslanych w formularzu
		$items = array();
		foreach($postValues['ingredients'] AS $index=>$ingredientId) {
			if($ingredientId == '') {
				continue;
			}
			$ingredientsCollection = $this->_ingredientMapper->getById($ingredientId);
			if(count($ingredientsCollection) != 1) {
				throw new ControllerException('Skladnik o podanym identyfikatorze nie istnieje w bazie danych');
			}
			$items[$index]['ingredient'] = $ingredientsCollection[0];
			$items[$index]['unit'] = null;
			$item = $this->_itemMapper->create();
			if($postValues['amount'][$index] != '') {
				$item->amount = $postValues['amount'][$index];
				$this->_itemMapper->validate(array('amount'=>$postValues['amount'][$index]), 'insert');
				if($this->_itemMapper->hasErrors()) {
					$errors = $this->_itemMapper->getErrors(); //tutaj podpiac i18n!!!
					$this->_session->setFlashData('error', $this->_i18n->translate('Recipe has not been added to data base'));
					$this->_session->setFlashData('recipeAddFormValues', $postValues);
					$this->_session->setFlashData('recipeAddErrors', $errors);
					$this->redirect($baseUrl.'/admin/recipe/addForm');
				}
				if($postValues['units'][$index] != '') {
					$unitsCollection = $this->_unitMapper->getById($postValues['units'][$index]);
					if(count($unitsCollection) != 1) {
						throw new ControllerException('Jednostka o podanym identyfikatorze nie istnieje w bazie danych');
					}
					$items[$index]['unit'] = $unitsCollection[0];
				}
				else {
					$items[$index]['unit'] = null;
				}
			}
			$items[$index]['item'] = $item;
		}
		
		//przygotowanie obiektu kategorii dla przepisu na podstawie danych z formularza
		$category = null;
		if(isset($postValues['cats'])) {
			$categoriesCollection = $this->_categoryMapper->getById($postValues['cats']);
			if(count($categoriesCollection) == 0) {
				throw new ControllerException('Kategoria o podanym identyfikatorze nie istnieje w bazie danych');
			}
			$category = $categoriesCollection[0];
		}
		
		//przygotowanie obiektu uzytkownika dla przepisu na podstawie danych z formularza
		$user = null;
		if(isset($postValues['usr'])) {
			$usersCollection = $this->_userMapper->getById($postValues['usr']);
			if(count($usersCollection) == 0) {
				throw new ControllerException('Uzytkownik o podanym identyfikatorze nie istnieje w bazie danych');
			}
			$user = $usersCollection[0];
		}		
		
		//zapisanie obiektu Recipe i powiazanie go z obiektem Category
		$this->_recipeMapper->save($recipe, $category);

		//Powiazanie obiektu Recipe z obiektem User
		if($user !== null) {
			$this->_recipeMapper->save($recipe, $user);
		}
		
		//zapisanie obiektow Item i powiazan z obiektami Recipe i Unit
		foreach($items AS $item) {
			$this->_itemMapper->save($item['item'], $recipe);
			$this->_itemMapper->save($item['item'], $item['ingredient']);
			if(isset($item['unit'])) {
				$this->_itemMapper->save($item['item'], $item['unit']);
			}
		}
		
		//przekierowanie po udanym zapisie
		$this->_session->setFlashData('notice', $this->_i18n->translate('Recipe has been added to data base'));
		$this->redirect($baseUrl.'/admin/recipe/list');
		
	}
	
}

?>
