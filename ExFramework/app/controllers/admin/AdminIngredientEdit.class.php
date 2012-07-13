<?php

class AdminIngredientEdit extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_ingredientMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, IngredientMapper $ingredientMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_ingredientMapper = $ingredientMapper;
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
		$ingredientId = (int)$postValues['id'];
		if(($ingredientId == '') || ($ingredientId == null)) {
			throw new ControllerException('Brak identyfikatora skladnika');
		}
		
		//pobieranie danych z modelu		
		$ingredientRelationsNames = $this->_ingredientMapper->getRelationsNames();
		$ingredientsCollection = $this->_ingredientMapper->getById($ingredientId);
		if(count($ingredientsCollection) != 1) {
			throw new ControllerException('Skladnik nie istnieje w bazie danych');
		}
		$ingredient = $ingredientsCollection[0];
		
		//wypelnienie obiektu kategorii, danymi z formularza
		foreach($postValues AS $index=>$value) {
			$ingredient->{$index} = $value;
		}
		
		//zapisywanie danych o skladniku
		$result = $this->_ingredientMapper->save($ingredient);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_ingredientMapper->hasErrors()) {
			$errors = $this->_ingredientMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Ingredient has not been updated in data base'));
			$this->_session->setFlashData('ingredientEditFormValues', $postValues);
			$this->_session->setFlashData('ingredientEditErrors', $errors);
			$this->redirect($baseUrl.'/admin/ingredient/editForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('Ingredient has been updated in data base'));
			$this->redirect($baseUrl.'/admin/ingredient/list');
		}
		
	}
	
}

?>
