<?php

class AdminGroupView extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_userMapper = null;
	protected $_groupMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminGroupView';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UserMapper $userMapper, GroupMapper $groupMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_userMapper = $userMapper;
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
		$groupId = (int)$this->_request->get('id');		
		if(($groupId == '') || ($groupId == null)) {
			throw new ControllerException('Brak identyfikatora grupy');
		}
		
		//pobieranie danych z modelu
		$groupFieldsNames = $this->_groupMapper->getFieldsNames();
		$groupRelationsNames = $this->_groupMapper->getRelationsNames();	
		$userFieldsNames = $this->_userMapper->getFieldsNames();
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi grup
		$groupsCollection = $this->_groupMapper->getById($groupId);
		if(count($groupsCollection) != 1) {
			throw new ControllerException('Grupa nie istnieje w bazie danych');
		}
		$groupObject = $groupsCollection[0];
		$groupToView = $groupObject->toArray($groupFieldsNames);
		$usersCollection = $groupObject->users->getCollection();
		$usersToView = array();
		foreach($usersCollection AS $userObject) {
			$user = array();
			$user = $userObject->toArray($userFieldsNames);
			$usersToView[] = $user; 
		}
		$groupToView['users'] = $usersToView;
				
		//przygotowanie dodatkowych danych dla widoku
		$links = array('edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/group/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/group/delete/id/'));
		$groupTableHeadersNames = array_map(array($this->_i18n, 'translate'), $groupFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('group', $groupToView);
		$this->_view->assign('links', $links);
		$this->_view->assign('groupTableHeadersNames', $groupTableHeadersNames);
		$this->_view->assign('usersString', $this->_i18n->translate($groupRelationsNames['users']));		
		$this->_view->assign('groupViewTitle', $this->_i18n->translate('Group view'));
		$this->_view->assign('noUserMessage', $this->_i18n->translate('Group has no user'));
		
	}
	
}

?>
