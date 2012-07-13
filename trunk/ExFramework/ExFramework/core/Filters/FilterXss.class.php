<?php

/**
 * @class FilterXss
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FilterXss implements IFilter {

	/**
	 * Strona kodowa
	 *
	 * @var string
	 * 
	 */		
	protected $_charset = 'UTF-8';
	
	/**
	 * Konstruktor
	 *
	 * @access public
	 * 
	 */			
	public function __construct() {
		//
	}

	/**
	 * Ustawienie kodowania
	 *
	 * @access public
	 * @param string Strona kodowa
	 * @return void
	 * 
	 */		
	public function setCharset($charset='UTF-8') {
		$this->_charset = $charset;
	}
	
	/**
	 * Filtruje wartosc zmiennej
	 *
	 * @access public
	 * @param mixed Wartosc poddawana filtrowaniu
	 * @param array Dodatkowe opcje
	 * @return mixed
	 * 
	 */		
	public function filter($var, $options=array()) {
		if(isset($options['charset'])) {
			$this->setCharset($options['charset']);
		}		
		if(is_array($var)) {
			$tmpArray = array();
			foreach($var as $k => $v) {
				$tmpArray[$k] = $this->filter($v);
			}
			return $tmpArray;
		}
		//Usuniecie niewidocznych znakow oprocz (09), (10), (1d)
		$invisibleChars = array('%00', '%01', '%02', '%03', '%04', '%05', '%06', '%07', '%08', '%0b', '%0c', '%0e', '%0f',
								'%10', '%11', '%12', '%13', '%14', '%15', '%16', '%17', '%18', '%19', '%1a', '%1b', '%1c', 
								'%1d', '%1e', '%1f',
								'\x00', '\x01', '\x02', '\x03', '\x04', '\x05', '\x06', '\x07', '\x08', '\x0b', '\x0c', '\x0e', '\x0f',
								'\x10', '\x11', '\x12', '\x13', '\x14', '\x15', '\x16', '\x17', '\x18', '\x19', '\x1a', '\x1b', '\x1c', 
								'\x1d', '\x1e', '\x1f'
								);
		$var = str_replace($invisibleChars, '', $var);
		//Naprawa znakow zakodowanych m.in. przez htmlentities oraz zakodowanyc reprezentacji liczbowych jesli sa takie &nbsp[znaki niewidoczne];
		$ampHash = substr(md5(time() + mt_rand(0, 100000)), 0, 10); //ochrona ciagow z tablicy _SERVER
        $var = preg_replace('/\&([a-z0-9\_]+)\=([a-z0-9\_]+)/i', $ampHash . "\\1=\\2", $var);
        $var = preg_replace('/(&\#?\w+)[\x00-\x20]+;/', "\\1;", $var);
        $var = preg_replace('/(&\#x?)([0-9A-F]+)([;]*)/i', "\\1\\2;", $var);
		$var = preg_replace('|'. $ampHash .'|', '&', $var); //przywrocenie znakow & oddzielajacych zmienne
		//Odkodowywanie znakow %xx
		$var = rawurldecode($var);
		//Konwersja do ASCII
        $var = html_entity_decode($var, ENT_COMPAT, $this->_charset);
        //usuniecie znakow niewidocznych jesli sie pojawily
		$var = str_replace($invisibleChars, '', $var);
		//usuniecie tabulatorow
		$var = str_replace("\t", ' ', $var);
		//Wywalenie atrybutow styli, mozna tam wrzucac javascript
		$var = preg_replace('/(<[^>]+[\x00-\x20\"\'\/])style[^>]*>/i', "\\1>", $var);		
		//Wywalenie niebezpiecznych protokolow
		$var = str_replace(array('document.write', 'document.cookie', '.parentNode', '.innerHTML', 'window.location', '-moz-binding'), '', $var);
		$var = preg_replace('/javascript\s*:/', '', $var);
		$var = preg_replace('/"expression\s*(\(|&\#40;)/', '', $var);
		$var = preg_replace('/Redirect\s+302/', '', $var);
		$var = preg_replace('/vbscript\s*:/', '', $var);
		$var = preg_replace('/<!--/', '&lt;!--', $var);
		$var = preg_replace('/-->/', '--&gt;', $var);
		$var = preg_replace('/<!\[CDATA\[/', '&lt;![CDATA[', $var);
		//Usuniecie tagow PHP
		$var = preg_replace('/<\?(php)/i', "&lt;?\\1", $var);
		//Usuniecie niebezpiecznego kodu z obrebu atrybutow tagow
        $var = preg_replace('/([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:/i', "\\1=\\2nojavascript...", $var);
        $var = preg_replace('/([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:/i', "\\1=\\2novbscript...", $var);
		$var = preg_replace('/([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*-moz-binding[\x00-\x20]*:/i', "\\1=\\2nomozbinding...", $var);
		$var = preg_replace('/([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*data[\x00-\x20]*:/i', "\\1=\\2nodata...", $var);
		$var = preg_replace('/([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*a[\x00-\x20]*l[\x00-\x20]*e[\x00-\x20]*r[\x00-\x20]*t/i', "\\1=\\2noalert...", $var);
		$var = preg_replace('/([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*c[\x00-\x20]*h[\x00-\x20]*a[\x00-\x20]*r[\x00-\x20]*s[\x00-\x20]*e[\x00-\x20]*t[\x00-\x20]*\=/i', "\\1=\\2nocharset...", $var);
		$var = preg_replace('/([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*w[\x00-\x20]*i[\x00-\x20]*n[\x00-\x20]*d[\x00-\x20]*o[\x00-\x20]*w[\x00-\x20]*\./i', "\\1=\\2nowindow...", $var);
		$var = preg_replace('/([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*d[\x00-\x20]*o[\x00-\x20]*c[\x00-\x20]*u[\x00-\x20]*m[\x00-\x20]*e[\x00-\x20]*n[\x00-\x20]*t[\x00-\x20]*\./i', "\\1=\\2nodocument...", $var);
		$var = preg_replace('/([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*c[\x00-\x20]*o[\x00-\x20]*o[\x00-\x20]*k[\x00-\x20]*i[\x00-\x20]*e[\x00-\x20]*\./i', "\\1=\\2nocookie...", $var);
		$var = preg_replace('/([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t/i', "\\1=\\2noscript...", $var);
		//Usuniecie atrybutow rozpoczynajacych sie od on lub xmlns
        $string = preg_replace('/(<[^>]+[\x00-\x20\"\'\/])(on|xmlns)[^>]*>/i', "\\1>", $var); //pozniej dopuscic obrazki
		//Wywalenie niechcianych tagow
		$unwantedTags = array('applet', 'body', 'meta', 'xml', 'blink', 'link', 'style', 'script', 
								'html', 'head', 'form', 'input', 'isindex', 'textarea', 'video',
								'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 
								'bgsound', 'title', 'basefont', 'base', 'style', 'plaintext', );
		foreach($unwantedTags AS $tag) {
            $var = preg_replace('/(<\/*'. $tag .'[^>]*>)/i','' ,$var);			
		}
		//Wywalenie funkcji PHP
		$unwantedPhp = array('cmd', 'passthru', 'eval', 'exec', 'system', 'fopen', 'fsockopen', 
							'file_get_contents', 'readfile', 'file', 'unlink');
		foreach($unwantedPhp AS $fnc) {
            $var = preg_replace('|' . $fnc .'\s*\(.*?\)|i','' ,$var);			
		}
		return $var;
	}
	
}

?>
