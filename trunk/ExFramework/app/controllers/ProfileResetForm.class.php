<?php

class ProfileResetForm extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'profileResetForm';
	protected $_layoutName = 'defaultLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_viewResolver = $viewResolver;
		$this->_view = $this->_viewResolver->resolve($this->_templateName, $this->_layoutName, true);
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();
		$flashError = $this->_session->getFlashData('error');
		
		$userLogin = null;
		$userIsAuth = false;
		$userLogin = $this->_session->userLogin;
		$userIsAuth = $this->_session->userIsAuth; 
		if(!empty($userLogin) && ($userIsAuth != false)) {
			throw new ControllerException('Brak dostepu');
		}
				
		//przygotowanie danych dla widoku
		$actionLink = $baseUrl.'/profile/reset';
		
		//dolaczenie danych do widoku
		$this->_view->assign('flashError', $flashError);		
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('loginLabel', $this->_i18n->translate('login'));
		$this->_view->assign('emailLabel', $this->_i18n->translate('email'));
		$this->_view->assign('profileResetFormTitle', $this->_i18n->translate('Password reset form'));		
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		
	}
	
}

?>
