<?php

class AdminResourceAddForm extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_resourceMapper = null;
	protected $_groupMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminResourceAddForm';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, ResourceMapper $resourceMapper, GroupMapper $groupMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_resourceMapper = $resourceMapper;
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
		$values = $this->_session->getFlashData('resourceAddFormValues');
		$errors = $this->_session->getFlashData('resourceAddErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');
		
		//pobieranie danych z modelu
		$resourceFieldsNames = $this->_resourceMapper->getFieldsNames();
		$resourceRelationsNames = $this->_resourceMapper->getRelationsNames();
		$groupFieldsNames = $this->_groupMapper->getFieldsNames();
		$groups = $this->_groupMapper->getAll();
		
		//przygotowanie tablicy z danymi grup uzytkownika
		$groupsToView = array();
		foreach($groups AS $group) {
			$g = array();
			$g = $group->toArray($groupFieldsNames);
			$g['info'] = $this->_i18n->translate($g['info']);
			$g =  array_map('htmlspecialchars', $g);
			$groupsToView[] = $g;
		}
		
		//przygotowanie tablicy z danymi zasobu i jego grup w przypadku kiedy podczas wypelniania wystapily bledy
		$resourceToView = array();
		if(is_array($values)) {
			foreach($values AS $index=>$value) {
				if(is_array($value)) {
					$resourceToView[$index] = array_map('htmlspecialchars', $value);
				}
				else {
					$resourceToView[$index] = htmlspecialchars($value);
				}
			}
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$actionLink = $baseUrl.'/admin/resource/add';
		$resourceTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $resourceFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('resource', $resourceToView);
		$this->_view->assign('groups', $groupsToView);
		$this->_view->assign('errors', $errors);
		$this->_view->assign('flashError', $flashError);		
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('resourceTableHeadersStrings', $resourceTableHeadersStrings);
		$this->_view->assign('groupsString', $this->_i18n->translate($resourceRelationsNames['groups']));
		$this->_view->assign('resourceAddFormTitle', $this->_i18n->translate('Resource add form'));		
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
				
	}
	
}

?>
