<?php

class AdminUserAdd extends AdminBaseControllerActionAbstract implements IController {
	
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
		
		//pobieranie danych z modelu, praca z modelem
		$user = $this->_userMapper->create($postValues);
		$group = null;
		if(isset($postValues['groups'])) {
			if((is_array($postValues['groups'])) && (!empty($postValues['groups'][0]))) {
				$groupsCollection = $this->_groupMapper->getById($postValues['groups']);
				if(count($groupsCollection) == 0) {
					throw new ControllerException('Grupa o podanym identyfikatorze nie istnieje w bazie danych');
				}
				elseif(count($groupsCollection) > 1) {
					foreach($groupsCollection AS $g) {
						$group[] = $g;
					}
				}
				else {
					$group = $groupsCollection[0];
				}
			}
		}
		$result = $this->_userMapper->save($user, $group);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_userMapper->hasErrors()) {
			$errors = $this->_userMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('User has not been added to data base'));
			$this->_session->setFlashData('userAddFormValues', $postValues);
			$this->_session->setFlashData('userAddErrors', $errors);
			$this->redirect($baseUrl.'/admin/user/addForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('User has been added to data base'));
			$this->redirect($baseUrl.'/admin/user/list');
		}
		
	}
	
}
?>
