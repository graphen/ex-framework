<?php

class ActionResolverException extends AppException {
	
	public function __construct($message, $code=0, Exception $e=null) {
		parent::__construct($message, $code, $e);
	}
	
}

/**
 * @interface IActionControllerResolver
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IActionControllerResolver {
	public function resolve();
}

/**
 * @class ActionControllerResolver
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ActionControllerResolver implements IActionControllerResolver {
	
	/**
	 * Obiekt fabryczny kontrolerow akcji
	 *
	 * @var object
	 * 
	 */		
	protected $_actionFactory = null;
	
	/**
	 * Obiekt routera
	 *
	 * @var object
	 * 
	 */		
	protected $_router = null;
	
	/**
	 * Obiekt zadania
	 *
	 * @var object
	 * 
	 */		
	protected $_request = null;
	
	/**
	 * Konstruktor 
	 * 
	 * @access public
	 * @param object Obiekt fabryki kontrolerow akcji
	 * @param object Obiekt zadania
	 * @param object Obiekt routera 
	 * 
	 */	
	public function __construct(IFactory $actionFactory, IRequest $request, IRouter $router) {
		$this->_viewFactory = $viewFactory;
		$this->_request = $request;
		$this->_router = $router;
	}
	
	/**
	 * Zwraca obiekt kontrolera akcji na podstawie danych z zadania/routera
	 * 
	 * @access public
	 * @return object
	 * 
	 */		
	public function resolve() {
		$area =  $this->_router->getArea(); //Okreslenie nazwy aktualnego obszaru 
		$controllerName = $this->_router->getController(); //pobiera nazwe kontrolera ////////////dodac obsluge wyjatku kiedy kontroler podany nie istnieje 404
		$actionName = $this->_router->getAction(); //pobiera nazwe akcji
		$controller = $this->_controllerFactory->create(ucfirst($area) . ucfirst($controllerName) . ucfirst($actionName));
		return $controller;
	}
		
	
}

?>
