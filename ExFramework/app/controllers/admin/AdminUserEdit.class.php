<?php

class AdminUserEdit extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_userMapper = null;
	protected $_groupMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UserMapper $userMapper, GroupMapper $groupMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_userMapper = $userMapper;
		$this->_groupMapper = $groupMapper;
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
		$postValues = $this->_request->post();
		$userId = null;
		if(isset($postValues['id'])) {
			$userId = (int)$postValues['id'];
		}
		if(($userId == '') || ($userId == null)) {
			throw new ControllerException('Brak identyfikatora uzytkownika');
		}
		
		//pobieranie danych z modelu
		$usersCollection = $this->_userMapper->getById($userId);
		if(count($usersCollection) != 1) {
			throw new ControllerException('Uzytkownik nie istnieje w bazie danych');
		}
		$user = $usersCollection[0];
		
		//obsluga grup
		$groupsToAdd = array();
		$groupsToDelete = array();
		$postValuesGroups = $postValues['groups'];
		if(isset($postValuesGroups)) {
			if(is_array($postValuesGroups)) {
				if(count($postValuesGroups) > 0) {
					$inDbGroupsCollection =  $user->groups->getCollection();
					foreach($inDbGroupsCollection AS $group) {
						if(in_array($group->id, $postValuesGroups)) {
							$key = array_search($group->id, $postValuesGroups);
							unset($postValuesGroups[$key]);
						}
						else {
							$groupsToDelete[] = $group;
						}
					}
					
					foreach($postValuesGroups AS $index=>$groupId) {
						if($groupId == '') {
							unset($postValuesGroups[$index]);
						}
					}					
					if(count($postValuesGroups) > 0) {
					$postGroupsCollection = $this->_groupMapper->getById($postValuesGroups);
						if(count($postGroupsCollection) > 0) {
							foreach($postGroupsCollection AS $group){
								$groupsToAdd[] = $group;
							}
						}
					}
				}
			}
		}
		
		//wypelnienie obiektu uzytkownika, danymi z formularza
		foreach($postValues AS $index=>$value) {
			if($index == 'groups') {
				continue;
			}
			$user->{$index} = $value;
		}
		if(!empty($postValues['newPassword'])) {
			$user->password = $postValues['newPassword'];
			$user->passwordConfirmation = $postValues['passwordConfirmation'];
		}
		
		//usuniecie powiazan  
		if(count($groupsToDelete) > 0) {
			$delResult = $this->_userMapper->delete($user, $groupsToDelete);
		}		
		//zapisywanie danych o uzytkowniku
		$result = $this->_userMapper->save($user, $groupsToAdd);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_userMapper->hasErrors()) {
			$errors = $this->_userMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('User has not been updated in data base'));
			$this->_session->setFlashData('userEditFormValues', $postValues);
			$this->_session->setFlashData('userEditErrors', $errors);
			$this->redirect($baseUrl.'/admin/user/editForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('User has been updated in data base'));
			$this->redirect($baseUrl.'/admin/user/list');
		}
		
	}
	
}

?>
