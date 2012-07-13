<?php

class PublicMenu extends ControllerActionAbstract implements IController {
	
	protected $_request = null;	
	protected $_menuMapper = null;
	protected $_entryMapper = null;
	protected $_viewResolver = null;
	protected $_i8n = null;
	
	protected $_templateName = 'publicMenu';
	
	public function __construct(IRequest $request, MenuMapper $menuMapper, EntryMapper $entryMapper, IViewResolver $viewResolver, II18n $i18n) {
		$this->_request = $request;
		$this->_menuMapper = $menuMapper;
		$this->_entryMapper = $entryMapper;
		$this->_viewResolver = $viewResolver;
		$this->_i18n = $i18n;
		$this->_view = $this->_viewResolver->resolve($this->_templateName, $this->_layoutName, true);
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();
				
		//pobieranie danych z modelu
		$menusCollection = $this->_menuMapper->getById(2); //Pod id 2 jest menu panelu publiczne.
		if(count($menusCollection) != 1) {
			$this->_view->assign('menuEntries', '');
		}
		else {
			$menu = $menusCollection[0];
			//przygotowanie tablicy z lista wpisow menu administracyjnego
			$entriesToView = array();
			$entries = $menu->entries->getCollection();
			foreach($entries AS $entry) {
				$entriesToView[] = array('title'=>$entry->title, 'url'=>$entry->url);
			}
					
			//dolaczenie danych do widoku
			$this->_view->assign('menuEntries', $entriesToView);
		}
		$this->_view->assign('menuTitle', $this->_i18n->translate('Public Menu'));
	}
			
}

?>
