<?php

class AdminResourceView extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_resourceMapper = null;
	protected $_groupMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminResourceView';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, ResourceMapper $resourceMapper, GroupMapper $groupMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_resourceMapper = $resourceMapper;
		$this->_groupMapper = $groupMapper;
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
		$resourceId = (int)$this->_request->get('id');		
		if(($resourceId == '') || ($resourceId == null)) {
			throw new ControllerException('Brak identyfikatora zasobu');
		}
		
		//pobieranie danych z modelu
		$resourceFieldsNames = $this->_resourceMapper->getFieldsNames();
		$resourceRelationsNames = $this->_resourceMapper->getRelationsNames();	
		$groupFieldsNames = $this->_groupMapper->getFieldsNames();
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi dla widoku
		$resourcesCollection = $this->_resourceMapper->getById($resourceId);
		if(count($resourcesCollection) != 1) {
			throw new ControllerException('Zasob nie istnieje w bazie danych');
		}
		$resourceObject = $resourcesCollection[0];
		$resourceToView = $resourceObject->toArray($resourceFieldsNames);
		$groupsCollection = $resourceObject->groups->getCollection();
		$groupsToView = array();
		foreach($groupsCollection AS $groupObject) {
			$group = array();
			$group = $groupObject->toArray($groupFieldsNames);
			$groupsToView[] = $group; 
		}
		$resourceToView['groups'] = $groupsToView;
				
		//przygotowanie dodatkowych danych dla widoku
		$links = array('edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/resource/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/resource/delete/id/'));
		$resourceTableHeadersNames = array_map(array($this->_i18n, 'translate'), $resourceFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('resource', $resourceToView);
		$this->_view->assign('links', $links);
		$this->_view->assign('resourceTableHeadersNames', $resourceTableHeadersNames);
		$this->_view->assign('groupsString', $this->_i18n->translate($resourceRelationsNames['groups']));		
		$this->_view->assign('resourceViewTitle', $this->_i18n->translate('Resource view'));
		$this->_view->assign('noGroupMessage', $this->_i18n->translate('Resource has no group'));
		
	}
	
}

?>
