<?php

/**
 * @class ViewResolverException
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class ViewResolverException extends AppException {
	
	public function __construct($message, $code=0, Exception $e=null) {
		parent::__construct($message, $code, $e);
	}
	
}

?>
