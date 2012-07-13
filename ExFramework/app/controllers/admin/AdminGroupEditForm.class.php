<?php

class AdminGroupEditForm extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_groupMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminGroupEditForm';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, GroupMapper $groupMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_groupMapper = $groupMapper;
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
			throw new ControllerException('Brak dostepu');
		}			
		
		$baseUrl = $this->_request->getBaseUrl();
		$values = $this->_session->getFlashData('groupEditFormValues');
		$errors = $this->_session->getFlashData('groupEditErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');				
		if(!is_array($values)) {
			$groupId = (int)$this->_request->get('id');		
		}
		else {
			$groupId = (int)$values['id'];
		}
		if(($groupId == '') || ($groupId == null)) {
			throw new ControllerException('Brak identyfikatora grupy');
		}

		//pobieranie danych z modelu, przygotowanie tablicy z danymi grupy
		$groupFieldsNames = $this->_groupMapper->getFieldsNames();
		
		//pobieranie danych z modelu, przygotowanie tablicy z danymi grupy
		if(!is_array($values)) {
			$groupCollection = $this->_groupMapper->getById($groupId);
			if(count($groupCollection) != 1) {
				throw new ControllerException('Grupa nie istnieje w bazie danych');
			}
			$groupObject = $groupCollection[0];
			$groupToView = $groupObject->toArray($groupFieldsNames);
			$groupToView = array_map('htmlspecialchars', $groupToView);
		}
		
		//przygotowanie wartosci formularza, jesli skrypt uruchamiany jesli wystapily bledy podczas wprowadzania danych w formularzu
		if(is_array($values)) {
			foreach($values AS $index=>$value) {
				$groupToView[$index] = htmlspecialchars($value);
			}
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$actionLink = $baseUrl.'/admin/group/edit';
		$groupTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $groupFieldsNames);
		$rootValues = array(array('value'=>1, 'info'=>$this->_i18n->translate('Group is root group')), array('value'=>0, 'info'=>$this->_i18n->translate('Group is no root group')));
		
		//dolaczenie danych do widoku
		$this->_view->assign('group', $groupToView);
		$this->_view->assign('rootValues', $rootValues);
		$this->_view->assign('errors', $errors);
		$this->_view->assign('flashError', $flashError);
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('groupTableHeadersStrings', $groupTableHeadersStrings);
		$this->_view->assign('groupEditFormTitle', $this->_i18n->translate('Group edit form'));
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
		
	}
	
}

?>
