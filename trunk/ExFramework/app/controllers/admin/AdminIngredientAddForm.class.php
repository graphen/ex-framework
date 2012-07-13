<?php

class AdminIngredientAddForm extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_ingredientMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminIngredientAddForm';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, IngredientMapper $ingredientMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
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
		$values = $this->_session->getFlashData('ingredientAddFormValues');
		$errors = $this->_session->getFlashData('ingredientAddErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');
		
		//pobieranie danych z modelu
		$ingredientFieldsNames = $this->_ingredientMapper->getFieldsNames();
		
		//przygotowanie tablicy z danymi skladnikow w przypadku kiedy podczas wypelniania wystapily bledy
		$ingredientToView = array();
		if(is_array($values)) {
			foreach($values AS $index=>$value) {
				$ingredientToView[$index] = htmlspecialchars($value);
			}
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$actionLink = $baseUrl.'/admin/ingredient/add';
		$ingredientTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $ingredientFieldsNames);
	
		//dolaczenie danych do widoku
		$this->_view->assign('ingredient', $ingredientToView);
		$this->_view->assign('errors', $errors);
		$this->_view->assign('flashError', $flashError);
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('ingredientTableHeadersStrings', $ingredientTableHeadersStrings);
		$this->_view->assign('ingredientAddFormTitle', $this->_i18n->translate('Ingredient add form'));		
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
			
	}
	
}

?>
