<?php

class ProfileRegister extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_userMapper = null;
	protected $_groupMapper = null;
	protected $_mailer = null;
	protected $_mailFrom = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UserMapper $userMapper, GroupMapper $groupMapper, IMailer $mailer, $mailFrom) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_userMapper = $userMapper;
		$this->_groupMapper = $groupMapper;
		$this->_mailer = $mailer;
		$this->_mailFrom = $mailFrom;
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();
		$postValues = $this->_request->post();
		
		$userLogin = null;
		$userIsAuth = false;
		$userLogin = $this->_session->userLogin;
		$userIsAuth = $this->_session->userIsAuth; 
		if(!empty($userLogin) && ($userIsAuth != false)) {
			throw new ControllerException('Brak dostepu');
		}		
		
		//pobieranie danych z modelu, praca z modelem
		$user = $this->_userMapper->create($postValues);
		$userCode = $this->generateCode();
		$user->code = $userCode;
		$user->status = 0;
		$user->registerDate = date("Y-m-d H:i:s");
		$groupName = 'local_user';
		$groupsCollection = $this->_groupMapper->getByName($groupName);
		$group = $groupsCollection[0];
		$result = $this->_userMapper->save($user, $group);
		
		//sprawdzanie poprawnosci zapisania, przekierowanie w reakcji za wynik
		$errors = array();
		if($this->_userMapper->hasErrors()) {
			$errors = $this->_userMapper->getErrors(); //tutaj podpiac i18n!!!
			$this->_session->setFlashData('error', $this->_i18n->translate('User has not been registered'));
			$this->_session->setFlashData('profileRegisterFormValues', $postValues);
			$this->_session->setFlashData('profileRegisterErrors', $errors);
			$this->redirect($baseUrl.'/profile/registerForm');
		}
		else {
			$userEmailAddress = $user->email;
			$userRegisterSubject = $this->_i18n->translate('User registeration and menagement system');
			$userRegisterBodyHtml = $this->_i18n->translate('Click ') . '<a href="' . $baseUrl.'/profile/activate/userCode/'. $userCode.'">'. $userCode .'</a>'. $this->_i18n->translate(' to end registration process');
			$userRegisterBodyPlain = $this->_i18n->translate('Click ') . $baseUrl.'/profile/activate/userCode/'. $userCode. $this->_i18n->translate(' to end registration process');
			
			$this->_mailer->setMailTextType('html');
			$this->_mailer->setMailCharset('utf-8');
			$this->_mailer->setPriority(4);
			$this->_mailer->setMsMailPriority('High');
			$this->_mailer->setTextPlainEncoding('quoted-printable');
			$this->_mailer->setTextHtmlEncoding('quoted-printable');
			$this->_mailer->setEndString('rn');
			$this->_mailer->setMimeInfo('This is multipart MIME message');
			$this->_mailer->setWordWrap(true);
			$this->_mailer->setMimeMultipart('mixed');
			$this->_mailer->setSendMultipart(true);
			$this->_mailer->setAlternativeText($userRegisterBodyPlain);
			$this->_mailer->addTo($userEmailAddress);
			$this->_mailer->addFrom('<'.$this->_mailFrom.'>', 'User registeration & menagement system');
			$this->_mailer->addSubject($userRegisterSubject);
			$this->_mailer->addBody($userRegisterBodyHtml);
			$this->_mailer->send();
			
			$this->_session->setFlashData('notice', $this->_i18n->translate('Your acount is registered but not active. An e-mail has been send to you. You have to activate your acount. Follow email instructions'));
			$this->redirect($baseUrl.'/profile/loginForm');
		}
		
	}
	
	protected function generateCode() {
        srand((double)microtime()*1000000);
		$random = rand(0,1000000);
		return md5(time().$random);
	}
	
}

?>
