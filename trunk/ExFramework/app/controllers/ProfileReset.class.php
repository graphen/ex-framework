<?php

class ProfileReset extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	protected $_session = null;
	protected $_i18n = null;
	protected $_userMapper = null;
	protected $_mailer = null;
	protected $_emailFrom = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UserMapper $userMapper, IMailer $mailer, $emailFrom) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18n = $i18n;
		$this->_userMapper = $userMapper;
		$this->_mailer = $mailer;
		$this->_emailFrom = $emailFrom;
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
		
		if(empty($postValues['email']) || empty($postValues['login'])) {
			$this->_session->setFlashData('error', $this->_i18n->translate('Empty login or password field'));
			$this->redirect($baseUrl.'/profile/resetForm');
		}
		
		//pobieranie danych z modelu, praca z modelem
		$userCollection = $this->_userMapper->getByLogin($postValues['login']);
		if(count($userCollection) != 1) {
			$this->_session->setFlashData('error', $this->_i18n->translate('User does not exist'));
			$this->redirect($baseUrl.'/profile/resetForm');	
		}
		else {
			$user = $userCollection[0];
			if($user->email !== $postValues['email']) {
				$this->_session->setFlashData('error', $this->_i18n->translate('Invalid email address'));
				$this->redirect($baseUrl.'/profile/resetForm');				
			}
			else {
				$newPassword = $this->generatePassword();
				$user->password = $newPassword;
				$user->newPassword = $newPassword; //to musi byc poniewaz podczas aktualizacji sprawdzane sa pola newPassword i passwordConfirmation
				$user->passwordConfirmation = $newPassword;
				$this->_userMapper->save($user);
				if($this->_userMapper->hasErrors()) {
					$errors = $this->_userMapper->getErrors(); //tutaj podpiac i18n!!!
					$this->_session->setFlashData('error', $this->_i18n->translate('Errors while password changing'));
					$this->redirect($baseUrl.'/profile/resetForm');
				}
				
				$userEmailAddress = $user->email;
				$userRegisterSubject = $this->_i18n->translate('User registeration and menagement system');
				$userRegisterBodyHtml = $this->_i18n->translate('Your new password is') . ': <b>' . $newPassword . '</b>. ' . $this->_i18n->translate('Click ') . '<a href="' . $baseUrl.'/profile/login">' . $baseUrl . '/profile/login</a>'. $this->_i18n->translate(' and login with your new password');
				$userRegisterBodyPlain = $this->_i18n->translate('Your new password is') . ': ' . $newPassword . '. ' . $this->_i18n->translate('Click ') . $baseUrl.'/profile/login' . $this->_i18n->translate(' and login with your new password');
				
				$this->_mailer->setMailTextType('html');
				$this->_mailer->setMailCharset('utf-8');
				$this->_mailer->setPriority(4);
				$this->_mailer->setMsMailPriority('High');
				$this->_mailer->setTextPlainEncoding('quoted-printable');
				$this->_mailer->setTextHtmlEncoding('quoted-printable');
				$this->_mailer->setEndString('rn');
				$this->_mailer->setMimeInfo('This is multipart MIME message');
				$this->_mailer->setWordWrap(true);
				$this->_mailer->setSendMultipart(true);				
				$this->_mailer->setMimeMultipart('mixed');
				$this->_mailer->setAlternativeText($userRegisterBodyPlain);
				$this->_mailer->addTo($userEmailAddress);
				$this->_mailer->addFrom('<'.$this->_emailFrom.'>', 'User registeration & menagement system');
				$this->_mailer->addSubject($userRegisterSubject);
				$this->_mailer->addBody($userRegisterBodyHtml);
				$this->_mailer->send();
				
				$this->_session->setFlashData('notice', $this->_i18n->translate('You have got a new password. Check your email account'));
				$this->redirect($baseUrl.'/profile/loginForm');			
			}
		}
		
	}
	
  protected function generatePassword() {
        $chars = "abcdefghijkmnopqrstuvwxyz0123456789_";
        srand((double)microtime()*1000000);
        $password = '';
        for($i = 0; $i <= 10; $i++) {
			$number = rand() % 35;
			$password .= substr($chars, $number, 1);
		}
        return $password;
    }	
	
}
?>
