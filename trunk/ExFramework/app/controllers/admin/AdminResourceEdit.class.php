<?php

class AdminResourceEdit extends AdminBaseControllerActionAbstract implements IController {
	
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
		$resourceId = null;
		if(isset($postValues['id'])) {
			$resourceId = (int)$postValues['id'];
		}
		if(($resourceId == '') || ($resourceId == null)) {
			throw new ControllerException('Brak identyfikatora zasobu');
		}
		
		//pobieranie danych z modelu		
		$resourceRelationsNames = $this->_resourceMapper->getRelationsNames();
		$resourcesCollection = $this->_resourceMapper->getById($resourceId);
		if(count($resourcesCollection) != 1) {
			throw new ControllerException('Zasob nie istnieje w bazie danych');
		}
		$resource = $resourcesCollection[0];
		
		//obsluga grup
		$groupsToAdd = array();
		$groupsToDelete = array();
		$postValuesGroups = $postValues['groups'];
		if(isset($postValuesGroups)) {
			if(is_array($postValuesGroups)) {	
				if(count($postValuesGroups) > 0) {
					$inDbGroupsCollection =  $resource->groups->getCollection();
					foreach($inDbGroupsCollection AS $group) {
						if(in_array($group->id, $postValuesGroups)) {
							$key = array_search($group->id, $postValuesGroups);
							unset($postValuesGroups[$key]);
						}
						else {
							$groupsToDelete[] = $group;
						}
					}
					foreach($postValuesGroups AS $index=>$groupId) {
						if($groupId == '') {
							unset($postValuesGroups[$index]);
						}
					}
					if(count($postValuesGroups) > 0) {
					$postGroupsCollection = $this->_groupMapper->getById($postValues[$resourceRelationsNames['groups']]);
						if(count($postGroupsCollection) > 0) {
							foreach($postGroupsCollection AS $group){
								$groupsToAdd[] = $group;
							}
						}
					}
				}
			}
		}
		
		//wypelnienie obiektu zasobu, danymi z formularza
		foreach($postValues AS $index=>$value) {
			if($index == 'groups') {
				continue;
			}
			$resource->{$index} = $value;
		}
		
		//usuniecie powiazan  
		if(count($groupsToDelete) > 0) {
			$delResult = $this->_resourceMapper->delete($resource, $groupsToDelete);
		}
		//zapisywanie danych o zasobie		
		$result = $this->_resourceMapper->save($resource, $groupsToAdd);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_resourceMapper->hasErrors()) {
			$errors = $this->_resourceMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('Resource has not been updated in data base'));
			$this->_session->setFlashData('resourceEditFormValues', $postValues);
			$this->_session->setFlashData('resourceEditErrors', $errors);
			$this->redirect($baseUrl.'/admin/resource/editForm');
		}
		else {
			$this->_session->setFlashData('notice', $this->_i18n->translate('Resource has been updated in data base'));
			$this->redirect($baseUrl.'/admin/resource/list');
		}
		
	}
	
}

?>
