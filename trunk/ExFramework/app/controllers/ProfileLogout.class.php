<?php

class ProfileLogout extends BaseControllerActionAbstract implements IController {
	
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
	}
	
	public function execute() {
		$baseUrl = $this->_request->getBaseUrl();
		
		$userLogin = null;
		$userIsAuth = false;
		$userLogin = $this->_session->userLogin;
		$userIsAuth = $this->_session->userIsAuth;
		if(empty($userLogin) || ($userIsAuth == false)) {
			throw new ControllerException('Brak dostepu');
		}
		else {	
			if(get_class($this->_auth->getAuthAdapter()) == 'AuthAdapterDb') {
				$userCollection = $this->_userMapper->getByLogin($userLogin);
				$user = $userCollection[0];			
				$user->lastAccess = date("Y-m-d H:i:s");
				$this->_userMapper->save($user);
			}
			$this->_auth->logout();
			$this->redirect($baseUrl);
		}
	}
	
}

?>
