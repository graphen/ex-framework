<?php

class AdminGroupDelete extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_groupMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, GroupMapper $groupMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_groupMapper = $groupMapper;
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
		$groupId = (int)$this->_request->get('id');	
		if(($groupId == '') || $groupId == null) {
			throw new ControllerException('Brak identyfikatora grupy');
		}
		
		//pobieranie danych z modelu, kasowanie danych o grupie
		$groupFieldsNames = $this->_groupMapper->getFieldsNames();
		$groupsCollection = $this->_groupMapper->getById($groupId);
		if(count($groupsCollection) != 1) {
			throw new ControllerException('Grupa nie istnieje w bazie danych');
		}
		$group = $groupsCollection[0];
		$result = $this->_groupMapper->delete($group);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$this->_session->setFlashData('notice', $this->_i18n->translate('Group has been deleted from database'));
		$this->redirect($baseUrl.'/admin/group/list');
		
	}
	
}

?>
