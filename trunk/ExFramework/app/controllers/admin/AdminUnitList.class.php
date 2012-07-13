<?php

class AdminUnitList extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_unitMapper = null;
	protected $_viewResolver = null;
	protected $_paginator = null;
	
	protected $_templateName = 'adminUnitList';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UnitMapper $unitMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_unitMapper = $unitMapper;
		$this->_viewResolver = $viewResolver;
		$this->_paginator = $paginator;
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
		$flashNotice = $this->_session->getFlashData('notice');
		$currentPageNumber = $this->_request->get('page');		
		
		//pobieranie danych z modelu
		$unitFieldsNames = $this->_unitMapper->getFieldsNames();
		$countAll = $this->_unitMapper->countAll();
		$this->_paginator->setLink($baseUrl.'/admin/unit/list/'); 
		$this->_paginator->setNumberOfRecords($countAll);
		$this->_paginator->setCurrentPageNumber($currentPageNumber); 
		$this->_paginator->setRecordsPerPage(40);
		$units = $this->_unitMapper->getAllUnits($this->_paginator->getLimit(), $this->_paginator->getOffset());
		
		//przygotowanie tablicy z lista definicji jednostek
		$unitsToView = array();
		foreach($units AS $unit) {
			$c = array();
			$c = $unit->toArray($unitFieldsNames);
			$unitsToView[] = $c;
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$links = array('view'=>array($this->_i18n->translate('view'), $baseUrl.'/admin/unit/view/id/'), 'edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/unit/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/unit/delete/id/'));
		$unitTableHeadersNames = array_map(array($this->_i18n, 'translate'), $unitFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('units', $unitsToView);	
		$this->_view->assign('flashNotice', $flashNotice);
		$this->_view->assign('links', $links);
		$this->_view->assign('paginator', $this->_paginator->getPaginator());		
		$this->_view->assign('unitTableHeadersNames', $unitTableHeadersNames);
		$this->_view->assign('unitListTitle', $this->_i18n->translate('Units list'));
		$this->_view->assign('noUnitMessage', $this->_i18n->translate('No unit in data base'));			
		
	}
			
}

?>
