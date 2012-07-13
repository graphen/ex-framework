<?php

class AdminRecipeDelete extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_recipeMapper = null;
	protected $_itemMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, RecipeMapper $recipeMapper, ItemMapper $itemMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_recipeMapper = $recipeMapper;
		$this->_itemMapper = $itemMapper;
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
		if(($recipeId == '') || $recipeId == null) {
			throw new ControllerException('Brak identyfikatora przepisu');
		}
		
		//pobieranie danych z modelu, kasowanie danych o przepisie
		$recipeFieldsNames = $this->_recipeMapper->getFieldsNames();
		$recipesCollection = $this->_recipeMapper->getById($recipeId);
		if(count($recipesCollection) != 1) {
			throw new ControllerException('Przepis nie istnieje w bazie danych');
		}
		$recipe = $recipesCollection[0];
		$items = $recipe->items->getCollection();
		foreach($items AS $item) {
			$this->_itemMapper->delete($item);
		} 
		$result = $this->_recipeMapper->delete($recipe);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$this->_session->setFlashData('notice', $this->_i18n->translate('Recipe has been deleted from database'));
		$this->redirect($baseUrl.'/admin/recipe/list');
		
	}
	
}

?>
