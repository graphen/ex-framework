<?php

class AdminUserEditForm extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_userMapper = null;
	protected $_groupMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminUserEditForm';
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
		$values = $this->_session->getFlashData('userEditFormValues');
		$errors = $this->_session->getFlashData('userEditErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');				
		if(!is_array($values)) {
			$userId = (int)$this->_request->get('id');		
		}
		else {
			$userId = (int)$values['id'];
		}
		if(($userId == '') || ($userId == null)) {
			throw new ControllerException('Brak identyfikatora uzytkownika');
		}

		//pobieranie danych z modelu
		$userFieldsNames = $this->_userMapper->getFieldsNames();
		$userVirtualFieldsNames = $this->_userMapper->getVirtualFieldsNames();
		$userRelationsNames = $this->_userMapper->getRelationsNames();
		$groupFieldsNames = $this->_groupMapper->getFieldsNames();
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi uzytkownika
		$userToView = null;
		if(!is_array($values)) {
			$usersCollection = $this->_userMapper->getById($userId);
			if(count($usersCollection) != 1) {
				throw new ControllerException('Uzytkownik nie istnieje w bazie danych');
			}
			$userObject = $usersCollection[0];
			$userToView = $userObject->toArray($userFieldsNames);
			$userToView = array_map('htmlspecialchars', $userToView);
			$groupsCollection = $userObject->groups->getCollection();
			$groupsToView = array();
			foreach($groupsCollection AS $groupObject) {
				$g = array();
				$id = $groupObject->id;
				$groupsToView[] = htmlspecialchars($id); 
				
			}
			$userToView['groups'] = $groupsToView;
		}
	
		//pobieranie danych z modelu, przygotowanie tablicy z danymi grup
		$groups = $this->_groupMapper->getAll();
		$groupsToView = array();
		foreach($groups AS $group) {
			$g = array();
			$g = $group->toArray($groupFieldsNames);
			$g['info'] = $this->_i18n->translate($g['info']);
			$groupsToView[] = array_map('htmlspecialchars', $g);
		}
		
		//przygotowanie danych, jesli wystapily bledy
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
		$statuses = array(array('value'=>0, 'info'=>$this->_i18n->translate('User inactive')), array('value'=>1, 'info'=>$this->_i18n->translate('User active')));		
		$actionLink = $baseUrl.'/admin/user/edit';
		$userTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $userFieldsNames);
		
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
		$this->_view->assign('newPasswordString', $this->_i18n->translate($userVirtualFieldsNames['newPassword']));
		$this->_view->assign('userEditFormTitle', $this->_i18n->translate('User edit form'));
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
		
	}
	
}

?>
