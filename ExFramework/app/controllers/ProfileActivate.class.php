<?php

class ProfileActivate extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_userMapper = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UserMapper $userMapper) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_userMapper = $userMapper;
	}
	
	public function execute() {	
		
		//pobieranie danych z zadania i sesji
		$userLogin = null;
		$userIsAuth = false;
		$userLogin = $this->_session->userLogin;
		$userIsAuth = $this->_session->userIsAuth; 
		if(!empty($userLogin) && ($userIsAuth != false)) {
			throw new ControllerException('Brak dostepu');
		}			
		
		$baseUrl = $this->_request->getBaseUrl();
		$userCode = $this->_request->get('userCode');
		if(empty($userCode)) {
			$this->_session->setFlashData('error', $this->_i18n->translate('An error has occurred'));
			$this->redirect($baseUrl.'/profile/registerForm');				
		}
		
		//pobieranie danych z modelu, praca z modelem
		$usersCollection = $this->_userMapper->getByCode($userCode);
		if(count($usersCollection) != 1) {
			$this->_session->setFlashData('error', $this->_i18n->translate('An error has occurred'));
			$this->redirect($baseUrl.'/profile/registerForm');
		}
		else {
			$user = $usersCollection[0];
			if($user->status == 1) {
				$this->_session->setFlashData('notice', $this->_i18n->translate('You have activated your account'));
				$this->redirect($baseUrl.'/profile/loginForm');				
			}
			else {
				$dateNow = date("Y-m-d H:i:s");
				$datediff = (strtotime($dateNow) - strtotime($user->registerDate)) / (60*60*24);
				if($datediff > 2) {
					$this->_userMapper->delete($user);
					$this->_session->setFlashData('error', $this->_i18n->translate('Account expired'));
					$this->redirect($baseUrl.'/profile/registerForm');
				}
				$user->status = 1;
				$this->_userMapper->save($user);
				if($this->_userMapper->hasErrors()) {
					//$e = $this->_userMapper->getErrors();
					$this->_session->setFlashData('error', $this->_i18n->translate('User has not been activated'));
					$this->redirect($baseUrl.'/profile/registerForm');
				}
				else {
					$this->_session->setFlashData('notice', $this->_i18n->translate('Your account is active from now'));
					$this->redirect($baseUrl.'/profile/loginForm');
				}
			}
		}
		
	}
	
}
?>
