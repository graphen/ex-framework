<?php

class ProfileEditForm extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_userMapper = null;
	protected $_viewResolver = null;
	
	
	protected $_templateName = 'profileEditForm';
	protected $_layoutName = 'defaultLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UserMapper $userMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_userMapper = $userMapper;
		$this->_viewResolver = $viewResolver;
		$this->_view = $this->_viewResolver->resolve($this->_templateName, $this->_layoutName, true);
		
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();
		$values = $this->_session->getFlashData('profileEditFormValues');
		$errors = $this->_session->getFlashData('profileEditErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');
		
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
		$userFieldsNames = $this->_userMapper->getFieldsNames();
		$userVirtualFieldsNames = $this->_userMapper->getVirtualFieldsNames();
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi uzytkownika
		$userToView = null;
		if(!is_array($values)) {
			$usersCollection = $this->_userMapper->getById($userId);
			if(count($usersCollection) != 1) {
				throw new ControllerException('Uzytkownik nie istnieje w bazie danych');
			}
			$userObject = $usersCollection[0];
			$userToView = $userObject->toArray($userFieldsNames);
			$userToView = array_map('htmlspecialchars', $userToView);
		}		
		
		//przygotowanie danych, jesli wystapily bledy
		if(is_array($values)) {
			foreach($values AS $index=>$value) {
				if(is_array($value)) {
					$userToView[$index] = array_map('htmlspecialchars', $value);
				}
				else {
					$userToView[$index] = htmlspecialchars($value);
				}
			}		
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$actionLink = $baseUrl.'/profile/edit';
		$userTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $userFieldsNames);

		//dolaczenie danych do widoku
		$this->_view->assign('user', $userToView);	
		$this->_view->assign('errors', $errors);
		$this->_view->assign('flashError', $flashError);	
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('userTableHeadersStrings', $userTableHeadersStrings);
		$this->_view->assign('passwordConfirmationString', $this->_i18n->translate($userVirtualFieldsNames['passwordConfirmation']));
		$this->_view->assign('newPasswordString', $this->_i18n->translate($userVirtualFieldsNames['newPassword']));
		$this->_view->assign('profileEditFormTitle', $this->_i18n->translate('Profile edit form'));
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
	}
	
}

?>
