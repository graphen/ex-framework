<?php

$dirPath = realpath(dirname(__FILE__));
require_once($dirPath."/config/lang/eng.php");
require_once($dirPath."/tcpdf.php");


class Html2Pdf extends TCPDF {	

	public function __construct($orientation='L', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false) {
		return parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
	}

}

?>
