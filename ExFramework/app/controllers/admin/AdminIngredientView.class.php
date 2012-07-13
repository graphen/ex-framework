<?php

class AdminIngredientView extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_itemMapper = null;
	protected $_ingredientMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminIngredientView';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, ItemMapper $itemMapper, IngredientMapper $ingredientMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_itemMapper = $itemMapper;
		$this->_ingredientMapper = $ingredientMapper;
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
		$ingredientId = (int)$this->_request->get('id');		
		if(($ingredientId == '') || ($ingredientId == null)) {
			throw new ControllerException('Brak identyfikatora skladnika');
		}
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi skladnika
		$ingredientFieldsNames = $this->_ingredientMapper->getFieldsNames();
		$ingredientRelationsNames = $this->_ingredientMapper->getRelationsNames();	
		$ingredientsCollection = $this->_ingredientMapper->getById($ingredientId);
		if(count($ingredientsCollection) != 1) {
			throw new ControllerException('Skladnik nie istnieje w bazie danych');
		}
		$ingredientObject = $ingredientsCollection[0];
		$ingredientToView = $ingredientObject->toArray($ingredientFieldsNames);
		$itemsCollectionCount = count($ingredientObject->items);
		$ingredientToView['itemsCount'] = $itemsCollectionCount;
				
		//przygotowanie dodatkowych danych dla widoku
		$links = array('edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/ingredient/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/ingredient/delete/id/'));
		$ingredientTableHeadersNames = array_map(array($this->_i18n, 'translate'), $ingredientFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('ingredient', $ingredientToView);
		$this->_view->assign('links', $links);
		$this->_view->assign('ingredientTableHeadersNames', $ingredientTableHeadersNames);
		$this->_view->assign('itemsCountString', $this->_i18n->translate('Items count'));		
		$this->_view->assign('ingredientViewTitle', $this->_i18n->translate('Ingredient view'));
	}
	
}

?>
