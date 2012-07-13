<?php

class AdminUnitEditForm extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_unitMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminUnitEditForm';
	protected $_layoutName = 'adminLayout';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UnitMapper $unitMapper, IViewResolver $viewResolver) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_unitMapper = $unitMapper;
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
		$values = $this->_session->getFlashData('unitEditFormValues');
		$errors = $this->_session->getFlashData('unitEditErrors'); //przepuscic przez i18n
		$flashError = $this->_session->getFlashData('error');
		if(!is_array($values)) {
			$unitId = (int)$this->_request->get('id');		
		}
		else {
			$unitId = (int)$values['id'];
		}
		if(($unitId == '') || ($unitId == null)) {
			throw new ControllerException('Brak identyfikatora def. jednostki');
		}
		
		//pobieranie danych z modelu, praca z modelem
		$unitFieldsNames = $this->_unitMapper->getFieldsNames();
		$unitToView = null;
		if(!is_array($values)) {
			$unitsCollection = $this->_unitMapper->getById($unitId);
			if(count($unitsCollection) != 1) {
				throw new ControllerException('Def. jednostki nie istnieje w bazie danych');
			}
			$unitObject = $unitsCollection[0];
			$unitToView = $unitObject->toArray($unitFieldsNames);
			$unitToView = array_map('htmlspecialchars', $unitToView);
		}
		
		//przygotowanie wartosci formularza, jesli skrypt uruchamiany jesli wystapily bledy podczas wprowadzania danych w formularzu
		if(is_array($values)) {
			foreach($values AS $index=>$value) {
				$unitToView[$index] = htmlspecialchars($value);
			}
		}
		
		//przygotowanie dodatkowych danych dla widoku
		$actionLink = $baseUrl.'/admin/unit/edit';
		$unitTableHeadersStrings = array_map(array($this->_i18n, 'translate'), $unitFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('unit', $unitToView);
		$this->_view->assign('errors', $errors);
		$this->_view->assign('flashError', $flashError);
		$this->_view->assign('actionLink', $actionLink);
		$this->_view->assign('unitTableHeadersStrings', $unitTableHeadersStrings);
		$this->_view->assign('unitEditFormTitle', $this->_i18n->translate('Unit edit form'));
		$this->_view->assign('submitString', $this->_i18n->translate('Submit'));
		$this->_view->assign('resetString', $this->_i18n->translate('Reset'));
		
	}
	
}

?>
