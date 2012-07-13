<?php

/**
 * @interface IRequest
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IRequest {
	
	public function get($key=null);
	public function rawGet($key=null);
	public function setQuery($key, $value=null);
	public function post($key=null);
	public function rawPost($key=null);
	public function cookie($key=null);
	public function rawCookie($key=null);
	public function server($key=null);
	public function rawServer($key=null);
	public function env($key=null);
	public function rawEnv($key=null);
	public function getHost();
	public function getScriptFolder();
	public function getScriptName();
	public function getRequestMethod();
	public function isRequestMethod($requestMethod);
	public function isPost();
	public function isGet();
	public function isPut();
	public function isDelete();
	public function isAjax();
	public function getIp();
	public function getClientIp();
	public function validIp($ip);
	public function getUserAgent();
	public function getReferer();
	public function getBaseUrl();

}

?>
