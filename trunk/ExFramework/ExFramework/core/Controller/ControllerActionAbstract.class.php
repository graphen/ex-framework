<?php

/**
 * @class ControllerActionAbstract
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class ControllerActionAbstract implements IController {
	
	/**
	 * Nazwy klas widgetow
	 *
	 * @var array
	 * 
	 */	
	protected $_widgetsDefinitions = array(); //nazwy widgetow do wykonania razem z ta akcja
	
	/**
	 * Nazwa szablonu
	 *
	 * @var string
	 * 
	 */	
	protected $_templateName = null;
	
	/**
	 * Nazwa szablonu layoutu
	 *
	 * @var string
	 * 
	 */	
	protected $_layoutName = null;
	
	/**
	 * Wlaczenie/wylaczenie uzywania layoutu
	 *
	 * @var bool
	 * 
	 */		
	protected $_useLayout = true;
	
	/**
	 * Obiekty modeli
	 *
	 * @var array
	 * 
	 */	
	protected $_models = array();
	
	/**
	 * Obiekt widoku
	 *
	 * @var object
	 * 
	 */		
	protected $_view = null;
	
	/**
	 * Przekierowuje zadanie na podany URL
	 * 
	 * @access public
	 * @param string URL
	 * @return void
	 * 
	 */	
	public function redirect($url) {
		header("Location: $url");	
		exit(0);
	}
	
	/**
	 * Przekierowuje zadanie na podany URL po czasie  okreslonym w sekundach
	 * 
	 * @access public
	 * @param string URL
	 * @param int Liczba sekund
	 * @return void
	 * 
	 */		
	public function refresh($url, $time=1) {	
		header("Refresh: $time; URL=$url");
		exit(0);
	}
	
	/**
	 * Ustawia tablice nazw klas widgetow
	 * 
	 * @access public
	 * @param array Tablica nazw klas widgetow
	 * @return void
	 * 
	 */		
	public function setWidgetsDefinitions(Array $widgetsDefs) {
		$this->_widgetsDefinitions = $widgetsDefs;
	}	
	
	/**
	 * dodaje nazwe klasy widgetu do tablicy
	 * 
	 * @access public
	 * @param string NAzwa klasy widgetu
	 * @return void
	 * 
	 */		
	public function addWidgetDefinition($widgetDef) {
		$this->_widgetsDefinitions[] = (string)$widgetDef;
	}
	
	/**
	 * Zwraca tablice nazw klas widgetow
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function getWidgetsDefinitions() {
		return $this->_widgetsDefinitions;
	}
	
	/**
	 * Ustawia nazwe szablonu
	 * 
	 * @access public
	 * @param string Nazwa szablonu
	 * @return void
	 * 
	 */	
	public function setTemplateName($templateName) {
		$this->_templateName = (string)$templateName;
	}
	
	/**
	 * Ustawia nazwe szablonu layoutu
	 * 
	 * @access public
	 * @param string Nazwa szablonu layoutu
	 * @return void
	 * 
	 */			
	public function setLayoutName($layoutName) {
		$this->_layoutName = (string)$layoutName;
	}
	
	/**
	 * Wlacza/wylaca uzywanie layoutu
	 * 
	 * @access public
	 * @param bool Wlaczenie/wylaczenie layoutu
	 * @return void
	 * 
	 */		
	public function setUseLayout($useLayout) {
		$this->_useLayout = (bool)$useLayout;
	}
	
	/**
	 * Zwraca nazwe szablonu
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getTemplateName() {
		return $this->_templateName;
	}
	
	/**
	 * Zwraca nazwe szablonu layoutu
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getLayoutName() {
		return $this->_layoutName;
	}
	
	/**
	 * Odpowiada czy uzywany jest layout
	 * 
	 * @access public
	 * @return bool
	 * 
	 */		
	public function getUseLayout() {
		return $this->_useLayout;
	}
	
	/**
	 * Zwraca obiekt widoku
	 * 
	 * @access public
	 * @return object
	 * 
	 */		
	public function getView() {
		return $this->_view;
	}
	
	/**
	 * Zwraca obiekt modelu
	 * 
	 * @access public
	 * @param string Id obiektu
	 * @return object
	 * 
	 */		
	public function getModel($modelId) {
		if(isset($this->_models[$modelId])) {
			return $this->_models[$modelId];
		}
		return null;
	}
	
	/**
	 * Zwraca tablice obiektow modeli
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function getModels() {
		return $this->_models;
	}
	
	/**
	 * Wykonuje zadania przed wykonaniem wlasciwej akcji
	 * 
	 * @access public
	 * @return mixed
	 * 
	 */	
	public function preAction() {
		//
	}
	
	/**
	 * Wykonuje zadania po wykonaniu wlasciwej akcji
	 * 
	 * @access public
	 * @return mixed
	 * 
	 */			
	public function postAction() {
		//
	}
	
	/**
	 * Wykonuje przekierowanie na zadana strone z ustawieniem komunikatu w sesji
	 * 
	 * @access public
	 * @param object Obiekt zadania
	 * @param object Obiekt sesji
	 * @param string URL, na ktory nastapi przekierowanie
	 * @param string Identyfikator komunikatu
	 * @param string Tresc komunikatu
	 * @return void
	 * 
	 */		
	protected function redirectToUrl(IRequest $request, ISession $session, $url=null, $flashDataKey=null, $flashDataMessage=null) {
		if(($flashDataKey != null) && ($flashDataMessage != null)) {
			$session->setFlashData($flashDataKey, $flashDataMessage);
		}
		if($url == null) {
			$url = $session->userLastUrl;
		}
		$session->writeClose();
		if(!empty($url)) {
			$this->redirect($url);
		}
		else {
			$baseUrl = $request->getBaseUrl();
			$this->redirect($baseUrl);
		}			
	}	
	
}

?>
