<?php

class ProfileLoginForm extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_i18n = null;
	protected $_session = null;
	protected $_userMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'profileLoginForm';
	protected $_layoutName = 'defaultLayout';
	
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
		$flashError = $this->_session->getFlashData('error');
		$flashNotice = $this->_session->getFlashData('notice');
		
		$userLogin = null;
		$userIsAuth = false;
		$userLogin = $this->_session->userLogin;
		$userIsAuth = $this->_session->userIsAuth; 
		if(!empty($userLogin) && ($userIsAuth != false)) {
			throw new ControllerException('Brak dostepu');
		}	
		
		//przygotowanie danych dla widoku
		$actionLink = $baseUrl.'/profile/login';
		$registerLink = $baseUrl.'/profile/registerForm';
		
		//dolaczenie danych do widoku
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('registerLink', $registerLink);
		$this->_view->assign('flashError', $flashError);
		$this->_view->assign('flashNotice', $flashNotice);	
		$this->_view->assign('profileLoginFormTitle', $this->_i18n->translate('Login Form'));
		$this->_view->assign('LoginLabel', $this->_i18n->translate('Login'));
		$this->_view->assign('PasswordLabel', $this->_i18n->translate('Password'));		
		$this->_view->assign('LoginString', $this->_i18n->translate('Sign in'));
		$this->_view->assign('RegisterString', $this->_i18n->translate('Register'));
		$this->_view->assign('NoAccountString', $this->_i18n->translate('If you do not have an acount'));
				
	}
			
}

?>
