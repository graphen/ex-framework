<?php

class AdminUserList extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_userMapper = null;
	protected $_groupMapper = null;
	protected $_viewResolver = null;
	protected $_paginator = null;
	
	protected $_templateName = 'adminUserList';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UserMapper $userMapper, GroupMapper $groupMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_userMapper = $userMapper;
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
		$userFieldsNames = $this->_userMapper->getFieldsNames();
		$userRelationsNames = $this->_userMapper->getRelationsNames();
		$groupFieldsNames = $this->_groupMapper->getFieldsNames();
		$countAll = $this->_userMapper->countAll();
		$this->_paginator->setLink($baseUrl.'/admin/user/list/'); 
		$this->_paginator->setNumberOfRecords($countAll);
		$this->_paginator->setCurrentPageNumber($currentPageNumber); 
		$users = $this->_userMapper->getAllUsers($this->_paginator->getLimit(), $this->_paginator->getOffset());
				
		//praca z modelem
		$usersToView = array();
		foreach($users AS $user) {
			$u = array();
			$u = $user->toArray($userFieldsNames);
			$groups = $user->groups->getCollection();
			$groupsToView = array();
			foreach($groups AS $group) {
				$g = array();
				$g = $group->toArray($groupFieldsNames);
				$groupsToView[] = $g;
			}
			$u['groups'] = $groupsToView;
			$usersToView[] = $u;
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$links = array('view'=>array($this->_i18n->translate('view'), $baseUrl.'/admin/user/view/id/'), 'edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/user/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/user/delete/id/'));
		$userTableHeadersNames = array_map(array($this->_i18n, 'translate'), $userFieldsNames);
				
		//dolaczenie danych do widoku
		$this->_view->assign('users', $usersToView);
		$this->_view->assign('flashNotice', $flashNotice);
		$this->_view->assign('links', $links);
		$this->_view->assign('paginator', $this->_paginator->getPaginator());		
		$this->_view->assign('userTableHeadersNames', $userTableHeadersNames);
		$this->_view->assign('groupsString', $this->_i18n->translate($userRelationsNames['groups']));		
		$this->_view->assign('userListTitle', $this->_i18n->translate('Users list'));
		$this->_view->assign('noUserMessage', $this->_i18n->translate('No users in data base'));			
		$this->_view->assign('noGroupMessage', $this->_i18n->translate('User has no group'));
		
	}
			
}

?>
