<?php

/**
 * @interface IAuth
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IAuth {

	public function login($userLogin, $userPass=null, $authMethod=null);
	public function check();
	public function logout();
	public function getErrorCode();
	public function getErrorMessage();
	public function getAuthAdapter();
	
}

?>
