<?php

/**
 * @class ControllerFront
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ControllerFront implements IController {
	
	/**
	 * Obiekt zadania
	 *
	 * @var object
	 * 
	 */	
	protected $_request = null;	
	
	/**
	 * Obiekt routera
	 *
	 * @var object
	 * 
	 */	
	protected $_router = null;
	
	/**
	 * Obiekt odpowiedzialny za odpowiedz
	 *
	 * @var object
	 * 
	 */	
	protected $_response = null;
	
	/**
	 * Obiekt Auth
	 *
	 * @var object
	 * 
	 */	
	protected $_auth = null;	
	
	/**
	 * Obiekt ACL
	 *
	 * @var object
	 * 
	 */	
	protected $_acl = null;
	
	/**
	 * Obiekt fabryczny kontrolerow akcji
	 *
	 * @var object
	 * 
	 */	
	protected $_actionControllerFactory = null;
	
	/**
	 * Wlaczenie/wylaczenie uzywania ACL
	 *
	 * @var bool
	 * 
	 */	
	protected $_useAcl = true;	
	
	/**
	 * Konstruktor 
	 * 
	 * @access public
	 * @param object Obiekt zadania
	 * @param object Obiekt odpowiedzi
	 * @param object Obiekt routera
	 * @param object Obiekt fabryczny kontrolerow akcji
	 * @param object Obiekt autentykacji
	 * @param object Obiekt ACL
	 * @param bool Wlaczenie/wylaczenie uzywania ACL
	 * 
	 */		
	public function __construct(IRequest $request, IResponse $response, IRouter $router, IFactory $actionControllerFactory, IAuth $auth, IAcl $acl, $useAcl=true) {
		$this->_request = $request;
		$this->_router = $router;
		$this->_response = $response;
		$this->_auth = $auth;
		$this->_acl = $acl;
		$this->_actionControllerFactory = $actionControllerFactory;
		$this->_useAcl = (($useAcl == 1) || ($useAcl == true)) ? true : false;
	}
	
	/**
	 * Wykonuje zadanie, wywolujac odpowiedni kontroler akcji i przekzujac uzyskane dane do obiektu odpowiedzi, oraz wywywoluje akcje tego obiektu
	 * 
	 * @access public
	 * @return void
	 * 
	 */		
	public function execute() {
		$this->_router->route();
		$area =  ($this->_router->getArea() != null) ? $this->_router->getArea() : ''; //Okreslenie nazwy aktualnego obszaru 
		$controllerName = $this->_router->getController(); //pobiera nazwe kontrolera 
		$actionName = $this->_router->getAction(); //pobiera nazwe akcji
		$actionController = $this->_actionControllerFactory->create(ucfirst($area) . ucfirst($controllerName) . ucfirst($actionName));		
		
		//-->ACL
		if($this->_useAcl == true) {
			$resource = '';
			if($area !== '') {
				$resource .= '/'.$area;
			}
			if($controllerName !== '') {
				$resource .= '/'.$controllerName;
			}
			if($actionName !== '') {
				$resource .= '/'.$actionName;
			}
			if($resource === '') {
				$resource = '/';
			}
			$userLogin = $this->_auth->getUserLogin();
			if($this->_acl->isAllowed($resource, $userLogin) == false) {
				$baseUrl = $this->_request->getBaseUrl();
				if($this->_auth->check() == false) {
					$redirectTo = $this->_acl->getLoginPagePath();
				}
				else {
					$redirectTo = $this->_acl->getErrorPagePath();				
				}
				header('Location: ' . $baseUrl.$redirectTo);
				exit(0);
			}
		}
		//<--ACL
		$actionController->preAction();
		$actionController->execute(); //wykonanie akcji kontrolera , w nim zainicjowany widok i model; widok dostanie dane z kontrolera akcji, ktory pobierze je z modelu 
		$actionController->postAction();
		
		/*
		 *Tu mozna dorobic obsluge multiactioncontroller, jesli nie ma klasy controlera szukac multiactioncontrollera i wykonac poodana akcje 
		$controller = $this->_actionControllerFactory->create(ucfirst($area) . ucfirst($controllerName));
		$controller->{$actionName}();
		*/ 
		
		$view = $actionController->getView(); //pobranie obiektu widoku z kontrolera
		if(!$view instanceof IView) {
			throw new ControllerException('Metoda getView obiektu ActionController musi zwracac obiekt impl. interfejs IView');
		}
		$layout = null;
		if($view instanceof IViewHtml) {
			if($actionController->getUseLayout() === true) { //sprawdzenie czy kontroler uzywa layoutu, jesli tak pobranie obiektu layoutu
				$layout = $view->getLayout();
				$layout->setContent($view);				
				$widgetsDefs = $actionController->getWidgetsDefinitions();
				$layout = $this->processWidgets($layout, $widgetsDefs);
			}
		}
		if(($layout !== null) && ($layout instanceof ILayout)) {
			$this->_response->setOutputView($layout); //i wyslanie do obiektu response obiektu layoutu
		}
		else {
			$this->_response->setOutputView($view); //lub obiektu widoku, jesli brak layoutu
		}
		$this->_response->sendOutput(); //wykonanie response i wyslanie danych do przegladarki
		
	}
	
	/**
	 * Wykonuje widgety, przekazujac zwrocone dane do obiektu odpowiedzi
	 * 
	 * @access public
	 * @param object Layout
	 * @oaram array Definicje widgetow
	 * @return void
	 * 
	 */	
	protected function processWidgets(ILayout $layout, $widgetsDefs) {
		if(!is_array($widgetsDefs)) {
			$widgetsDefs = array($widgetsDefs);
		}
		foreach($widgetsDefs AS $widgetName=>$widgetResource) {
			$widgetObject = $this->_actionControllerFactory->create(ucfirst($widgetName));

			//-->ACL
			if($this->_useAcl == true) {
				$resource = $widgetResource;
				$userLogin = $this->_auth->getUserLogin();
				if($this->_acl->isAllowed($resource, $userLogin) == false) {
					continue;
				}
			}
			//<--ACL
						
			if(!$widgetObject instanceof IController) {
				throw new ControllerException('Widgety i kontrolery musza implementowac interfejs IController');
			}
			$widgetObject->preAction();
			$widgetObject->execute();
			$widgetObject->postAction();
			$widgetView = $widgetObject->getView();
			if(!$widgetView instanceof IViewHtml) {
				throw new ControllerException('Widoki widgetow musza implementowac interfejs IViewHtml');
			}
			$layout->addWidget($widgetName, $widgetView);
		}
		return $layout;
	}
	
}

?>
