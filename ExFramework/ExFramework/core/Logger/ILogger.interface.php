<?php

/**
 * @interface ILogger
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface ILogger {

	public function setLogEnabled($logEnabled);
	public function logException(Exception $exception);
	public function log($message, $level='Error', $file=null, $line=null);
	public function getLogs();

}

?>
