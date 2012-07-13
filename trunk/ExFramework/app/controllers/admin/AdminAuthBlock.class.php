<?php

class AdminAuthBlock extends ControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_i18n = null;
	protected $_session = null;
	protected $_userMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminAuthBlock';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UserMapper $userMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_i18n = $i18n;
		$this->_session = $session;
		$this->_userMapper = $userMapper;
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
			//throw new ControllerException('Brak dostepu');
		}		
		
		$baseUrl = $this->_request->getBaseUrl();
		
		$userToView = null;
		$userId = null;
		$userId = $this->_session->userData->id;
		if($userId != '' && $userId != null) {
			//pobieranie danych z modelu
			$userFieldsNames = $this->_userMapper->getFieldsNames();
			$userCollection = $this->_userMapper->get($userId);
			if(count($userCollection) == 1) {
				$user = $userCollection[0];
				$userToView = $user->toArray($userFieldsNames);
			}
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$logoutLink = $baseUrl.'/profile/logout';
		
		//dolaczenie danych do widoku
		$this->_view->assign('adminAuthBlockTitle', $this->_i18n->translate('Admin block'));
		$this->_view->assign('logoutLink', $logoutLink);
		$this->_view->assign('user', $userToView);		
		$this->_view->assign('UserBlockString', $this->_i18n->translate('Profile block'));
		$this->_view->assign('LogoutString', $this->_i18n->translate('Logout'));
		$this->_view->assign('WelcomeString', $this->_i18n->translate('Hello'));
		$this->_view->assign('LastAccessString', $this->_i18n->translate('Last access date'));	
		
	}
			
}

?>
