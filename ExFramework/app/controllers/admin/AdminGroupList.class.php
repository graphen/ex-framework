<?php

class AdminGroupList extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_groupMapper = null;
	protected $_viewResolver = null;
	protected $_paginator = null;
	
	protected $_templateName = 'adminGroupList';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, GroupMapper $groupMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_groupMapper = $groupMapper;
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
		$groupFieldsNames = $this->_groupMapper->getFieldsNames();
		$countAll = $this->_groupMapper->countAll();
		$this->_paginator->setLink($baseUrl.'/admin/group/list/'); 
		$this->_paginator->setNumberOfRecords($countAll);
		$this->_paginator->setCurrentPageNumber($currentPageNumber);
		$groups = $this->_groupMapper->getAllGroups($this->_paginator->getLimit(), $this->_paginator->getOffset());
		
		//przygotowanie tablicy z lista uzytkownikow
		$groupsToView = array();
		foreach($groups AS $group) {
			$g = array();
			$g = $group->toArray($groupFieldsNames);
			$groupsToView[] = $g;
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$links = array('view'=>array($this->_i18n->translate('view'), $baseUrl.'/admin/group/view/id/'), 'edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/group/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/group/delete/id/'));
		$groupTableHeadersNames = array_map(array($this->_i18n, 'translate'), $groupFieldsNames);
				
		//dolaczenie danych do widoku
		$this->_view->assign('groups', $groupsToView);
		$this->_view->assign('paginator', $this->_paginator->getPaginator());	
		$this->_view->assign('flashNotice', $flashNotice);
		$this->_view->assign('links', $links);
		$this->_view->assign('groupTableHeadersNames', $groupTableHeadersNames);
		$this->_view->assign('groupListTitle', $this->_i18n->translate('Groups list'));
		$this->_view->assign('noGroupMessage', $this->_i18n->translate('No groups in data base'));			
		
	}
			
}

?>
