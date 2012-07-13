<?php

class searchBlock extends ControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_i18n = null;		
	protected $_categoryMapper = null;
	protected $_userMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'searchBlock';
	
	public function __construct(IRequest $request, II18n $i18n, CategoryMapper $categoryMapper, UserMapper $userMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_i18n = $i18n;
		$this->_categoryMapper = $categoryMapper;
		$this->_userMapper = $userMapper;
		$this->_viewResolver = $viewResolver;
		$this->_view = $this->_viewResolver->resolve($this->_templateName, $this->_layoutName, true);
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();

		//pobieranie danych z modelu
		$categories = $this->_categoryMapper->getAll();
		$categoriesToView = array();
		foreach($categories AS $category) {
			$categoriesToView[] = array('id'=>$category->id, 'name'=>htmlspecialchars($category->name));
		}
		
		//przygotowanie tablicy z danymi uzytkownikow
		$users = $this->_userMapper->getAll();
		$usersToView = array();
		foreach($users AS $user) {
			$usersToView[] = array('id'=>$user->id, 'login'=>htmlspecialchars($user->login));
		}

		//przygotowanie dodatkowych danych dla widoku
		$actionLink = $baseUrl.'/recipe/search/';
		
		//dolaczenie danych do widoku
		$this->_view->assign('searchBlockTitle', $this->_i18n->translate('Searcher'));
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('users', $usersToView);		
		$this->_view->assign('categories', $categoriesToView);
		$this->_view->assign('searchString', $this->_i18n->translate('Search'));	
		$this->_view->assign('catFilterString', $this->_i18n->translate('Filter by category'));		
		$this->_view->assign('userFilterString', $this->_i18n->translate('Filter by user'));
				
	}
			
}

?>
