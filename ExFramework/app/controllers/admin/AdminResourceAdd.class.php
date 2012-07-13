<?php

class AdminResourceAdd extends AdminBaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_resourceMapper = null;
	protected $_groupMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, ResourceMapper $resourceMapper, GroupMapper $groupMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_resourceMapper = $resourceMapper;
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
		$postValues = $this->_request->post();
		
		//pobieranie danych z modelu
		$resourceRelationsNames = $this->_resourceMapper->getRelationsNames();
		
		//zapisywanie danych o zasobie
		$resource = $this->_resourceMapper->create($postValues);
		$group = null;
		if(isset($postValues['groups'])) {
			if((is_array($postValues['groups'])) && (!empty($postValues['groups'][0]))) {
				$groupsCollection = $this->_groupMapper->getById($postValues[$resourceRelationsNames['groups']]);
				if(count($groupsCollection) == 0) {
					throw new ControllerException('Grupa o podanym identyfikatorze nie istnieje w bazie danych');
				}
				elseif(count($groupsCollection) > 1) {
					foreach($groupsCollection AS $g) {
						$group[] = $g;
					}
				}
				else {
					$group = $groupsCollection[0];
				}
			}
		}
		$result = $this->_resourceMapper->save($resource, $group);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_resourceMapper->hasErrors()) {
			$errors = $this->_resourceMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Resource has not been added to data base'));
			$this->_session->setFlashData('resourceAddFormValues', $postValues);
			$this->_session->setFlashData('resourceAddErrors', $errors);
			$this->redirect($baseUrl.'/admin/resource/addForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('Resource has been added to data base'));
			$this->redirect($baseUrl.'/admin/resource/list');
		}
		
	}
	
}
?>
