<?php

class AdminResourceList extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_resourceMapper = null;
	protected $_groupMapper = null;
	protected $_viewResolver = null;
	protected $_paginator = null;
	
	protected $_templateName = 'adminResourceList';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, ResourceMapper $resourceMapper, GroupMapper $groupMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_resourceMapper = $resourceMapper;
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
		$resourceFieldsNames = $this->_resourceMapper->getFieldsNames();
		$resourceRelationsNames = $this->_resourceMapper->getRelationsNames();
		$groupFieldsNames = $this->_groupMapper->getFieldsNames();
		
		$countAll = $this->_resourceMapper->countAll();
		$this->_paginator->setLink($baseUrl.'/admin/resource/list/'); 
		$this->_paginator->setNumberOfRecords($countAll);
		$this->_paginator->setCurrentPageNumber($currentPageNumber); 
		$resources = $this->_resourceMapper->getAllResources($this->_paginator->getLimit(), $this->_paginator->getOffset());
		
		//przygotowanie tablicy z lista zasobow
		$resourcesToView = array();
		foreach($resources AS $resource) {
			$r = array();
			$r = $resource->toArray($resourceFieldsNames);
			$groups = $resource->groups->getCollection();
			$groupsToView = array();
			foreach($groups AS $group) {
				$g = array();
				$g = $group->toArray($groupFieldsNames);
				$groupsToView[] = $g;
			}
			$r['groups'] = $groupsToView;
			$resourcesToView[] = $r;
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$links = array('view'=>array($this->_i18n->translate('view'), $baseUrl.'/admin/resource/view/id/'), 'edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/resource/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/resource/delete/id/'));
		$resourceTableHeadersNames = array_map(array($this->_i18n, 'translate'), $resourceFieldsNames);
				
		//dolaczenie danych do widoku
		$this->_view->assign('resources', $resourcesToView);
		$this->_view->assign('flashNotice', $flashNotice);
		$this->_view->assign('links', $links);
		$this->_view->assign('paginator', $this->_paginator->getPaginator());			
		$this->_view->assign('resourceTableHeadersNames', $resourceTableHeadersNames);
		$this->_view->assign('groupsString', $this->_i18n->translate($resourceRelationsNames['groups']));		
		$this->_view->assign('resourceListTitle', $this->_i18n->translate('Resources list'));
		$this->_view->assign('noResourceMessage', $this->_i18n->translate('No resources in data base'));		
		$this->_view->assign('noGroupMessage', $this->_i18n->translate('Resource has no group'));
		
	}
			
}

?>
