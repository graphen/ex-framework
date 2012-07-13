<?php

class AdminEntryDelete extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_entryMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, EntryMapper $entryMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_entryMapper = $entryMapper;
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
		$entryId = (int)$this->_request->get('id');	
		if(($entryId == '') || $entryId == null) {
			throw new ControllerException('Brak identyfikatora wpisu menu');
		}	
		
		//pobieranie danych z modelu
		$entryFieldsNames = $this->_entryMapper->getFieldsNames();
			
		//pobieranie danych z modelu, kasowanie danych o wpisie menu
		$entriesCollection = $this->_entryMapper->getById($entryId);
		if(count($entriesCollection) != 1) {
			throw new ControllerException('Wpis menu nie istnieje w bazie danych');
		}
		$entry = $entriesCollection[0];
		$result = $this->_entryMapper->delete($entry);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$this->_session->setFlashData('notice', $this->_i18n->translate('Entry has been deleted from database'));
		$this->redirect($baseUrl.'/admin/entry/list');
		
	}
	
}

?>
