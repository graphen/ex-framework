<?php

class AdminResourceDelete extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_resourceMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, ResourceMapper $resourceMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_resourceMapper = $resourceMapper;
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
		$resourceId = (int)$this->_request->get('id');	
		if(($resourceId == '') || $resourceId == null) {
			throw new ControllerException('Brak identyfikatora zasobu');
		}	
		
		//pobieranie danych z modelu, kasowanie danych o uzytkowniku
		$resourceFieldsNames = $this->_resourceMapper->getFieldsNames();
		$resourcesCollection = $this->_resourceMapper->getById($resourceId);
		if(count($resourcesCollection) != 1) {
			throw new ControllerException('Zasob nie istnieje w bazie danych');
		}
		$resource = $resourcesCollection[0];
		$result = $this->_resourceMapper->delete($resource);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$this->_session->setFlashData('notice', $this->_i18n->translate('Resource has been deleted from database'));
		$this->redirect($baseUrl.'/admin/resource/list');
		
	}
	
}

?>
