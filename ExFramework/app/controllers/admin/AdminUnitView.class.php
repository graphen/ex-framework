<?php

class AdminUnitView extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_unitMapper = null;
	protected $_viewResolver = null;
	
	protected $_templateName = 'adminUnitView';
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
		$unitId = (int)$this->_request->get('id');		
		if(($unitId == '') || ($unitId == null)) {
			throw new ControllerException('Brak identyfikatora def. jednostki');
		}
		
		//pobieranie danych z modelu
		$unitFieldsNames = $this->_unitMapper->getFieldsNames();
		$unitRelationsNames = $this->_unitMapper->getRelationsNames();	
		
		//pobieranie danych z modelu, praca z modelem
		$unitsCollection = $this->_unitMapper->getById($unitId);
		if(count($unitsCollection) != 1) {
			throw new ControllerException('Def. jednostki nie istnieje w bazie danych');
		}
		$unitObject = $unitsCollection[0];
		$unitToView = $unitObject->toArray($unitFieldsNames);
				
		//przygotowanie dodatkowych danych dla widoku
		$links = array('edit'=>array($this->_i18n->translate('edit'), $baseUrl.'/admin/unit/editForm/id/'), 'delete'=>array($this->_i18n->translate('delete'), $baseUrl.'/admin/unit/delete/id/'));
		$unitTableHeadersNames = array_map(array($this->_i18n, 'translate'), $unitFieldsNames);
		
		//dolaczenie danych do widoku
		$this->_view->assign('unit', $unitToView);
		$this->_view->assign('links', $links);
		$this->_view->assign('unitTableHeadersNames', $unitTableHeadersNames);
		$this->_view->assign('unitViewTitle', $this->_i18n->translate('Unit view'));
		
	}
	
}

?>
