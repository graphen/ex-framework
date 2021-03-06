<?php

/**
 * @class FileManager
 *
 * @author Przemyslaw Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class FileManager extends DirAndFileManager implements IFileManager {

	/**
	 * Obiekt obslugi pliku
	 *
	 * @var object
	 * 
	 */	
	protected $_fileHandler = null;
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * 
	 */	
	public function __construct(IFileHandler $fileHandler) {
		$this->_fileHandler = $fileHandler;
	}

	/**
	 * Sprawdza czy plik jest zwyklym plikiem
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */
	public function isFile($filePath) {
		if(!file_exists($filePath)) {
			throw new DirAndFileManagerException('Plik: ' . $filePath . ' nie istnieje');
		}
		if(is_file($filePath)) {
			return true;
		}
		else {
			return false;
		}
	}
			
	/**
	 * Kopiuje pliki
	 *
	 * @access public
	 * @param string Sciezka do pliku zrodlowego
	 * @param string Sciezka do pliku docelowego
	 * @param bool Czy nadpisac? //true dla tak
	 * @return void
	 * 
	 */
	public function copy($filePath, $newFilePath, $overwrite=false) {		
		if(!$this->isFile($filePath)) {
			throw new DirAndFileManagerException('Sciezka: ' . $filePath . ' nie prowadzi do pliku');
		}
		if(file_exists($newFilePath) AND ($overwrite == false)) {
			throw new DirAndFileManagerException('Plik docelowy: ' . $filePath . ' istnieje');
		}
		if(!copy($filePath, $newFilePath)) {
			throw new DirAndFileManagerException('Nie mozna skopiowac pliku: ' . $filePath);
		}
	}	
	
	/**
	 * Tworzy nowe pliki
	 *
	 * @access public
	 * @param string Sciezka do tworzonego pliku
	 * @param bool Czy nadpisac jesli istnieje?
	 * @param integer Uprawnienia
	 * @return void
	 * 
	 */
	public function create($filePath, $overwrite=false, $mode=0666) {	
		if(file_exists($filePath) AND ($overwrite === false)) {
			throw new DirAndFileManagerException('Plik: ' . $filePath . ' juz istnieje');
		}
		if(!touch($filePath)) {
			throw new DirAndFileManagerException('Nie mozna utworzyc pliku: ' . $filePath);
		}
		if(!chmod($filePath, $mode)) {
			throw new DirAndFileManagerException('Nie mozna zmienic uprawnien do pliku: ' . $filePath);
		}
	}
	
	/**
	 * Kasuje pliki
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */
	public function delete($filePath) {
		if(!$this->isFile($filePath)) {
			throw new DirAndFileManagerException('Sciezka nie prowadzi do pliku: ' . $filePath);
		}
		if(strstr("win", PHP_OS)) {
			str_replace('/', '\\', $filePath);
			exec("del " . $filePath);
			if(file_exists($filePath)) {
				throw new DirAndFileManagerException('Nie mozna skasowac pliku: ' . $filePath);
			}
		}
		else {
			if(!unlink($filePath)) {
				throw new DirAndFileManagerException('Nie mozna skasowac pliku: ' . $filePath);
			}
		}
	}
		
	/**
	 * Zwraca zawartosc pliku jako tablice
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return array
	 * 
	 */
	public function readToArray($filePath) {
		if(!$this->isFile($filePath)) {
			throw new DirAndFileManagerException('Sciezka nie prowadzi do pliku: ' . $filePath);
		}
		if(!is_readable($filePath)) {
			throw new DirAndFileManagerException('Nie mozna czytac pliku: ' . $filePath);
		}
		$data = array();
		if(!($data = file($filePath))) {
			throw new DirAndFileManagerException('Nie mozna czytac pliku: ' . $filePath);
		}
		return $data;
	}

	/**
	 * Zwraca zawartosc pliku jako string
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return string
	 * 
	 */
	public function readToString($filePath) {	
		if(!$this->isFile($filePath)) {
			throw new DirAndFileManagerException('Sciezka nie prowadzi do pliku: ' . $filePath);
		}
		if(!is_readable($filePath)) {
			throw new DirAndFileManagerException('Nie mozna czytac pliku: ' . $filePath);
		}
		$data = '';
		if(!($data = file_get_contents($filePath))) {
			throw new DirAndFileManagerException('Nie mozna czytac pliku: ' . $filePath);
		}
		return $data;
	}

	/**
	 * Zapisuje string do pliku
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @param string Dane
	 * @param bool Czy dolaczyc na koniec pliku 
	 * @return void
	 * 
	 */
	public function write($filePath, $data, $append=false) {
		if(!$this->isFile($filePath)) {
			throw new DirAndFileManagerException('Sciezka nie prowadzi do pliku: ' . $filePath);
		}
		if(!is_writable($filePath)) {
			throw new DirAndFileManagerException('Nie mozna pisac do pliku: ' . $filePath);
		}
		$ap = ($append === true) ? FILE_APPEND : null; 
		if(!(file_put_contents($filePath, $data, $ap))) {
			throw new DirAndFileManagerException('Nie mozna pisac do pliku: ' . $filePath);
		}
	}
	
	/**
	 * Wyswietla zawartosc pliku
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return void
	 * 
	 */
	public function show($filePath) {
		if(!$this->isFile($filePath)) {
			throw new DirAndFileManagerException('Sciezka nie prowadzi do pliku: ' . $filePath);
		}
		if(!is_readable($filePath)) {
			throw new DirAndFileManagerException('Nie mozna czytac pliku: ' . $filePath);
		}
		if(!readfile($filePath)) {
			throw new DirAndFileManagerException('Nie mozna wyswietlic zawartosci pliku: ' . $filePath);
		}
	}

	/**
	 * Zwraca obiekt reprezentujacy plik
	 *
	 * @access public
	 * @param string Sciezka do pliku
	 * @return object
	 */	
	public function getHandler($filePath='') {
		$fileHandler = clone $this->_fileHandler;
		$fileHandler->setFilePath($filePath);
		return $fileHandler;
	}
	
	/**
	 * Zwraca typ MIME pliku
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @return mixed //Array lub String
	 *
	 */
	public function getMime($filePath) {
		if(strstr('/', $filePath)) {
			$parts = explode('/', $filePath);
			$filePath = end($parts);		
		}
		if(!strstr($filePath, '.')) {
			return 'application/x-unknown-content-type';
		}		
		$parts = explode('.', $filePath);
		$extension = end($parts);
		$extension = strtolower($extension);
		$mimeTypes = array(
			'3dm'	=>	'x-world/x-3dmf',
			'3dmf'	=>	'x-world/x-3dmf',
			'a'		=>	'application/octet-stream',
			'aab'	=>	'application/x-authorware-bin',
			'aam'	=>	'application/x-authorware-map',
			'aas'	=>	'application/x-authorware-seg',
			'abc'	=>	'text/vnd.abc',
			'acgi'	=>	'text/html',
			'afl'	=>	'video/animaflex',
			'ai'	=>	'application/postscript',
			'aif'	=>	array('audio/aiff', 'audio/x-aiff'),
			'aifc'	=>	array('audio/aiff', 'audio/x-aiff'),
			'aiff'	=>	array('audio/aiff', 'audio/x-aiff'),
			'aim'	=>	'application/x-aim',
			'aip'	=>	'text/x-audiosoft-intra',
			'ani'	=>	'application/x-navi-animation',
			'aos'	=>	'application/x-nokia-9000-communicator-add-on-software',
			'aps'	=>	'application/mime',
			'arc'	=>	'application/octet-stream',
			'arj'	=>	array('application/arj', 'application/octet-stream'),
			'art'	=>	'image/x-jg',
			'asf'	=>	'video/x-ms-asf',
			'asm'	=>	'text/x-asm',
			'asp'	=>	'text/asp',
			'asx'	=>	array('application/x-mplayer2', 'video/x-ms-asf', 'video/x-ms-asf-plugin'),
			'au'	=>	array('audio/basic', 'audio/x-au'),
			'avi'	=>	array('application/x-troff-msvideo', 'video/avi', 'video/msvideo', 'video/x-msvideo'),
			'avs'	=>	'video/avs-video',
			'bcpio'	=>	'application/x-bcpio',
			'bin'	=>	array('application/mac-binary', 'application/macbinary', 'application/octet-stream', 'application/x-binary', 'application/x-macbinary'),
			'bm'	=>	'image/bmp',
			'bmp'	=>	array('image/bmp', 'image/x-windows-bmp'),
			'boo'	=>	'application/book',
			'book'	=>	'application/book',
			'boz'	=>	'application/x-bzip2',
			'bsh'	=>	'application/x-bsh',
			'bz'	=>	'application/x-bzip',
			'bz2'	=>	'application/x-bzip2',
			'c'		=>	array('text/plain', 'text/x-c'),
			'c++'	=>	'text/plain',
			'cat'	=>	'application/vnd.ms-pki.seccat',
			'cc'	=>	array('text/plain', 'text/x-c'),
			'ccad'	=>	'application/clariscad',
			'cco'	=>	'application/x-cocoa',
			'cdf'	=>	array('application/cdf', 'application/x-cdf', 'application/x-netcdf'),
			'cer'	=>	array('application/pkix-cert', 'application/x-x509-ca-cert'),
			'cha'	=>	'application/x-chat',
			'chat'	=>	'application/x-chat',
			'class'	=>	array('application/java', 'application/java-byte-code', 'application/x-java-class'),
			'com'	=>	array('application/octet-stream', 'text/plain'),
			'conf'	=>	'text/plain',
			'cpio'	=>	'application/x-cpio',
			'cpp'	=>	'text/x-c',
			'cpt'	=>	array('application/mac-compactpro', 'application/x-compactpro', 'application/x-cpt'),
			'crl'	=>	array('application/pkcs-crl', 'application/pkix-crl'),
			'crt'	=>	array('application/pkix-cert', 'application/x-x509-ca-cert', 'application/x-x509-user-cert'),
			'csh'	=>	array('application/x-csh', 'text/x-script.csh'),
			'css'	=>	array('application/x-pointplus', 'text/css'),
			'cxx'	=>	'text/plain',
			'dcr'	=>	'application/x-director',
			'deepv'	=>	'application/x-deepv',
			'def'	=>	'text/plain',
			'der'	=>	'application/x-x509-ca-cert',
			'dif'	=>	'video/x-dv',
			'dir'	=>	'application/x-director',
			'dl'	=>	array('video/dl', 'video/x-dl'),
			'doc'	=>	'application/msword',
			'dot'	=>	'application/msword',
			'dp'	=>	'application/commonground',
			'drw'	=>	'application/drafting',
			'dump'	=>	'application/octet-stream',
			'dv'	=>	'video/x-dv',
			'dvi'	=>	'application/x-dvi',
			'dwf'	=>	array('drawing/x-dwf(old)', 'model/vnd.dwf'),
			'dwg'	=>	array('application/acad', 'image/vnd.dwg', 'image/x-dwg'),
			'dxf'	=>	array('application/dxf', 'image/vnd.dwg', 'image/x-dwg'),
			'dxr'	=>	'application/x-director',
			'el'	=>	'text/x-script.elisp',
			'elc'	=>	array('application/x-bytecode.elisp(compiled', 'application/x-elc'),
			'env'	=>	'application/x-envoy',
			'eps'	=>	'application/postscript',
			'es'	=>	'application/x-esrehber',
			'etx'	=>	'text/x-setext',
			'evy'	=>	array('application/envoy', 'application/x-envoy'),
			'exe'	=>	'application/octet-stream',
			'f'		=>	array('text/plain', 'text/x-fortran'),
			'f77'	=>	'text/x-fortran',
			'f90'	=>	array('text/plain', 'text/x-fortran'),
			'fdf'	=>	'application/vnd.fdf',
			'fif'	=>	array('application/fractals', 'image/fif'),
			'fli'	=>	array('video/fli', 'video/x-fli'),
			'flo'	=>	'image/florian',
			'flx'	=>	'text/vnd.fmi.flexstor',
			'fmf'	=>	'video/x-atomic3d-feature',
			'for'	=>	array('text/plain', 'text/x-fortran'),
			'fpx'	=>	array('image/vnd.fpx', 'image/vnd.net-fpx'),
			'frl'	=>	'application/freeloader',
			'funk'	=>	'audio/make',
			'g'	=>	'text/plain',
			'g3'	=>	'image/g3fax',
			'gif'	=>	'image/gif',
			'gl'	=>	array('video/gl', 'video/x-gl'),
			'gsd'	=>	'audio/x-gsm',
			'gsm'	=>	'audio/x-gsm',
			'gsp'	=>	'application/x-gsp',
			'gss'	=>	'application/x-gss',
			'gtar'	=>	'application/x-gtar',
			'gz'	=>	array('application/x-compressed', 'application/x-gzip'),
			'gzip'	=>	array('application/x-gzip', 'multipart/x-gzip'),
			'h'		=>	array('text/plain', 'text/x-h'),
			'hdf'	=>	'application/x-hdf',
			'help'	=>	'application/x-helpfile',
			'hgl'	=>	'application/vnd.hp-hpgl',
			'hh'	=>	array('text/plain', 'text/x-h'),
			'hlb'	=>	'text/x-script',
			'hlp'	=>	array('application/hlp', 'application/x-helpfile', 'application/x-winhelp'),
			'hpg'	=>	'application/vnd.hp-hpgl',
			'hpgl'	=>	'application/vnd.hp-hpgl',
			'hqx'	=>	array('application/binhex', 'application/binhex4', 'application/mac-binhex', 'application/mac-binhex40', 'application/x-binhex40', 'application/x-mac-binhex40'),
			'hta'	=>	'application/hta',
			'htc'	=>	'text/x-component',
			'htm'	=>	'text/html',
			'html'	=>	'text/html',
			'htmls'	=>	'text/html',
			'htt'	=>	'text/webviewhtml',
			'htx'	=>	'text/html',
			'ice'	=>	'x-conference/x-cooltalk',
			'ico'	=>	'image/x-icon',
			'idc'	=>	'text/plain',
			'ief'	=>	'image/ief',
			'iefs'	=>	'image/ief',
			'iges'	=>	array('application/iges', 'model/iges'),
			'igs'	=>	array('application/iges', 'model/iges'),
			'ima'	=>	'application/x-ima',
			'imap'	=>	'application/x-httpd-imap',
			'inf'	=>	'application/inf',
			'ins'	=>	'application/x-internett-signup',
			'ip'	=>	'application/x-ip2',
			'isu'	=>	'video/x-isvideo',
			'it'	=>	'audio/it',
			'iv'	=>	'application/x-inventor',
			'ivr'	=>	'i-world/i-vrml',
			'ivy'	=>	'application/x-livescreen',
			'jam'	=>	'audio/x-jam',
			'jav'	=>	array('text/plain', 'text/x-java-source'),
			'java'	=>	array('text/plain', 'text/x-java-source'),
			'jcm'	=>	'application/x-java-commerce',
			'jfif'	=>	array('image/jpeg', 'image/pjpeg'),
			'jfif-tbnl'	=>	'image/jpeg',
			'jpe'	=>	array('image/jpeg', 'image/pjpeg'),
			'jpeg'	=>	array('image/jpeg', 'image/pjpeg'),
			'jpg'	=>	array('image/jpeg', 'image/pjpeg'),
			'jps'	=>	'image/x-jps',
			'js'	=>	'application/x-javascript',
			'jut'	=>	'image/jutvision',
			'kar'	=>	array('audio/midi', 'music/x-karaoke'),
			'ksh'	=>	array('application/x-ksh', 'text/x-script.ksh'),
			'la'	=>	array('audio/nspaudio', 'audio/x-nspaudio'),
			'lam'	=>	'audio/x-liveaudio',
			'latex'	=>	'application/x-latex',
			'lha'	=>	array('application/lha', 'application/octet-stream', 'application/x-lha'),
			'lhx'	=>	'application/octet-stream',
			'list'	=>	'text/plain',
			'lma'	=>	array('audio/nspaudio', 'audio/x-nspaudio'),
			'log'	=>	'text/plain',
			'lsp'	=>	array('application/x-lisp', 'text/x-script.lisp'),
			'lst'	=>	'text/plain',
			'lsx'	=>	'text/x-la-asf',
			'ltx'	=>	'application/x-latex',
			'lzh'	=>	array('application/octet-stream', 'application/x-lzh'),
			'lzx'	=>	array('application/lzx', 'application/octet-stream', 'application/x-lzx'),
			'm'		=>	array('text/plain', 'text/x-m'),
			'm1v'	=>	'video/mpeg',
			'm2a'	=>	'audio/mpeg',
			'm2v'	=>	'video/mpeg',
			'm3u'	=>	'audio/x-mpequrl',
			'man'	=>	'application/x-troff-man',
			'map'	=>	'application/x-navimap',
			'mar'	=>	'text/plain',
			'mbd'	=>	'application/mbedlet',
			'mc$'	=>	'application/x-magic-cap-package-1.0',
			'mcd'	=>	array('application/mcad', 'application/x-mathcad'),
			'mcf'	=>	array('image/vasa', 'text/mcf'),
			'mcp'	=>	'application/netmc',
			'me'	=>	'application/x-troff-me',
			'mht'	=>	'message/rfc822',
			'mhtml'	=>	'message/rfc822',
			'mid'	=>	array('application/x-midi', 'audio/midi', 'audio/x-mid', 'audio/x-midi', 'music/crescendo', 'x-music/x-midi'),
			'midi'	=>	array('application/x-midi', 'audio/midi', 'audio/x-mid', 'audio/x-midi', 'music/crescendo',	'x-music/x-midi'),
			'mif'	=>	array('application/x-frame', 'application/x-mif'),
			'mime'	=>	array('message/rfc822', 'www/mime'),
			'mjf'	=>	'audio/x-vnd.audioexplosion.mjuicemediafile',
			'mjpg'	=>	'video/x-motion-jpeg',
			'mm'	=>	array('application/base64', 'application/x-meme'),
			'mme'	=>	'application/base64',
			'mod'	=>	array('audio/mod', 'audio/x-mod'),
			'moov'	=>	'video/quicktime',
			'mov'	=>	'video/quicktime',
			'movie'	=>	'video/x-sgi-movie',
			'mp2'	=>	array('audio/mpeg', 'audio/x-mpeg', 'video/mpeg', 'video/x-mpeg', 'video/x-mpeq2a'),
			'mp3'	=>	array('audio/mpeg3', 'audio/x-mpeg-3', 'video/mpeg', 'video/x-mpeg'),
			'mpa'	=>	array('audio/mpeg', 'video/mpeg'),
			'mpc'	=>	'application/x-project',
			'mpe'	=>	'video/mpeg',
			'mpeg'	=>	'video/mpeg',
			'mpg'	=>	array('audio/mpeg', 'video/mpeg'),
			'mpga'	=>	'audio/mpeg',
			'mpp'	=>	'application/vnd.ms-project',
			'mpt'	=>	'application/x-project',
			'mpv'	=>	'application/x-project',
			'mpx'	=>	'application/x-project',
			'mrc'	=>	'application/marc',
			'ms'	=>	'application/x-troff-ms',
			'mv'	=>	'video/x-sgi-movie',
			'my'	=>	'audio/make',
			'mzz'	=>	'application/x-vnd.audioexplosion.mzz',
			'nap'	=>	'image/naplps',
			'naplps'	=>	'image/naplps',
			'nc'	=>	'application/x-netcdf',
			'ncm'	=>	'application/vnd.nokia.configuration-message',
			'nif'	=>	'image/x-niff',
			'niff'	=>	'image/x-niff',
			'nix'	=>	'application/x-mix-transfer',
			'nsc'	=>	'application/x-conference',
			'nvd'	=>	'application/x-navidoc',
			'o'		=>	'application/octet-stream',
			'oda'	=>	'application/oda',
			'odc'	=>	'application/vnd.oasis.opendocument.chart',
			'odf'	=>	'application/vnd.oasis.opendocument.formula',
			'odg'	=>	'application/vnd.oasis.opendocument.graphics',
			'odi'	=>	'application/vnd.oasis.opendocument.image',
			'odm'	=>	'application/vnd.oasis.opendocument.text-master',
			'odp'	=>	'application/vnd.oasis.opendocument.presentation',
			'ods'	=>	'application/vnd.oasis.opendocument.spreadsheet',
			'odt'	=>	'application/vnd.oasis.opendocument.text',
			'omc'	=>	'application/x-omc',
			'omcd'	=>	'application/x-omcdatamaker',
			'omcr'	=>	'application/x-omcregerator',			
			'p'		=>	'text/x-pascal',
			'p10'	=>	array('application/pkcs10', 'application/x-pkcs10'),
			'p12'	=>	array('application/pkcs-12', 'application/x-pkcs12'),
			'p7a'	=>	'application/x-pkcs7-signature',
			'p7c'	=>	array('application/pkcs7-mime', 'application/x-pkcs7-mime'),
			'p7m'	=>	array('application/pkcs7-mime', 'application/x-pkcs7-mime'),
			'p7r'	=>	'application/x-pkcs7-certreqresp',
			'p7s'	=>	'application/pkcs7-signature',
			'part'	=>	'application/pro_eng',
			'pas'	=>	'text/pascal',
			'pbm'	=>	'image/x-portable-bitmap',
			'pcl'	=>	array('application/vnd.hp-pcl', 'application/x-pcl'),
			'pct'	=>	'image/x-pict',
			'pcx'	=>	'image/x-pcx',
			'pdb'	=>	'chemical/x-pdb',
			'pdf'	=>	'application/pdf',
			'pfunk'	=>	array('audio/make', 'audio/make.my.funk'),
			'pgm'	=>	array('image/x-portable-graymap', 'image/x-portable-greymap'),
			'pic'	=>	'image/pict',
			'pict'	=>	'image/pict',
			'pkg'	=>	'application/x-newton-compatible-pkg',
			'pko'	=>	'application/vnd.ms-pki.pko',
			'pl'	=>	array('text/plain', 'text/x-script.perl'),
			'plx'	=>	'application/x-pixclscript',
			'pm'	=>	array('image/x-xpixmap', 'text/x-script.perl-module'),
			'pm4'	=>	'application/x-pagemaker',
			'pm5'	=>	'application/x-pagemaker',
			'png'	=>	'image/png',
			'pnm'	=>	array('application/x-portable-anymap', 'image/x-portable-anymap'),
			'pot'	=>	array('application/mspowerpoint', 'application/vnd.ms-powerpoint'),
			'pov'	=>	'model/x-pov',
			'ppa'	=>	'application/vnd.ms-powerpoint',
			'ppm'	=>	'image/x-portable-pixmap',
			'pps'	=>	array('application/mspowerpoint','application/vnd.ms-powerpoint'),
			'ppt'	=>	array('application/mspowerpoint', 'application/powerpoint', 'application/vnd.ms-powerpoint', 'application/x-mspowerpoint'),
			'ppz'	=>	'application/mspowerpoint',
			'pre'	=>	'application/x-freelance',
			'prt'	=>	'application/pro_eng',
			'ps'	=>	'application/postscript',
			'psd'	=>	'application/octet-stream',
			'pvu'	=>	'paleovu/x-pv',
			'pwz'	=>	'application/vnd.ms-powerpoint',
			'py'	=>	'text/x-script.phyton',
			'pyc'	=>	'applicaiton/x-bytecode.python',
			'qcp'	=>	'audio/vnd.qcelp',
			'qd3'	=>	'x-world/x-3dmf',
			'qd3d'	=>	'x-world/x-3dmf',
			'qif'	=>	'image/x-quicktime',
			'qt'	=>	'video/quicktime',
			'qtc'	=>	'video/x-qtc',
			'qti'	=>	'image/x-quicktime',
			'qtif'	=>	'image/x-quicktime',
			'ra'	=>	array('audio/x-pn-realaudio','audio/x-pn-realaudio-plugin', 'audio/x-realaudio'),
			'ram'	=>	'audio/x-pn-realaudio',
			'ras'	=>	array('application/x-cmu-raster', 'image/cmu-raster', 'image/x-cmu-raster'),
			'rast'	=>	'image/cmu-raster',
			'rexx'	=>	'text/x-script.rexx',
			'rf'	=>	'image/vnd.rn-realflash',
			'rgb'	=>	'image/x-rgb',
			'rm'	=>	array('application/vnd.rn-realmedia', 'audio/x-pn-realaudio'),
			'rmi'	=>	'audio/mid',
			'rmm'	=>	'audio/x-pn-realaudio',
			'rmp'	=>	array('audio/x-pn-realaudio', 'audio/x-pn-realaudio-plugin'),
			'rng'	=>	array('application/ringing-tones', 'application/vnd.nokia.ringing-tone'),
			'rnx'	=>	'application/vnd.rn-realplayer',
			'roff'	=>	'application/x-troff',
			'rp'	=>	'image/vnd.rn-realpix',
			'rpm'	=>	'audio/x-pn-realaudio-plugin',
			'rt'	=>	array('text/richtext', 'text/vnd.rn-realtext'),
			'rtf'	=>	array('application/rtf', 'application/x-rtf', 'text/richtext'),
			'rtx'	=>	array('application/rtf', 'text/richtext'),
			'rv'	=>	'video/vnd.rn-realvideo',
			's'		=>	'text/x-asm',
			's3m'	=>	'audio/s3m',
			'saveme'	=>	'application/octet-stream',
			'sbk'	=>	'application/x-tbook',
			'scm'	=>	array('application/x-lotusscreencam', 'text/x-script.guile', 'text/x-script.scheme', 'video/x-scm'),
			'sdml'	=>	'text/plain',
			'sdp'	=>	array('application/sdp', 'application/x-sdp'),
			'sdr'	=>	'application/sounder',
			'sea'	=>	array('application/sea', 'application/x-sea'),
			'set'	=>	'application/set',
			'sgm'	=>	array('text/sgml', 'text/x-sgml'),
			'sgml'	=>	array('text/sgml', 'text/x-sgml'),
			'sh'	=>	array('application/x-bsh', 'application/x-sh', 'application/x-shar', 'text/x-script.sh'),
			'shar'	=>	array('application/x-bsh', 'application/x-shar'),
			'shtml'	=>	array('text/html', 'text/x-server-parsed-html'),
			'sid'	=>	'audio/x-psid',
			'sit'	=>	array('application/x-sit', 'application/x-stuffit'),
			'skd'	=>	'application/x-koan',
			'skm'	=>	'application/x-koan',
			'skp'	=>	'application/x-koan',
			'skt'	=>	'application/x-koan',
			'sl'	=>	'application/x-seelogo',
			'smi'	=>	'application/smil',
			'smil'	=>	'application/smil',
			'snd'	=>	array('audio/basic', 'audio/x-adpcm'),
			'sol'	=>	'application/solids',
			'spc'	=>	array('application/x-pkcs7-certificates', 'text/x-speech'),
			'spl'	=>	'application/futuresplash',
			'spr'	=>	'application/x-sprite',
			'sprite'	=>	'application/x-sprite',
			'sql'	=>	'text/x-sql',
			'src'	=>	'application/x-wais-source',
			'ssi'	=>	'text/x-server-parsed-html',
			'ssm'	=>	'application/streamingmedia',
			'sst'	=>	'application/vnd.ms-pki.certstore',
			'step'	=>	'application/step',
			'stl'	=>	array('application/sla', 'application/vnd.ms-pki.stl', 'application/x-navistyle'),
			'stp'	=>	'application/step',
			'sv4cpio'	=>	'application/x-sv4cpio',
			'sv4crc'	=>	'application/x-sv4crc',
			'svf'	=>	array('image/vnd.dwg', 'image/x-dwg'),
			'svr'	=>	array('application/x-world', 'x-world/x-svr'),
			'swf'	=>	'application/x-shockwave-flash',
			't'		=>	'application/x-troff',
			'talk'	=>	'text/x-speech',
			'tar'	=>	'application/x-tar',
			'tbk'	=>	array('application/toolbook', 'application/x-tbook'),
			'tcl'	=>	array('application/x-tcl', 'text/x-script.tcl'),
			'tcsh'	=>	'text/x-script.tcsh',
			'tex'	=>	'application/x-tex',
			'texi'	=>	'application/x-texinfo',
			'texinfo'	=>	'application/x-texinfo',
			'text'	=>	array('application/plain', 'text/plain'),
			'tgz'	=>	array('application/gnutar', 'application/x-compressed'),
			'tif'	=>	array('image/tiff', 'image/x-tiff'),
			'tiff'	=>	array('image/tiff', 'image/x-tiff'),
			'tr'	=>	'application/x-troff',
			'tsi'	=>	'audio/tsp-audio',
			'tsp'	=>	array('application/dsptype', 'audio/tsplayer'),
			'tsv'	=>	'text/tab-separated-values',
			'turbot'	=>	'image/florian',
			'txt'	=>	'text/plain',
			'uil'	=>	'text/x-uil',
			'uni'	=>	'text/uri-list',
			'unis'	=>	'text/uri-list',
			'unv'	=>	'application/i-deas',
			'uri'	=>	'text/uri-list',
			'uris'	=>	'text/uri-list',
			'ustar'	=>	array('application/x-ustar', 'multipart/x-ustar'),
			'uu'	=>	array('application/octet-stream', 'text/x-uuencode'),
			'uue'	=>	'text/x-uuencode',
			'vcd'	=>	'application/x-cdlink',
			'vcs'	=>	'text/x-vcalendar',
			'vda'	=>	'application/vda',
			'vdo'	=>	'video/vdo',
			'vew'	=>	'application/groupwise',
			'viv'	=>	array('video/vivo', 'video/vnd.vivo'),
			'vivo'	=>	array('video/vivo', 'video/vnd.vivo'),
			'vmd'	=>	'application/vocaltec-media-desc',
			'vmf'	=>	'application/vocaltec-media-file',
			'voc'	=>	array('audio/voc', 'audio/x-voc'),
			'vos'	=>	'video/vosaic',
			'vox'	=>	'audio/voxware',
			'vqe'	=>	'audio/x-twinvq-plugin',
			'vqf'	=>	'audio/x-twinvq',
			'vql'	=>	'audio/x-twinvq-plugin',
			'vrml'	=>	array('application/x-vrml', 'model/vrml', 'x-world/x-vrml'),
			'vrt'	=>	'x-world/x-vrt',
			'vsd'	=>	'application/x-visio',
			'vst'	=>	'application/x-visio',
			'vsw'	=>	'application/x-visio',
			'w60'	=>	'application/wordperfect6.0',
			'w61'	=>	'application/wordperfect6.1',
			'w6w'	=>	'application/msword',
			'wav'	=>	array('audio/wav', 'audio/x-wav'),
			'wb1'	=>	'application/x-qpro',
			'wbmp'	=>	'image/vnd.wap.wbmp',
			'web'	=>	'application/vnd.xara',
			'wiz'	=>	'application/msword',
			'wk1'	=>	'application/x-123',
			'wmf'	=>	'windows/metafile',
			'wml'	=>	'text/vnd.wap.wml',
			'wmlc'	=>	'application/vnd.wap.wmlc',
			'wmls'	=>	'text/vnd.wap.wmlscript',
			'wmlsc'	=>	'application/vnd.wap.wmlscriptc',
			'word'	=>	'application/msword',
			'wp'	=>	'application/wordperfect',
			'wp5'	=>	array('application/wordperfect', 'application/wordperfect6.0'),
			'wp6'	=>	'application/wordperfect',
			'wpd'	=>	array('application/wordperfect', 'application/x-wpwin'),
			'wq1'	=>	'application/x-lotus',
			'wri'	=>	array('application/mswrite', 'application/x-wri'),
			'wrl'	=>	array('application/x-world', 'model/vrml', 'x-world/x-vrml'),
			'wrz'	=>	array('model/vrml', 'x-world/x-vrml'),
			'wsc'	=>	'text/scriplet',
			'wsrc'	=>	'application/x-wais-source',
			'wtk'	=>	'application/x-wintalk',
			'xbm'	=>	array('image/x-xbitmap', 'image/x-xbm', 'image/xbm'),
			'xdr'	=>	'video/x-amt-demorun',
			'xgz'	=>	'xgl/drawing',
			'xif'	=>	'image/vnd.xiff',
			'xl'	=>	'application/excel',
			'xla'	=>	array('application/excel', 'application/x-excel', 'application/x-msexcel'),
			'xlb'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/x-excel'),
			'xlc'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/x-excel'),
			'xld'	=>	array('application/excel', 'application/x-excel'),
			'xlk'	=>	array('application/excel', 'application/x-excel'),
			'xll'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/x-excel'),
			'xlm'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/x-excel'),
			'xls'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-msexcel'),
			'xlt'	=>	array('application/excel', 'application/x-excel'),
			'xlv'	=>	array('application/excel', 'application/x-excel'),
			'xlw'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-msexcel'),
			'xm'	=>	'audio/xm',
			'xml'	=>	array('application/xml', 'text/xml'),
			'xmz'	=>	'xgl/movie',
			'xpix'	=>	'application/x-vnd.ls-xpix',
			'xpm'	=>	array('image/x-xpixmap', 'image/xpm'),
			'x-png'	=>	'image/png',
			'xsr'	=>	'video/x-amt-showrun',
			'xwd'	=>	array('image/x-xwd', 'image/x-xwindowdump'),
			'xyz'	=>	'chemical/x-pdb',
			'z'		=>	array('application/x-compress', 'application/x-compressed'),
			'zip'	=>	array('application/x-compressed', 'application/x-zip-compressed', 'application/zip', 'multipart/x-zip'),
			'zoo'	=>	'application/octet-stream',
			'zsh'	=>	'text/x-script.zsh'		
		);
		if(isset($mimeTypes[$extension])) {
			return $mimeTypes[$extension];
		}
		else {
			return 'application/x-unknown-content-type';
		}
	}
	
	/**
	 * Przenosi i zmienia nazwy plikow
	 *
	 * @access public
	 * @param string Sciezka do pliku/katalogu zrodlowego
	 * @param string Sciezka do pliku/katalogu docelowego
	 * @param bool Czy nadpisac? //true dla tak
	 * @return void
	 * 
	 */
	public function rename($filePath, $newFilePath, $overwrite=false) {		
		parent::rename($filePath, $newFilePath, $overwrite=false);
	}
	
	/**
	 * Przenosi zmienia nazwy plikow i katalogow. Alias do rename
	 *
	 * @access public
	 * @param string Sciezka do pliku/katalogu zrodlowego
	 * @param string Sciezka do pliku/katalogu docelowego
	 * @param bool Czy nadpisac? //true dla tak
	 * @return void
	 * 
	 */
	public function move($filePath, $newFilePath, $overwrite=false) {
		parent::move($filePath, $newFilePath, $overwrite=false);
	}
	
	/**
	 * Zmienia uprawnienia do plikow/katalogow 
	 * Tylko pliki wlasciciela
	 * 
	 * @access public
	 * @param string Sciezka do pliku/katalogu
	 * @param integer Uprawnienia osemkowo
	 * @return void
	 * 
	 */
	public function chmod($filePath, $newPerms) {
		parent::chmod($filePath, $newPerms);
	}	
	
	/**
	 * Zwraca wlasciwosci pliku/katalogu
	 * 
	 * @access public
	 * @param string Sciezka do pliku/katalogu
	 * @return array
	 * 
	 */
	public function stats($filePath) {	
		return parent::stats($filePath);
	}	
	
}

?>
