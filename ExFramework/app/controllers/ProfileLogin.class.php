<?php

class ProfileLogin extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_auth = null;
	protected $_userMapper = null;
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, IAuth $auth, IDataMapper $userMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_auth = $auth;
		$this->_userMapper = $userMapper;	
		if(get_class($this->_auth->getAuthAdapter()) != 'AuthAdapterDb') {
			throw new ControllerException('Tylko obsluga uzytkownikow z lokalnej bazy danych');
		}
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();
		$referer = $this->_session->lastUrl;
		
		$userLogin = null;
		$userIsAuth = false;
		$userLogin = $this->_session->userLogin;
		$userIsAuth = $this->_session->userIsAuth; 
		if(!empty($userLogin) && ($userIsAuth != false)) {
			throw new ControllerException('Brak dostepu');
		}		
		
		$userLogin = $this->_request->post('login');
		$userPassword = $this->_request->post('password');
		
		//pobieranie danych z modelu, praca z modelem
		$result = $this->_auth->login($userLogin, $userPassword);
		if($result === true) {		
			if(get_class($this->_auth->getAuthAdapter()) == 'AuthAdapterDb') {
				$userCollection = $this->_userMapper->getByLogin($userLogin);
				$user = $userCollection[0];			
				$user->lastAccess = date("Y-m-d H:i:s");
				$user->visitCount++;
				$this->_userMapper->save($user);
			}
			$this->_session->setFlashData('notice', $this->_i18n->translate('User is authorized'));
			if(strstr($referer, $baseUrl)) {
				$this->redirect($referer);
			}
			else {
				$this->redirect($baseUrl.'/profile/view');
			}
		}
		else {
			$errorMessage = $this->_auth->getErrorMessage();
			$this->_session->setFlashData('error', $this->_i18n->translate($errorMessage));
			$this->redirect($baseUrl.'/profile/loginForm');
		}
		
	}
	
}

?>
