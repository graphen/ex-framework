<?php

class CategoryMenu extends ControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_categoryMapper = null;
	protected $_viewResolver = null;
	protected $_i18n = null;
	
	protected $_templateName = 'categoryMenu';
	
	public function __construct(IRequest $request, CategoryMapper $categoryMapper, IViewResolver $viewResolver, II18n $i18n) {
		$this->_request = $request;
		$this->_categoryMapper = $categoryMapper;
		$this->_viewResolver = $viewResolver;
		$this->_i18n = $i18n;
		$this->_view = $this->_viewResolver->resolve($this->_templateName, $this->_layoutName, true);
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();
		
		//pobieranie danych z modelu
		$categories = $this->_categoryMapper->getAll();
		
		//przygotowanie tablicy z lista kategorii
		$categoriesToView = array();
		foreach($categories AS $category) {
			$cats = array();
			$cats['title'] = $category->name;
			$cats['url'] = $baseUrl.'/recipe/list/cId/'.$category->id;
			$categoriesToView[] = $cats;
		}
						
		//dolaczenie danych do widoku
		$this->_view->assign('categoriesMenuList', $categoriesToView);	
		$this->_view->assign('categoryMenuTitle', $this->_i18n->translate('Category Menu'));
	}

			
}

?>
