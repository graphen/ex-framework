<?php

/**
 * @class DbException
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class DbException extends AppException {
	public function __construct($message, $message2, $code=0, Exception $e=null) {
		parent::__construct($message . ' - ' . $message2, $code, $e);
	}
}

?>
