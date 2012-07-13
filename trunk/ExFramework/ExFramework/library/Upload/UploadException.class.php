<?php

/**
 * @class UploadException
 *
 * @author Przemyslaw Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class UploadException extends AppException {

	public function __construct($message, $code=0, Exception $e=null) {
		parent::__construct($message, $code, $e);
	}
			
}

?>
