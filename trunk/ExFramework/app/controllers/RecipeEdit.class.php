<?php

class RecipeEdit extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_recipeMapper = null;
	protected $_categoryMapper = null;
	protected $_ingredientMapper = null;
	protected $_unitMapper = null;
	protected $_itemMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, RecipeMapper $recipeMapper, CategoryMapper $categoryMapper, IngredientMapper $ingredientMapper, UnitMapper $unitMapper, ItemMapper $itemMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_recipeMapper = $recipeMapper;
		$this->_categoryMapper = $categoryMapper;
		$this->_ingredientMapper = $ingredientMapper;
		$this->_unitMapper = $unitMapper;
		$this->_itemMapper = $itemMapper;
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();
		$postValues = $this->_request->post();
		$recipeId = null;
		if(isset($postValues['id'])) {
			$recipeId = (int)$postValues['id'];
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
		
		//pobieranie danych o przepisie z modelu
		$recipeRelationsNames = $this->_recipeMapper->getRelationsNames();
		$recipesCollection = $this->_recipeMapper->getById($recipeId);
		if(count($recipesCollection) != 1) {
			throw new ControllerException('Przepis nie istnieje w bazie danych');
		}
		$recipe = $recipesCollection[0];
		
		//sprawdzenie czy uzytkownik ma prawo do edycji przepisu
		$recipeOwnerCollection = $recipe->users->getCollection();
		if(count($recipeOwnerCollection) == 0) {
			throw new ControllerException('Nie masz praw do edycji przepisu. Przepis uzytkownika anonimowego');
		}
		$recipeOwner = $recipeOwnerCollection[0];
		$recipeOwnerLogin = $recipeOwner->login;
		$recipeOwnerId = $recipeOwner->id;
		if(($recipeOwnerId != $userId) && ($recipeOwnerLogin != $userLogin)) {
			throw new ControllerException('Nie masz praw do edycji przepisu');
		}		
		
		//wypelnienie obiektu przepisu, danymi z formularza i wstepne sprawdzanie
		foreach($postValues AS $index=>$value) {
			if(($index != 'categories') && ($index != 'users') && ($index != 'items')) {
				$recipe->{$index} = $value;
			}
		}
		$this->_recipeMapper->validate($postValues, 'update');
		//jesli wystapily bledy przekierowanie do strony formularza
		$errors = array();
		if($this->_recipeMapper->hasErrors()) {
			$errors = $this->_recipeMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Recipe has not been updated in data base'));
			$this->_session->setFlashData('recipeEditFormValues', $postValues);
			$this->_session->setFlashData('recipeEditErrors', $errors);
			$this->redirect($baseUrl.'/recipe/editForm');
		}
		
		//przygotowanie tablicy obiektow item do zapisu lub usuniecia na podstawie danych przeslanych w formularzu
		$itemsInDbCollection = $recipe->items->getCollection();
		$itemsInDb = array();
		foreach($itemsInDbCollection AS $item) {
			$itemsInDb[] = $item;
		}
		$itemsInDbToUnset = array();
		$itemsToDelete = null;
		$items = array();
		
		foreach($postValues['ingredients'] AS $index=>$ingredientId) {
			$actualIngredientId = null;
			$actualUnitId = null;
			$actualItemAmount = null;
			if($ingredientId == '') {
				continue;
			}
			$ingredientsCollection = $this->_ingredientMapper->getById($ingredientId);
			if(count($ingredientsCollection) != 1) {
				throw new ControllerException('Skladnik o podanym identyfikatorze nie istnieje w bazie danych');
			}
			$items[$index]['ingredient'] = $ingredientsCollection[0];
			$items[$index]['unit'] = null;
			$actualIngredientId = $items[$index]['ingredient']->id;
			$item = $this->_itemMapper->create();
			if($postValues['amount'][$index] != '') {
				$item->amount = $postValues['amount'][$index];
				$this->_itemMapper->validate(array('amount'=>$postValues['amount'][$index]), 'update');
				if($this->_itemMapper->hasErrors()) {
					$errors = $this->_itemMapper->getErrors(); //tutaj podpiac i18n!!!
					$this->_session->setFlashData('error', $this->_i18n->translate('Recipe has not been updated in data base'));
					$this->_session->setFlashData('recipeEditFormValues', $postValues);
					$this->_session->setFlashData('recipeEditErrors', $errors);
					$this->redirect($baseUrl.'/recipe/editForm');
				}
				$actualItemAmount = $postValues['amount'][$index];
				if($postValues['units'][$index] != '') {
					$unitsCollection = $this->_unitMapper->getById($postValues['units'][$index]);
					if(count($unitsCollection) != 1) {
						throw new ControllerException('Jednostka o podanym identyfikatorze nie istnieje w bazie danych');
					}
					$items[$index]['unit'] = $unitsCollection[0];
					$actualUnitId = $items[$index]['unit']->id;
				}
				else {
					$items[$index]['unit'] = null;
				}
			}
			$items[$index]['item'] = $item;
			
			foreach($itemsInDb AS $key=>$dbItem) {
				$amountF = false;
				$ingredientF = false;
				$unitF = false;
				if($dbItem->ingredientId == $actualIngredientId) {
					$ingredientF = true;
				}
				if(($dbItem->amount == $actualItemAmount) || (empty($dbItem->amount) && empty($actualItemAmount))) {
					$amountF = true;
				}
				if(($dbItem->unitId == $actualUnitId) || (empty($dbItem->unitId) && empty($actualUnitId))) {
					$unitF = true;
				}
				if($amountF==true && $ingredientF==true && $unitF==true) {
					$items[$index]['unset'] = true; //obiekty item istnieja juz w bazie wiec nie trzeba ich dodawac
					$itemsInDbToUnset[$key] = true; //jesli obiekt item istnieje w bazie, usuwam go z tablicy, te ktore zostana w tablicy trzeba usunac z bazy, uzytkownik ich nie wybral w formularzu, wiec nie ma ich byc w bazie
				}
			}
		}
		foreach($items AS $index=>$item) {
			if((isset($item['unset'])) && ($item['unset'] == true)) {
				unset($items[$index]);
			}
		}
		foreach($itemsInDbToUnset AS $index=>$item) {
			if($item == true) {
				unset($itemsInDb[$index]);
			}
		}
		$itemsToDelete = $itemsInDb;
		
		//przygotowanie obiektu kategorii dla przepisu na podstawie danych z formularza
		$categoryToAdd = null;
		$categoryToDelete = null;
		if(isset($postValues['cats'])) {
			$categoriesCollection = $this->_categoryMapper->getById($postValues['cats']);
			if(count($categoriesCollection) == 0) {
				throw new ControllerException('Kategoria o podanym identyfikatorze nie istnieje w bazie danych');
			}
			$category = $categoriesCollection[0];
			$categoriesCollectionInDb = $recipe->categories->getCollection();
			$categoryInDb = $categoriesCollectionInDb[0];
			if($categoryInDb->id != $category->id) {
				$categoryToDelete = $categoryInDb;
				$categoryToAdd = $category;
			}
		}
		
		//zapisanie obiektu Recipe i powiazanie go z obiektem Category, skasowanie starego powiazania
		if($categoryToDelete != null) {
			$this->_recipeMapper->delete($recipe, $categoryToDelete);
		}
		$this->_recipeMapper->save($recipe, $categoryToAdd);
		
		//zapisanie obiektow Item i powiazan z obiektami Recipe i Unit
		if(is_array($itemsToDelete)) {
			foreach($itemsToDelete AS $item) {
				$this->_itemMapper->delete($item);
			}
		}
		if(is_array($items)) {
			foreach($items AS $item) {
				$this->_itemMapper->save($item['item'], $recipe);
				$this->_itemMapper->save($item['item'], $item['ingredient']);
				if(isset($item['unit'])) {
					$this->_itemMapper->save($item['item'], $item['unit']);
				}
			}
		}
		
		//przekierowanie po udanym zapisie
		$this->_session->setFlashData('notice', $this->_i18n->translate('Recipe has been updated in data base'));
		$this->redirect($baseUrl.'/recipe/ownList');
		
	}
	
}

?>
