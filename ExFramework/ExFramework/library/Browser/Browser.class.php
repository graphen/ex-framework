<?php

/**
 * @class Browser
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Browser implements IBrowser {

	/**
	 * Surowy ciag identyfikujacy przegladarke
	 *
	 * @var string
	 */	
	protected $_clientUserAgentString = '';
	
	/**
	 * System operacyjny klienta
	 *
	 * @var string
	 */	
	protected $_clientOS = '';

	
	/**
	 * Nazwa przegladarki klienta + numer wersji
	 *
	 * @var string
	 */			
	protected $_clientUserAgentNameVer = '';
	
	/**
	 * Krotka nazwa przegladarki (ID)
	 *
	 * @var string
	 */		
	protected $_clientUserAgentId = '';
	
	
	/**
	 * Nazwa przegladarki
	 *
	 * @var string
	 */		
	protected $_clientUserAgentName = '';
	
	
	/**
	 * Wersja przegladarki
	 *
	 * @var string
	 */		
	protected $_clientUserAgentVersion = '';
	
	
	/**
	 * Numer glowny wersji przeladarki (x.)
	 *
	 * @var string
	 */		
	protected $_clientUserAgentMajorVersion = '';
	
	
	/**
	 * Numer poboczny wersji przegladarki (.x)
	 *
	 * @var string
	 */		
	protected $_clientUserAgentMinorVersion = '';
	
	
	/**
	 * Typ klienta
	 *
	 * @var string
	 */			
	protected $_clientUserAgentType = '';
	
	
	/**
	 * Czy klientem jest przegladarka //true jesli tak
	 *
	 * @var bool
	 */		
	protected $_isBrowser = false;
	
	
	/**
	 * Czy klientem jest robot sieciowy, lub inne narzedzie //true jesli tak
	 *
	 * @var bool
	 */		
	protected $_isRobot = false;
	
	
	/**
	 * Czy dostep jest z telefonu komorkowego
	 *
	 * @var bool
	 */		
	protected $_isMobile = false;
	
	
	/**
	 * Nazwa identyfikujaca urzadzenie telefoniczne
	 *
	 * @var string
	 */		
	protected $_clientMobileDevice = '';
	
	
	/**
	 * Jezyki akceptowane przez klienta
	 *
	 * @var array
	 */		
	protected $_clientUserAcceptedLanguages = array();
	
	
	/**
	 * Pierwszy z powyzszych jezykow, moze podstawowy
	 *
	 * @var string
	 */		
	protected $_clientLanguage = '';
	
	
	/**
	 * Strony kodowe akteptowane przez klienta
	 *
	 * @var array
	 */		
	protected $_clientUserAcceptedCharsets = array();
	
	
	/**
	 * Strona kodowa klienta
	 *
	 * @var string
	 */		
	protected $_clientCharset = '';	
	
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * 
	 */	
	public function __construct() {
		$this->resolveClientUserAgent();
		if($this->_clientUserAgentString != '') {
			$this->resolveClientOS();
			$this->resolveClientBrowser();
			$this->resolveMobile();
		}
		$this->resolveClientLanguage();
		$this->resolveClientCharset();
	}
	
	// Metody publiczne
	
	/**
	 * Wypisuje zawartosci pol obiektu
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function __toString() {
		$str = "";
		$str .= "HTTP_USER_AGENT: " . $this->_clientUserAgentString . "<br />\n";
		$str .= "OS: " . $this->_clientOS . "<br />\n";
		$str .= "User Agent Name + Version: " . $this->_clientUserAgentNameVer . "<br />\n";
		$str .= "User Agent Id: " . $this->_clientUserAgentId . "<br />\n";
		$str .= "User Agent Name: " . $this->_clientUserAgentName . "<br />\n";
		$str .= "User Agent Version: " . $this->_clientUserAgentVersion . "<br />\n";
		$str .= "User Agent Major Version: " . $this->_clientUserAgentMajorVersion . "<br />\n";
		$str .= "User Agent Minor Version: " . $this->_clientUserAgentMinorVersion . "<br />\n";
		$str .= "User Agent Type: " . $this->_clientUserAgentType . "<br />\n";	
		$mobile = ($this->_isMobile === true) ? "YES" : "NO";
		$str .= "Is Mobile: " . $mobile . "<br />\n";	
		$browser = ($this->_isBrowser === true) ? "YES" : "NO";
		$str .= "Is Browser: " . $browser . "<br />\n";	
		$robot = ($this->_isRobot === true) ? "YES" : "NO";
		$str .= "Is Robot: " . $robot . "<br />\n";	
		$str .= "Mobile Device: " . $this->_clientMobileDevice . "<br />\n";	
		$str .= "Client Language: " . $this->_clientLanguage . "<br />\n";			
		$str .= "Client Charset: " . $this->_clientCharset . "<br />\n";	
		return $str;
	}
	
	/**
	 * Zwraca surowy ciag identyfikujacy przegladarke
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getClientUserAgentString() {
		return $this->_clientUserAgentString;
	}
	
	/**
	 * Zwraca system operacyjny klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientOS() {
		return $this->_clientOS;
	}	
	
	/**
	 * Zwraca nazwe+wersje przegladarki klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */
	public function getClientUserAgentNameVer() {
		return $this->_clientUserAgentNameVer;
	}
	
	/**
	 * Zwraca nazwe przegladarki klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientUserAgentName() {
		return $this->_clientUserAgentName;
	}
	
	/**
	 * Zwraca wersje przegladarki klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientUserAgentVersion() {
		return $this->_clientUserAgentVersion;
	}
	
	/**
	 * Zwraca numer glowny przegladarki klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientUserAgentMajorVersion() {
		return $this->_clientUserAgentMajorVersion;
	}
	
	/**
	 * Zwraca numer poboczny przegladarki klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientUserAgentMinorVersion() {
		return $this->_clientUserAgentMinorVersion;
	}
	
	/**
	 * Zwraca nazwe urzadzenia telefonicznego
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientMobileDevice() {
		return $this->_clientMobileDevice;
	}
	
	/**
	 * Zwraca tablice akceptowanych jezykow
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getClientUserAgentAcceptedLanguages() {
		return $this->_clientUserAcceptedLanguages;
	}
	
	/**
	 * Zwraca mozliwy podstawowy jezyk klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientLanguage() {
		return $this->_clientLanguage;
	}
	
	/**
	 * Zwraca tablice akceptowanych stron kodowych klienta
	 * 
	 * @access public
	 * @return array
	 * 
	 */	
	public function getClientUserAgentAcceptedCharsets() {
		return $this->_clientUserAcceptedCharsets;
	}
	
	/**
	 * Zwraca mozliwa glowna strone kodowa klienta
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getClientCharset() {
		return $this->_clientCharset;
	}		
	
	/**
	 * Zwraca adres strony z ktorej przybyl klient
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getReferer() {
		if (isset($_SERVER['HTTP_REFERER']) && (!empty($_SERVER['HTTP_REFERER']))) {
			return trim($_SERVER['HTTP_REFERER']);
		}
		return '';
	}
	
	/**
	 * Sprawdza czy klient to przegladarka
	 * 
	 * @access public
	 * @return bool
	 * 
	 */	
	public function isBrowser() {
		return $this->_isBrowser;
	}
	
	/**
	 * Sprawdza czy klient to robot
	 * 
	 * @access public
	 * @return bool
	 * 
	 */	
	public function isRobot() {
		return $this->_isRobot;
	}
	
	/**
	 * Sprawdza czy klient to telefon
	 * 
	 * @access public
	 * @return bool
	 * 
	 */	
	public function isMobile() {
		return $this->_isMobile;
	}
	
	/**
	 * Sprawdza czy podany jezyk jest akceptowany przez klienta
	 * 
	 * @access public
	 * @param string Dwuliterowy skrot jezyka
	 * @return bool
	 * 
	 */	
	public function isAcceptedLanguage($language='pl') {
		foreach($this->_clientUserAcceptedLanguages AS $lang) {
			if(preg_match('/'. strtolower($language) .'/', strtolower($lang))) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Sprawdza czy podana strona kodowa jest akceptowana przez klienta
	 * 
	 * @access public
	 * @param string Kodowanie klienta
	 * @return bool
	 * 
	 */
	public function isAcceptedCharset($charset='iso8859-2') {
		if(in_array(strtolower($charset), $this->_clientUserAcceptedCharsets)) {
			return true;
		}
		return false;
	}
	
	/**
	 * Sprawdza czy klient przybyl z innej strony internetowej
	 * 
	 * @access public
	 * @return bool
	 * 
	 */	
	public function isReferer() {
		if (isset($_SERVER['HTTP_REFERER']) && (!empty($_SERVER['HTTP_REFERER']))) {
			return true;
		}
		return false;		
	}
	
	// Metody prywatne i chronione
	
	/**
	 * Ustawia surowy ciag przeslany przez klienta
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function resolveClientUserAgent() {
		if (isset($_SERVER['HTTP_USER_AGENT']) && (!empty($_SERVER['HTTP_USER_AGENT']))) {
			$this->_clientUserAgentString = strtolower(trim($_SERVER['HTTP_USER_AGENT']));
		}
	}	
	
	/**
	 * Rozwiazuje i ustawia nazwe systemu operacyjnego klienta
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function resolveClientOS() {
		$osArray = array(
			"/windows nt 6\.1|windows 7/" => "Windows 7",
			"/windows nt 6|windows vista|vista/" => "Windows Vista",		
			"/windows nt 5\.2|windows 2003/" => "Windows 2003",			
			"/windows nt 5\.1|windows xp/" => "Windows XP",			
			"/windows nt 5\.0|windows 2000/" => "Windows 2000",	
			"/windows nt 4\.1|winnt4.1/" => "Windows NT 4.1",
			"/windows nt 4\.0|winnt4.0| winnt 4.0/" => "Windows NT 4.0",			
			"/windows nt|winnt/" => "Windows NT",
			"/palmsource/" => "Palm Source",
			"/palmos|elaine|palm/"	=>	"PalmOS",			
			"/windows me|winme/" => "Windows ME",				
			"/windows 98|win98/" => "Windows 98",										
			"/windows 95|win95/" => "Windows 95",
			"/windows 9x|win9x/" => "Windows 9x",
			"/windows[ ]*ce|wince/" => "Windows CE",	
			"/windows phone os/" => "Windows Phone OS",		
			"/windows mobile/" => "Windows Mobile",	
			"/dos/" => "DOS",	
			"/windows/" => "Windows",
			"/debian/" => "Debian",			
			"/ubuntu/" => "Ubuntu",
			"/kubuntu/" => "Kubuntu",
			"/xubuntu/" => "Xubuntu",
			"/sidux/" => "Sidux",
			"/mandriva/" => "Mandriva",
			"/opensuse/" => "OpenSUSE",
			"/suse/" => "SUSE",
			"/fedora/" => "Fedora",
			"/redhat/" => "RedHat",
			"/slackware/" => "Slackware",
			"/xandros/" => "Xandros",
			"/slax/" => "Slax",
			"/gentoo/" => "Gentoo",
			"/mint/" => "Mint",
			"/sabayon/" => "Sabayon",
			"/kanotix/" => "Kanotix",
			"/mepis/" => "Mepis",
			"/android/" => "Android",
			"/freebsd/" => "FreeBSD",			
			"/openbsd/" => "OpenBSD",	
			"/netbsd/" => "NetBSD",
			"/bsdi/" => "BSDi",	
			"/bsd/" => "BSD",
			"/iphone[ ]*os[ ]*2/" => "Iphone 2.x",
			"/iphone[ ]*os[ ]*3/" => "Iphone 3.x",																
			"/iphone[ ]*os[ ]*4/" => "Iphone 4.x",
			"/os x/" => "Mac OS X",
			"/ppc mac/" => "Power PC Mac",
			"/ppc|powerpc/" => "Macintosh",
			"/beos/" => "BeOS",
			"/os\/2|os 2|os2/" => "OS2",
			"/sunos 5/" => "Sun 5",			
			"/sunos 4/" => "Sun 4",
			"/sunos/" => "Sun OS",
			"/solaris/" => "Solaris",			
			"/irix 6/" => "Irix 6",
			"/irix 5/" => "Irix 5",
			"/irix 4/" => "Irix 4",	
			"/irix/" => "Irix",	
			"/aix 4/" => "Aix 4",							
			"/aix 3/" => "Aix 3",				
			"/aix 2/" => "Aix 2",				
			"/aix 1/"=> "Aix 1",					
			"/aix/" => "Aix",	
			"/hp-ux(\.)*10/" => "HP-UX 10",		
			"/hp-ux(\.)*9/" => "HP-UX 9",		
			"/hp-ux/" => "HP-UX",			
			"/hpux/" => "HP-UX",	
			"/unix-system-v/" => "Unixware",
			"/reliantunix/" => "Reliant Unix",
			"/sinix/" => "Sinix",
			"/mpras/" => "Mpras Unix",			
			"/vax|openvms/" => "VMS",
			"/scounix_sv/" => "SCO",
			"/dec|osfl|alphaserver|ultrix|alphastation|osf/" => "DEC OSF",												
			"/apachebench/" => "Apache Bench",		
			"/blackberry/" => "Blackberry",
			"/epoc/" => "Epoc",
			"/cpu os/" => "Cpu OS",
			"/webos/" => "WebOS",
			"/symbian|series60|symbos/" => "Symbian",
			"/webtv/" => "WebTV",
			"/unix/" => "Unix",
			"/linux armv/" => "Linux ARMV",
			"/linux|x11|x 11/" => "Linux",			
			"/gnu/" => "GNU/Linux",
			"/cygwin/" => "Cygwin",
			"/darwin/" => "Darwin",
			"/win/" => "Windows",
			"/macintosh/" => "Macintosh",	
			"/mac os/" => "Mac OS",					
			"/apple/" => "Macintosh"			
		);
		foreach($osArray as $key => $os) {
			if(preg_match("$key", $this->_clientUserAgentString)) {
				$this->_clientOS = $os;
				break;
			}
			else {
				$this->_clientOS = "Unknown OS";			
			}
		}
	}
	
	/**
	 * Rozwiazuje i ustawia nazwe przegladarki lub robota, numer wersji itp
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function resolveClientBrowser() {
		/*przegladarki*/	
		$browsersList = array(	
							array('opera mini', 'Opera  Mini', 'browser'),//Opera
							array('opera mobile', 'Opera  Mobile', 'browser'),//Opera
							array('opera mobi', 'Opera  Mobi', 'browser'),//Opera							
							array('opera', 'Opera', 'browser'),//Opera
							array('konqueror', 'Konqueror', 'browser'),//Konqueror
							array('webkit', 'Webkit', 'browser'),
							array('gecko', 'Gecko', 'browser'),							
							array('icab', 'Icab', 'browser'),//MacOS
							array('omniweb', 'Omniweb', 'browser'),//MacOS
							array('vms_mosaic', 'VMS_Mosaic', 'browser'),//MacOS
							array('crazy-browser', 'Crazy-Browser IE', 'browser'),
							array('galeon', 'Galeon', 'browser'),				
							array('amaya', 'Amaya', 'browser'),
							array('netsurf', 'Netsurf', 'browser'),
							array('hotjava', 'HotJava', 'browser'),
							array('webtv', 'WebTV', 'textbrowser'),
							array('internet explorer', 'Internet Explorer', 'browser'),//IE							
							array('msie', 'Internet Explorer', 'browser'),//IE				
							array('mobilexplorer', 'Mobile Explorer', 'browser'),							
							array('avant browser', 'Avant Browser', 'browser'),//IE	
							array('openwave', 'Open Wave', 'browser'),																			
							array('netfront', 'Netfront Browser', 'browser'),		
							array('obigo', 'Obigo', 'browser'),
							array('avantgo', 'AvantGo', 'browser'),			
							array('blazer', 'Blazer', 'browser'),
							array('eudoraweb', 'Eudora Web', 'browser'),
							array('semc-browser', 'Semc-browser', 'browser'),
							array('up.browser', 'Up.browser', 'browser'),			
							array('webpro', 'Webpro', 'browser'),
							array('wms pie', 'Wms Pie', 'browser'),	
							array('iemobile', 'Iemobile', 'browser'),
							array('minimo', 'Minimo', 'browser'),			
							array('opwv', 'Opwv', 'browser'),
							array('polaris', 'Polaris', 'browser'),								
							array('xiino', 'Xiino', 'browser'),
							array('quicktime', 'Quick Time', 'player'),
							array('windows-media-player', 'Windows Media Player', 'player'),
							array('nsplayer', 'NSPlayer', 'player'),
							array('winampmpeg', 'Winamp', 'player'),
							array('mplayer', 'MPlayer', 'player'),							
							array('realplayer', 'Real Player', 'player'),							
							array('vlc media player', 'VLC Media Player', 'player'),							
							array('netpositive', 'Netpositive', 'textbrowser'),//Beos
							array('lynx', 'Lynx', 'textbrowser'),//Linux
							array('elinks', 'Elinks', 'textbrowser'),
							array('links2', 'Links2', 'textbrowser'),
							array('links', 'Links', 'textbrowser'),
							array('emacs', 'Emacs', 'textbrowser'),//Linux
							array('w3m', 'W3M', 'textbrowser'),
							array('amaya', 'Amaya', 'textbrowser'),
							array('dillo', 'Dillo', 'textbrowser'),
							array('ibrowse', 'Ibrowse', 'textbrowser'), //Amiga
							array('sonyericssonp800', 'Sonyericsson800', 'textbrowser'), //SonyEricsson Handheld
		);
		$webkitBrowsersList = array(
							'omniweb', 'arora', 'shiira', 'chrome', 'epiphany', 'gtklauncher', 'midori', 'iron', 'icab', 'lunascape', 'mobile safari', 'safari', 'uzbl', 'applewebkit', 'webkit'
		);
		$geckoBrowsersList = array(
							'fennec', 'bonecho', 'chimera', 'camino', 'epiphany', 'firebird', 'thunderbird', 'flock', 'galeon', 'iceape', 'icecat', 'k-meleon', 'minimo', 'multizilla', 'phoenix', 'songbird', 'swiftfox', 'seamonkey', 'shiretoko', 'iceweasel', 'firefox', 'minefield', 'netscape6', 'netscape', 'rv'
		);
	
		$botsList = array(	
							array('mediapartners-google', 'Google Adsense', 'bot'),
							array('googlebot', 'Googlebot', 'bot'),
							array('msnbot', 'MSNbot', 'bot'),
							array('yahoo! slurp', 'Yahoo! slurp', 'bot'),
							array('yahoo-mm', 'Yahoo-mm', 'bot'),
							array('yahoo-verticalcrawler', 'Yahoo', 'bot'),							
							array('yahoo', 'Yahoo', 'bot'),
							array('slurp', 'Inktomi Slurp', 'bot'),
							array('szukacz', 'Szukacz', 'bot'),	
							array('infoseek', 'Infoseek', 'bot'),
							array('lycos_spider', 'Lycos_spider', 'bot'),
							array('lycos', 'Lycos', 'bot'),
							array('acoon', 'Acoon', 'bot'),
							array('almaden', 'Almaden', 'bot'),
							array('altavista', 'Altavista', 'bot'),
							array('answerbus', 'Answerbus', 'bot'),
							array('anzwerscrawl', 'Anzwerscrawl', 'bot'),
							array('arachnoidea', 'Arachnoidea', 'bot'),
							array('architectspider', 'Architectspider', 'bot'),
							array('askjeeves', 'Askjeeves', 'bot'),
							array('ask jeeves', 'Ask jeeves', 'bot'),
							array('baiduspider', 'Baiduspider', 'bot'),
							array('boitho.com-dc', 'Boitho.com-dc', 'bot'),
							array('cherrypicker', 'Cherrypicker', 'bot'),
							array('crescent', 'Crescent', 'bot'),
							array('emailcollector', 'Emailcollector', 'bot'),
							array('emailsiphon', 'Emailsiphon', 'bot'),
							array('emailwolf', 'Emailwolf', 'bot'),
							array('enfish','Enfish', 'bot'),
							array('extractorpro', 'Extractorpro', 'bot'),
							array('fast-webcrawler', 'Fast-webcrawler', 'bot'),
							array('fastcrawler', 'Fastcrawler', 'bot'),
							array('fido', 'Fido', 'bot'),
							array('gais', 'Gais', 'bot'),
							array('gigabot', 'Gigabot', 'bot'),
							array('gulliver', 'Gulliver', 'bot'),
							array('ia_archiver', 'Ia_archiver', 'bot'),
							array('ia_archive', 'Ia_archive', 'bot'),
							array('iltrovatore-setaccio', 'Iltrovatore-setaccio', 'bot'),
							array('informant', 'Informant', 'bot'),
							array('inktomi', 'Inktomi', 'bot'),
							array('javal', 'Javal', 'bot'),
							array('kit_fireball', 'Kit_fireball', 'bot'),
							array('lwp-trivial', 'Lwp-trivial', 'bot'),
							array('mediapartners-google', 'Mediapartners-google', 'bot'),
							array('namecrawler', 'Namecrawler', 'bot'),
							array('naverbot', 'Naverbot', 'bot'), //blokowac to
							array('objectssearch', 'Objectssearch', 'bot'),
							array('openbot', 'Openbot', 'bot'),
							array('psbot', 'Psbot', 'bot'),
							array('scooter', 'Altavista bot', 'bot'),
							array('search.at', 'Search.at', 'bot'),
							array('sexsearcher', 'Sexsearcher', 'bot'),
							array('sogou', 'Sogou', 'bot'),
							array('sohu-search', 'Sohu-search', 'bot'),
							array('squid', 'Squid', 'bot'),
							array('surveybot', 'Surveybot', 'bot'),
							array('swisssearch', 'Swisssearch', 'bot'),
							array('teoma', 'Teoma', 'bot'),
							array('ultraseek', 'Ultraseek', 'bot'),
							array('valueclick', 'Valueclick', 'bot'),
							array('webcrawler', 'Webcrawler', 'bot'),
							array('wisewire', 'Wisewire', 'bot'),
							array('yahoo-verticalcrawler', 'yahoo-verticalcrawler', 'bot'),
							array('zyborg', 'Zyborg', 'bot'),
							array('yodaobot', 'Yodaobot', 'bot'),
							array('snappreviewbot', 'SnapPreviewBot', 'bot'),
							array('yodaobot', 'Yodaobot', 'bot'),
							array('google', 'Google bot', 'bot'),							
							array('crawler', 'Unknown crawler bot', 'bot'), //ogolnie bot
							array('spider', 'Unknown spider bot', 'bot'), //ogolnie bot
							array('search', 'Unknown search bot', 'bot'), //ogolnie bot							
							array('bot', 'Unknown bot', 'bot'), //ogolnie bot
														
							array('w3c_validator', 'W3C_validator', 'utility'),
							array('wdg_validator', 'WDG_validator', 'utility'),
							array('jakarta commons-httpclient', 'Jakarta commons-httpclient', 'utility'),								
							array('libwww-perl', 'libwww-perl', 'utility'),
							array('python-urllib', 'python-urllib', 'utility'),
							
							array('getright', 'Getright', 'downloader'),
							array('wget', 'wget', 'downloader'),
							array('mozilla', 'Mozilla', 'browser')				 

		);

		$regEx = ''; //wyrazenie regularne uzyte do wyszukania przegladarek
		$tmpBrowserKey = null; //wstepnie zdefiniowana przegladarka
		
		foreach($browsersList AS $key => $browser) { //przeszukanie wstepne
			if(strstr($this->_clientUserAgentString, $browser[0])) {
				$tmpBrowserKey = $key; //jesli znaleziono ciag reprezentujacy przegladarke zapamietanie indeksu pod ktorym jest ona w tablicy
				break;
			}
		}
		if(!is_null($tmpBrowserKey)) { //jesli wczesniej cos znaleziono
			$tmpBrowserId = $browsersList[$tmpBrowserKey][0]; //pobranie krotkiej nazwy z tabeli
			$tmpBrowserName = $browsersList[$tmpBrowserKey][1]; //pobranie nazwy z tabeli
			$tmpBrowserType = $browsersList[$tmpBrowserKey][2]; //pobranie typu z tabeli
			if($tmpBrowserId == 'gecko' || $tmpBrowserId == 'webkit') {
				if($tmpBrowserId == 'gecko') { //sprawdzenie czy nie jest to Gecko lub Webkit
					$geckoWebkitArrStr = 'geckoBrowsersList'; //jesli tak trzeba przeszukwac odpowiednie tablice
				}
				if($tmpBrowserId == 'webkit') {
					$geckoWebkitArrStr = 'webkitBrowsersList';			
				}
				foreach($$geckoWebkitArrStr AS $key => $browser) { //przeszukanie wstepne jesli wczesniej znaleziono Gecko lub Webkit
					if(strstr($this->_clientUserAgentString, $browser)) {
						$tmpBrowserId = $browser; //jesli cos znaleziono nadpisanie krotkiej nazwy
						$tmpBrowserName = ucfirst($browser); //nadpisanie nazwy
						break;
					}
				}
			}
			$regExp = "/(". $tmpBrowserId . ")[\/:(v ]*([0-9]+)(\.[0-9a-z\.]+)?/i";
			if(preg_match($regExp, $this->_clientUserAgentString, $match)) {
				$clientUserAgentNameVer = (isset($match[0])) ? $match[0] : '';
				$this->_clientUserAgentNameVer = $clientUserAgentNameVer;
				$this->_clientUserAgentId = $tmpBrowserId;
				$this->_clientUserAgentName = $tmpBrowserName;
				$this->_clientUserAgentMajorVersion = (isset($match[2])) ? $match[2] : '';
				$this->_clientUserAgentMinorVersion = (isset($match[3])) ? ltrim($match[3], '.') : '';
				$this->_clientUserAgentVersion = $this->_clientUserAgentMajorVersion . '.' . $this->_clientUserAgentMinorVersion;
				$this->_clientUserAgentType = $tmpBrowserType;
				$this->_isBrowser = true;
			}
			if($this->_clientUserAgentId == 'opera' || $this->_clientUserAgentId == 'safari') { //szukanie poprawnej wersji opery
				$regExp = "/(version)[\/ ]*([0-9]+)(\.[0-9a-z\.]+)?/i";
				if(preg_match($regExp, $this->_clientUserAgentString, $match)) {
					$this->_clientUserAgentMajorVersion = (isset($match[2])) ? $match[2] : '';
					$this->_clientUserAgentMinorVersion = (isset($match[3])) ? ltrim($match[3], '.') : '';
					$this->_clientUserAgentVersion = $this->_clientUserAgentMajorVersion . $this->_clientUserAgentMinorVersion;					
				}
			}
		}
		//jesli wyzej zostanie znaleziony gecko ale niedopasowana przegladarka to szukam w botach, tam na koncu bedzie odnaleziona mozilla
		if(is_null($tmpBrowserKey) OR ($browsersList[$tmpBrowserKey][0] == 'gecko' AND $this->_clientUserAgentName == '')) { //przeszukiwanie pod kierunkiem botow i innych narzedzi
			foreach ($botsList as $bot) {
				if (preg_match("/" . $bot[0] . "/", $this->_clientUserAgentString, $match)) {
					$this->_clientUserAgentType = $bot[2];
					$this->_clientUserAgentName = $bot[1];
					$this->_clientUserAgentId = $bot[0];
					$this->_isRobot = true;
					break;
				}
			}			
		}
	}
	
	/**
	 * Sprawdza czy urzadzenie jest telefonem, rozwiazuje i ustawia nazwe urzadzenia telefonicznego
	 * 
	 * @access protected
	 * @return void
	 * 
	 */	
	protected function resolveMobile() {
		$mobileStrArray = array(
				'avantgo', 'blazer', 'elaine', 'eudoraweb', 'iemobile',  'minimo', 'mobile safari',
				'mobilexplorer', 'opera mobile', 'opera mobi', 'opera mini', 'netfront', 'opwv', 
				'polaris', 'semc-browser', 'up.browser', 'webpro', 'wms pie', 'xiino',
				'benq', 'voda', 'blackberry', 'danger hiptop', 'ddipocket', ' droid', 'htc_dream', 
				'htc espresso', 'htc hero', 'htc halo', 'htc huangshan', 'htc legend', 
				'htc liberty', 'htc paradise', 'htc supersonic', 'htc tattoo', 'htc desire', 'ipad', 
				'ipod', 'iphone', 'kindle', 'lge-cx', 'lge-lx', 'lge-mx', 'lge vx', 'lg;lx',
				'nintendo wii', 'nokia', 'palm', 'pdxgw', 'playstation', 'sagem', 'samsung',
				'sec-sgh', 'sharp', 'sonyericsson', 'sprint', 'zunehd', 'zune', 'j-phone',
				'milestone', 'n410', 'mot 24', 'mot-', 'htc-', 'htc_',  'htc ', 'lge ', 'lge-', 'xda',
				'sec-', 'sie-m', 'sie-s', 'spv ', 'smartphone', 'armv', 'midp', 'mobilephone',
				'android', 'epoc', 'cpu os', 'iphone os', 'palmos', 'palmsource', 'windows mobile',
				'windows phone os', 'windows ce', 'symbianos', 'symbian os', 'symbian',
				'webos', 'linux armv',
				'astel', 'docomo', 'novarra-vision', 'portalmmm', 'reqwirelessweb', 'vodafone'
		);
		foreach($mobileStrArray AS $mobile) {
			if(strstr($this->_clientUserAgentString, $mobile)) {
				$this->_isMobile = true;
				break;
			}
			else {
				$this->_isMobile = false;				
			}
		}
		if($this->_isMobile !== false) {
			$device = null;
			$mobileDevices = array(
					'benq' => 'Benq', 
					'voda' => 'Vodafone',
					'blackberry' => 'Blackberry', 
					'danger hiptop' => 'Danger Hiptop', 
					'ddipocket' => 'Ddipocket', 
					' droid' => 'Droid', 
					'htc_dream' => 'HTC Dream', 
					'htc espresso' => 'HTC Espresso', 
					'htc hero' => 'HTC Hero', 
					'htc halo' => 'HTC Halo', 
					'htc huangshan' => 'HTC Huangshan', 
					'htc legend' => 'HTC Legend', 
					'htc liberty' => 'HTC Liberty', 
					'htc paradise' => 'HTC Paradise', 
					'htc supersonic' => 'HTC Supersonic', 
					'htc tattoo' => 'HTC Tatto',
					'htc desire' => 'HTC Desire', 
					'ipad' => 'iPad', 
					'ipod' => 'iPhod', 
					'iphone' => 'iPhone', 
					'kindle' => 'Kindle', 
					'lge-cx' => 'LG', 
					'lge-lx' => 'LG', 
					'lge-mx' => 'LG', 
					'lge vx' => 'LG', 
					'lg;lx' => 'LG',
					'nintendo wii' => 'Nitendo Wii', 
					'nokia' => 'Nokia', 
					'palm' => 'Palm', 
					'pdxgw' => 'Pdxgw', 
					'playstation' => 'Playstation', 
					'sagem' => 'Sagem', 
					'samsung' => 'Samsung',
					'sec-sgh' => 'SGH', 
					'sharp' => 'Sharp', 
					'sonyericsson' => 'Sony Ericsson', 
					'sprint' => 'Sprint', 
					'zunehd' => 'ZuneHD', 
					'zune' => 'Zune', 
					'j-phone' => 'J-Phone',
					'milestone' => 'Milestone', 
					'n410' => 'N410', 
					'mot 24' => 'Motorolla', 
					'mot-' => 'Motorolla', 
					'htc-' => 'HTC', 
					'htc_' => 'HTC',  
					'htc ' => 'HTC', 
					'lge-' => 'LG', 
					'lge' => 'LG',
					'xda' => 'XDA',
					'sec-' => 'SGH', 
					'sie-m' => 'Siemens', 
					'sie-s' => 'Siemens', 
					'spv ' => 'SPV', 
					'smartphone' => 'Smartphone', 
					'armv' => 'ARMV', 
					'midp' => 'MIDP', 
					'mobilephone' => 'Mobilephone'
			);		
			foreach($mobileDevices AS $key => $device) {
				if(strstr($this->_clientUserAgentString, $key)) {
					$this->_clientMobileDevice = $device;
					break;
				}
			}
		}
	}
	
	/**
	 * Rozwiazuje i ustawia akceptowane przez klienta jezyki oraz jego jezyk podstawowy
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function resolveClientLanguage() {
		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) AND $_SERVER['HTTP_ACCEPT_LANGUAGE'] != '') {
			$languages = preg_replace('/;q=[\.0-9]+/i', '', trim($_SERVER['HTTP_ACCEPT_LANGUAGE']));
			$this->_clientUserAcceptedLanguages = explode(',', $languages);
			$this->_clientLanguage = $this->_clientUserAcceptedLanguages[0];
		}
	}	
	
	/**
	 * Rozwiazuje i ustawia akceptowane przez klienta strony kodowe oraz jego strone kodowa podstawowa
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function resolveClientCharset() {
		if (isset($_SERVER['HTTP_ACCEPT_CHARSET']) AND $_SERVER['HTTP_ACCEPT_CHARSET'] != '') {		
			$charsets = preg_replace('/(;q=(.*?))/', '', strtolower(trim($_SERVER['HTTP_ACCEPT_CHARSET'])));
			$this->_clientUserAcceptedCharsets = explode(',', $charsets);
			$this->_clientCharset = $this->_clientUserAcceptedCharsets[0];
		}
	}
	
	//Metody ulatwiajace testowanie 
	
	/**
	 * Metoda ulatwiajaca testowanie
	 * 
	 * @access public
	 * @param string Dane przegladarki
	 * @return void
	 * 
	 */
	public function resolveAll($browser) {
		$this->_clientUserAgentString = strtolower($browser);
		if($this->_clientUserAgentString != '') {
			$this->resolveClientOS();
			$this->resolveClientBrowser();
			$this->resolveMobile();
		}
		$this->resolveClientLanguage();
		$this->resolveClientCharset();
	}
	
	/**
	 * Metoda ulatwiajaca testowanie
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function reset() {
				$this->_clientUserAgentNameVer = '';
				$this->_clientUserAgentId = '';
				$this->_clientUserAgentName = '';
				$this->_clientUserAgentVersion = '';
				$this->_clientUserAgentMajorVersion = '';
				$this->_clientUserAgentMinorVersion = '';
				$this->_clientUserAgentType = '';		
				$this->_isMobile = false;
				$this->_isBrowser = false;
				$this->_isRobot = false;
				$this->_clientMobileDevice = '';
				$this->_clientUserAcceptedLanguages = array();
				$this->_clientLanguage = '';
				$this->_clientUserAcceptedCharsets = array();
				$this->_clientCharset = '';								
	}	
	
}

?>
