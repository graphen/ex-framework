<?php

class AdminIngredientDelete extends AdminBaseControllerActionAbstract implements IController {
	
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
		$ingredientId = (int)$this->_request->get('id');	
		if(($ingredientId == '') || $ingredientId == null) {
			throw new ControllerException('Brak identyfikatora skladnika');
		}	
		
		//pobieranie danych z modelu, kasowanie danych o grupie
		$ingredientFieldsNames = $this->_ingredientMapper->getFieldsNames();
		$ingredientsCollection = $this->_ingredientMapper->getById($ingredientId);
		if(count($ingredientsCollection) != 1) {
			throw new ControllerException('Skladnik nie istnieje w bazie danych');
		}
		$ingredient = $ingredientsCollection[0];
		$result = $this->_ingredientMapper->delete($ingredient);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$this->_session->setFlashData('notice', $this->_i18n->translate('Ingredient has been deleted from database'));
		$this->redirect($baseUrl.'/admin/ingredient/list');
		
	}
	
}

?>
