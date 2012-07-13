<?php

class AdminIngredientAdd extends AdminBaseControllerActionAbstract implements IController {
	
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
		
		//pobieranie danych z modelu, zapisywanie danych o sklasniku	
		$ingredient = $this->_ingredientMapper->create($postValues);
		$result = $this->_ingredientMapper->save($ingredient);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_ingredientMapper->hasErrors()) {
			$errors = $this->_ingredientMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Ingredient has not been added to data base'));
			$this->_session->setFlashData('ingredientAddFormValues', $postValues);
			$this->_session->setFlashData('ingredientAddErrors', $errors);
			$this->redirect($baseUrl.'/admin/ingredient/addForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('Ingredient has been added to data base'));
			$this->redirect($baseUrl.'/admin/ingredient/list');
		}
		
	}
	
}

?>
