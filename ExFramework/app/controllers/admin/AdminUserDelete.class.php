<?php

class AdminUserDelete extends AdminBaseControllerActionAbstract implements IController {
	
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
		$userLogin = null;
		$userIsAuth = false;
		$userLogin = $this->_session->userLogin;
		$userIsAuth = $this->_session->userIsAuth;
		if(empty($userLogin) || ($userIsAuth == false)) {
			throw new ControllerException('Brak dostepu');
		}			
		
		$baseUrl = $this->_request->getBaseUrl();
		$userId = (int)$this->_request->get('id');	
		if(($userId == '') || $userId == null) {
			throw new ControllerException('Brak identyfikatora uzytkownika');
		}	
		
		//pobieranie danych z modelu, kasowanie danych o uzytkowniku
		$usersCollection = $this->_userMapper->getById($userId);
		if(count($usersCollection) != 1) {
			throw new ControllerException('Uzytkownik nie istnieje w bazie danych');
		}
		$user = $usersCollection[0];
		$result = $this->_userMapper->delete($user);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$this->_session->setFlashData('notice', $this->_i18n->translate('User has been deleted from database'));
		$this->redirect($baseUrl.'/admin/user/list');
		
	}
	
}

?>
