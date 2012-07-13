<?php

/**
 * @class ViewPdf
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ViewPdf extends ViewTemplate implements IViewTemplate {
	
	/**
	 * Silnik szablonow
	 * 
	 * @var object
	 * 
	 */ 	
	protected $_templateEngine = null;
	
	/**
	 * Html2Pdf Obiekt ptzretwarzajacy format HTML w PDF
	 * 
	 * @var object
	 * 
	 */ 	
	protected $_html2pdf = null;
	
	/*
	 * Konstruktor
	 * 
	 * @access public
	 * @param object ITemplateEngine Silnik szablonow
	 * @param object Html2Pdf
	 * @return void
	 * 
	 */ 			
	public function __construct(ITemplate $templateEngine, $html2pdf) {
		$this->_templateEngine = $templateEngine;
		$this->_html2pdf = $html2pdf;
		$this->setMimeType('application/pdf');
	}
	
	/*
	 * Dodaje zmienna o okreslonej etykiecie do tablicy zmiennych dla szablonu
	 * 
	 * @access public
	 * @param string Etykieta zmiennej
	 * @param mixed Wartosc zmiennej
	 * @return void
	 * 
	 */ 	
	public function assign($var, $value) {
		parent::assign($var, $value);
	}
	
	/*
	 * Przetwarza zmienne w szablonie, zwracajac ciag tekstowy
	 * 
	 * @access public
	 * @param Sciezka do szablonu domyslnie = null
	 * @param string Id cache
	 * @param string Id grupy szablonow
	 * @param bool Czy wyswietlic przetworzony szablon?
	 * @param int Czas zycia cache
	 * @return string
	 * 
	 */ 
	public function fetch($templatePath=null, $cacheId=null, $compileId=null, $display=false, $lifeTime=null) {
		foreach($this->_data AS $index=>$value) {
			if(is_object($value)) {
				if($value instanceof IViewHtmlHelper) {
					$this->_data[$index] = $value->fetchHtml();
				}
			}
		}
		
		// set document information
		$this->_html2pdf->SetCreator(PDF_CREATOR);
		$this->_html2pdf->SetAuthor('Cook Book');
		$this->_html2pdf->SetTitle('Cook Book');
		$this->_html2pdf->SetSubject('Cook Book');

		// set default header data
		//$this->_html2pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 061', PDF_HEADER_STRING);

		// set header and footer fonts
		$this->_html2pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->_html2pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$this->_html2pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$this->_html2pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$this->_html2pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->_html2pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		//set auto page breaks
		$this->_html2pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);		

		//set image scale factor
		$this->_html2pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$this->_html2pdf->setLanguageArray($l);
		
		// set font
		$this->_html2pdf->SetFont('dejavusans', '', 10);

		// add a page
		$this->_html2pdf->AddPage();		
		
		$html = "<html><head></head><body><div style=\"width:300px\">";
		$html .= parent::fetch($templatePath, $cacheId, $compileId, $display, $lifeTime);
		$html .= "</body></html>";
		
		// output the HTML content
		$this->_html2pdf->writeHTML($html, true, false, true, false, '');		
				
				
		// reset pointer to the last page
		$this->_html2pdf->lastPage();

		//Close and output PDF document
		$pdf = $this->_html2pdf->Output('example.pdf', 'S');
		
		return $pdf;
	}
	
	/*
	 * Zwraca obiekt rendera
	 * 
	 * @access public
	 * @return object
	 * 
	 */ 	
	public function getTemplateEngine() {
		return parent::getTemplateEngine();
	}	
	
	/*
	 * Zwraca typ MIME widoku jesli ustawiono
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getMimeType() {
		return parent::getMimeType();
	}
		
	/*
	 * Ustawia typ MIME widoku
	 * 
	 * @access public
	 * @param string Typ MIME dla widoku
	 * @return void
	 * 
	 */ 	
	public function setMimeType($mimeType) {
		parent::setMimeType($mimeType);
	}
	
	/*
	 * Zwraca sciezke do szablonu
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getTemplatePath() {
		return parent::getTemplatePath();
	}		
	
	/*
	 * Ustawia sciezke do szablonu
	 * 
	 * @access public
	 * @param string Sciezka do szablonu
	 * @return void
	 * 
	 */ 	
	public function setTemplatePath($tpl) {
		parent::setTemplatePath($tpl);
	}	
	
	/*
	 * Ustawia id szablonu
	 * 
	 * @access public
	 * @param string Id cache dla przetwarzanego szablonu
	 * @return void
	 * 
	 */ 		
	public function setCacheId($cacheId) {
		parent::setCacheId($cacheId);
	}
	
	/*
	 * Zwraca id cache przetwarzanego szablonu
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getCacheId() {
		return parent::getCacheId();
	}	

	/*
	 * Ustawia id kompilacji dla przetwarzanego szablonu
	 * 
	 * @access public
	 * @param string Id kompilacji dla przetwarzanego szablonu
	 * @return void
	 * 
	 */ 		
	public function setCompileId($compileId) {
		parent::setCompileId($compileId);
	}
	
	/*
	 * Zwraca id kompilacji przetwarzanego szablonu
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function getCompileId() {
		return parent::getCompileId();
	}
	
	/*
	 * Ustawia czy szablon po przetworzeniu ma zostac natychmiast wyswietlony
	 * 
	 * @access public
	 * @param bool True jesli wyswietlic
	 * @return void
	 * 
	 */ 		
	public function setDisplay($display) {
		parent::setDisplay($display);
	}
	
	/*
	 * Zwraca wartosc bool odp za natychmiatowe wyswietlanie szablonu po przetworzeniu
	 * 
	 * @access public
	 * @return bool
	 * 
	 */ 	
	public function getDisplay() {
		return parent::getDisplay();
	}
	
	/*
	 * Ustawia czas zycia przetwarzanego szablonu
	 * 
	 * @access public
	 * @param int Czas zycia szablonu
	 * @return void
	 * 
	 */ 		
	public function setLifeTime($lifeTime) {
		parent::setLifeTime($lifeTime);
	}
	
	/*
	 * Zwraca czas zycia przetwarzanego szablonu
	 * 
	 * @access public
	 * @return int
	 * 
	 */ 	
	public function getLifeTime() {
		return parent::getLifeTime();
	}
	
	/*
	 * Zwraca informacje tekstowa o pewnych wlasciwosciach obiektu
	 * 
	 * @access public
	 * @return string
	 * 
	 */ 	
	public function __toString() {
		$str = "";
		$str .= "View Type: " . $this->_viewType . "<br />";
		$str .= "Template Path: " . $this->_templatePath . "<br />"; 
		$str .= "Cache Id: " . $this->_cacheId . "<br />";
		$str .= "Compile Id: " . $this->_compileId . "<br />";
		$str .= "Life Time: " . $this->_lifeTime . "<br />";
		$str .= "Display: " . (int)$this->_display . "<br />"; 		 		 		 
		$str .= "Data: \n<pre>" . print_r($this->_data, true) . "</pre><br />";
		return $str;
	}
	
}

?>
