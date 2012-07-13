<?php

/**
 * @interface IFileHandler
 *
 * @author Przemyslaw Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IFileHandler {
	
	public function open($filePath=null, $mode='r');
	public function read($length=null);
	public function readToBuffer($length=8192);
	public function readLines($lineLength=1024, $linesNumber=null);
	public function readChars($length=null);
	public function readCsv($length=null, $delimiter=',');
	public function writeCsv($data);
	public function write($data=null, $length=null);
	public function rewind();
	public function close();
	public function tell();
	public function seek($offset, $w='');
	public function show();
	public function stats();
	
}

?>
