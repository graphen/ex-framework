<?php

class ProfileEdit extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_userMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UserMapper $userMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_userMapper = $userMapper;
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();
		$postValues = $this->_request->post();

 		$userLogin = null;
		$userIsAuth = false;
		$userLogin = $this->_session->userLogin;
		$userIsAuth = $this->_session->userIsAuth;
		if(empty($userLogin) || ($userIsAuth == false)) {
			throw new ControllerException('Brak dostepu');
		}	
						
		$userId = null;
		if(isset($this->_session->userData) && isset($this->_session->userData->id) && ($this->_session->userData->id != '')) {
			$userId = $this->_session->userData->id;
		}
		if(($userId == '') || ($userId == null)) {
			throw new ControllerException('Brak identyfikatora uzytkownika profilu');
		}	
		
		//pobieranie danych z modelu
		$usersCollection = $this->_userMapper->getById($userId);
		if(count($usersCollection) != 1) {
			throw new ControllerException('Uzytkownik nie istnieje w bazie danych');
		}
		$user = $usersCollection[0];
		
		//wypelnienie obiektu uzytkownika, danymi z formularza
		foreach($postValues AS $index=>$value) {
			$user->{$index} = $value;
		}
		if(!empty($postValues['newPassword'])) {
			$user->password = $postValues['newPassword'];
			$user->passwordConfirmation = $postValues['passwordConfirmation'];
		}
		
		//zapisywanie danych o uzytkowniku
		$result = $this->_userMapper->save($user);

		//przekierowanie w reakcji za wynik zapisu
		$errors = array();
		if($this->_userMapper->hasErrors()) {
			$errors = $this->_userMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('User has not been updated in data base'));
			$this->_session->setFlashData('profileEditFormValues', $postValues);
			$this->_session->setFlashData('profileEditErrors', $errors);

			$this->redirect($baseUrl.'/profile/editForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('User has been updated in data base'));
			$this->redirect($baseUrl.'/profile/view');
		}
	}
	
}

?>
