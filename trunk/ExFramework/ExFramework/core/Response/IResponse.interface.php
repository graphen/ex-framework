<?php

/**
 * @interface IResponse
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IResponse {
	
	public function setHeaders($headers=array());
	public function setOutputView($outputView);
	public function getOutputView();
	public function addHeader($headerContent, $replace=true);
	public function sendOutput();
	public function sendStatusHeader($code='200', $message='');

}

?>
