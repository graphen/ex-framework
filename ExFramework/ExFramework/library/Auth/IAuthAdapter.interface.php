<?php

/**
 * @interface IAuthAdapter
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IAuthAdapter {
	
	public function auth($login, $password=null);
	public function getErrorCode();
	public function getErrorMessage();
	public function getPasswordCryptMethod();
	public function setPasswordCryptMethod($cryptMethod);
	public function getUserLogin();
	
}

?>
