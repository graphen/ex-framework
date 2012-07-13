<?php

class ProfileView extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_userMapper = null;
	protected $_groupMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'profileView';
	protected $_layoutName = 'defaultLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UserMapper $userMapper, GroupMapper $groupMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_userMapper = $userMapper;
		$this->_groupMapper = $groupMapper;
		$this->_viewResolver = $viewResolver;
		$this->_view = $this->_viewResolver->resolve($this->_templateName, $this->_layoutName, true);
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();
		$flashNotice = $this->_session->getFlashData('notice');	
		
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
		$userRelationsNames = $this->_userMapper->getRelationsNames();
		$groupFieldsNames = $this->_groupMapper->getFieldsNames();
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi uzytkownikow
		$usersCollection = $this->_userMapper->getById($userId);
		if(count($usersCollection) != 1) {
			throw new ControllerException('Uzytkownik nie istnieje w bazie danych');
		}
		$userObject = $usersCollection[0];
		$userToView = $userObject->toArray($userFieldsNames);
		
		//przygotowanie dodatkowych danych dla widoku
		$links = array('edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/profile/editForm/'));
		$userTableHeadersNames = array_map(array($this->_i18n, 'translate'), $userFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('user', $userToView);
		$this->_view->assign('flashNotice', $flashNotice);
		$this->_view->assign('links', $links);	
		$this->_view->assign('userTableHeadersNames', $userTableHeadersNames);
		$this->_view->assign('profileViewTitle', $this->_i18n->translate('Profile view'));
		$this->_view->assign('noGroupMessage', $this->_i18n->translate('User has no group'));
		
	}
	
}

?>
