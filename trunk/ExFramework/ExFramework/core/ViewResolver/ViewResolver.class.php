<?php

/**
 * @class ViewResolver
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ViewResolver implements IViewResolver {
	
	/**
	 * Obiekt fabryczny widokow
	 *
	 * @var object
	 * 
	 */		
	protected $_viewFactory = null;
	
	/**
	 * Obiekt zadania
	 *
	 * @var object
	 * 
	 */		
	protected $_request = null;
	
	/**
	 * Rozszerzenie plikow szablonow
	 *
	 * @var string
	 * 
	 */		
	protected $_templateExtension = '.tpl';	
	
	/**
	 * Sciezka do katalogu aplikacji
	 *
	 * @var string
	 * 
	 */		
	protected $_appPath = null;	

	
	/**
	 * Domyslny format widoku
	 *
	 * @var string
	 * 
	 */		
	protected $_defaultViewFormat = 'html';	
	
	/**
	 * Konstruktor 
	 * 
	 * @access public
	 * @param object Obiekt fabryki widokow
	 * @param object Obiekt zadania
	 * @param string Sciezka do katalogu aplikacji
	 * @param string Rozszerzenie dla plikow szablonow i layout'ow
	 * @param string Domyslny format dla widokow
	 * 
	 */	
	public function __construct(IFactory $viewFactory, IRequest $request, $appPath, $templateExtension='.tpl', $defaultViewFormat='html') {
		$this->_viewFactory = $viewFactory;
		$this->_request = $request;
		$this->_appPath = (string)$appPath;
		if(!empty($templateExtension)) {
			$this->_templateExtension = (string)$templateExtension;
		}
		if(!empty($defaultViewFormat)) {
			$this->_defaultViewFormat = (string)$defaultViewFormat;
		}
	}
	
	/**
	 * Zwraca obiekt widoku na podstawie danych z zadania
	 * 
	 * @access public
	 * @param string Nazwa szablonu
	 * @param string Nazwa layoutu
	 * @param bool Czy uzyc layoutu
	 * @return object
	 * 
	 */		
	public function resolve($templateName=null, $layoutName=null, $useLayout=false) {
		$viewFormat = $this->_request->get('format'); //Okreslenie formatu widoku
		$area =  $this->_request->get('ar'); //Okreslenie nazwy aktualnego obszaru 
		if($viewFormat === null) {
			$viewFormat = $this->_defaultViewFormat; //ustawienie domyslnego typu widoku w przypadku nie rozpoznania go
		}
		$view = $this->_viewFactory->create($this->getViewObjectName($viewFormat)); //utworzenie obiektu widoku
		
		if($templateName != null) { //jesli podano nazwe szablonu i moze layoutu, probujemy znalesc ich pliki
			
			if($view instanceof IViewTemplate) { //Jesli widok implementuje IViewTemplate, poszukiwana jest sciezka do pliku szablonu

				if($area !== null) { //jesli podano nazwe obszaru probuje tam szukac szablonu
					$viewDirPath = rtrim($this->_appPath, '/') . '/views/' . $area . '/';
					$templateFilePath = $viewDirPath . $templateName . $this->_templateExtension; /////////przemyslec nazewnictwo szablonow
					if(file_exists($templateFilePath)) {
						$view->setTemplatePath('file:'.$templateFilePath);
					}
					//else { //jesli nie znaleziono szablonu zglaszam wyjatek, w koncu spodziwano sie jego obecnosci w przeszukiwanych sciezkach 
						//$viewDirPath = rtrim($this->_appPath, '/') . '/views/';
						//$templateFilePath = $viewDirPath . $templateName . $this->_templateExtension;
						//if(file_exists($templateFilePath)) {
							//$view->setTemplatePath('file:'.$templateFilePath);
						//}
						//else { //jesli nie znaleziono szablonu zglaszam wyjatek, w koncu spodziwano sie jego obecnosci w przeszukiwanych sciezkach 
							//throw new ViewResolverException('Szablon o podanej nazwie nie istnieje');
						//}
					//}					
				}
				else {
					$viewDirPath = rtrim($this->_appPath, '/') . '/views/';
					$templateFilePath = $viewDirPath . $templateName . $this->_templateExtension;
					if(file_exists($templateFilePath)) {
						$view->setTemplatePath('file:'.$templateFilePath);
					}
					else { //jesli nie znaleziono szablonu zglaszam wyjatek, w koncu spodziwano sie jego obecnosci w przeszukiwanych sciezkach 
						throw new ViewResolverException('Szablon o podanej nazwie nie istnieje');
					}					
				}
				

				if($layoutName != null) {
					if($view instanceof IViewHtml) { //jesli obiekt widoku implementuje int. ViewHtml, to sprawdzam czy uzywa layoutu i ewentualnie jesli layout istnieje dolaczam go
						if($useLayout == true) { //jesli widok ma mozliwosc uzycia layoutu i zostalo to wlaczone
							$layout = $view->getLayout(); //pobieram  obiekt layoutu z widoku, jest to singleton
							$layoutExists = false; 
							if($area !== null) {
								$layoutDirPath = rtrim($this->_appPath, '/') . '/layouts/' . $area . '/';
								$layoutFilePath = $layoutDirPath . $layoutName . $this->_templateExtension;
								if(file_exists($layoutFilePath)) {
									$layout->setLayoutPath('file:'.$layoutFilePath);
									$layoutExists = true;
								}
								else {
									throw new ViewResolverException('Szablon layoutu o podanej nazwie nie istnieje');
								}									
							}
							//if(($area == null) || ($layoutExists == false)) {
							else {
								$layoutDirPath = rtrim($this->_appPath, '/') . '/layouts/';
								$layoutFilePath = $layoutDirPath . $layoutName . $this->_templateExtension;
								if(file_exists($layoutFilePath)) {
									$layout->setLayoutPath('file:'.$layoutFilePath);
									$layoutExists = true;
								}
								else {
									throw new ViewResolverException('Szablon layoutu o podanej nazwie nie istnieje');
								}					
							}
						} //view doesnt use layout 
					} //view doesnt impl IViewHtml
				} //layoutName == null	
						
			} //view doesnt impl. IViewTemplate
			
		} //templateName == null
		return $view;
	}
	
	/**
	 * Tworzy i zwraca nazwe obiektu widoku
	 * 
	 * @access protected
	 * @param string Nazwa widoku, format widoku
	 * @return string
	 * 
	 */		
	protected function getViewObjectName($viewFormat) {
		return 'View' . ucfirst($viewFormat);
	}
	
}

?>
