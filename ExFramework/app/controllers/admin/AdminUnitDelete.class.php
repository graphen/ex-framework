<?php

class AdminUnitDelete extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_unitMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UnitMapper $unitMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_unitMapper = $unitMapper;
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
		if(($unitId == '') || $unitId == null) {
			throw new ControllerException('Brak identyfikatora def. jednostki');
		}
		
		//pobieranie danych z modelu, kasowanie danych o def. jednostki
		$unitFieldsNames = $this->_unitMapper->getFieldsNames();
		$unitsCollection = $this->_unitMapper->getById($unitId);
		if(count($unitsCollection) != 1) {
			throw new ControllerException('Def. jednostki nie istnieje w bazie danych');
		}
		$unit = $unitsCollection[0];
		$result = $this->_unitMapper->delete($unit);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$this->_session->setFlashData('notice', $this->_i18n->translate('Unit has been deleted from database'));
		$this->redirect($baseUrl.'/admin/unit/list');
		
	}
	
}

?>
