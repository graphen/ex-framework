<?php

class AdminUserAddForm extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_userMapper = null;
	protected $_groupMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminUserAddForm';
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
		$values = $this->_session->getFlashData('userAddFormValues');
		$errors = $this->_session->getFlashData('userAddErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');
		
		//pobieranie danych z modelu
		$userFieldsNames = $this->_userMapper->getFieldsNames();
		$userVirtualFieldsNames = $this->_userMapper->getVirtualFieldsNames();
		$userRelationsNames = $this->_userMapper->getRelationsNames();
		$groupFieldsNames = $this->_groupMapper->getFieldsNames();
		$groups = $this->_groupMapper->getAll();
		
		//przygotowanie tablicy z danymi grup uzytkownika
		$groupsToView = array();
		foreach($groups AS $group) {
			$g = array();
			$g = $group->toArray($groupFieldsNames);
			$g['info'] = $this->_i18n->translate($g['info']);
			$groupsToView[] = $g;
		}
		
		//przygotowanie tablicy z danymi uzytkownika i jego grup w przypadku kiedy podczas wypelniania wystapily bledy
		$userToView = array();
		if(is_array($values)) {
			foreach($values AS $index=>$value) {
				if(is_array($value)) {
					$userToView[$index] = array_map('htmlspecialchars', $value);
				}
				else {
					$userToView[$index] = htmlspecialchars($value);
				}
			}
		}		
		
		//przygotowanie dodatkowych danych dla widoku
		$actionLink = $baseUrl.'/admin/user/add';
		$userTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $userFieldsNames);
		$statuses = array(array('value'=>0, 'info'=>$this->_i18n->translate('User inactive')), array('value'=>1, 'info'=>$this->_i18n->translate('User active')));
		
		//dolaczenie danych do widoku
		$this->_view->assign('user', $userToView);
		$this->_view->assign('groups', $groupsToView);
		$this->_view->assign('statuses', $statuses);
		$this->_view->assign('errors', $errors);
		$this->_view->assign('flashError', $flashError);		
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('userTableHeadersStrings', $userTableHeadersStrings);
		$this->_view->assign('groupsString', $this->_i18n->translate($userRelationsNames['groups']));
		$this->_view->assign('passwordConfirmationString', $this->_i18n->translate($userVirtualFieldsNames['passwordConfirmation']));
		$this->_view->assign('userAddFormTitle', $this->_i18n->translate('User add form'));		
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
				
	}
	
}

?>
