<?php

class ProfileBlock extends ControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_i18n = null;
	protected $_session = null;
	protected $_userMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'profileBlock';
	
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
		$baseUrl = $this->_request->getBaseUrl();
		
		$userToView = null;
		$userLogin = null;
		$userLogin = $this->_session->userLogin;
		if($userLogin != '' && $userLogin != null) {
			//pobieranie danych z modelu
			$userCollection = $this->_userMapper->getByLogin($userLogin);
			if(count($userCollection) == 1) {
				$user = $userCollection[0];
				$userToView = array('login'=>$user->login, 'lastAccess'=>$this->_session->userData->lastaccess);				
			}
		}
		
		//przygotowanie danych dla widoku
		$actionLink = $baseUrl.'/profile/login';
		$logoutLink = $baseUrl.'/profile/logout';
		$registerLink = $baseUrl.'/profile/registerForm';
		
		//dolaczenie danych do widoku
		$this->_view->assign('profileBlockTitle', $this->_i18n->translate('Profile block'));
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('logoutLink', $logoutLink);
		$this->_view->assign('registerLink', $registerLink);
		$this->_view->assign('user', $userToView);		
		$this->_view->assign('LoginLabel', $this->_i18n->translate('Login'));
		$this->_view->assign('PasswordLabel', $this->_i18n->translate('Password'));		
		$this->_view->assign('LoginString', $this->_i18n->translate('Login'));
		$this->_view->assign('LogoutString', $this->_i18n->translate('Logout'));
		$this->_view->assign('WelcomeString', $this->_i18n->translate('Hello'));
		$this->_view->assign('LastAccessString', $this->_i18n->translate('Last access date'));
		$this->_view->assign('RegisterString', $this->_i18n->translate('Register'));
		$this->_view->assign('NoAccountString', $this->_i18n->translate('If you do not have an acount'));	
		
	}
			
}

?>
