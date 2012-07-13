<?php

/**
 * @class AuthException
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class AuthException extends AppException {
	
	public function __construct($message, $code=0, Exception $e=null) {
		parent::__construct($message, $code, $e);
	}

}

?>
