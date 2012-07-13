<?php

/**
 * @interface Mail
 * 
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IMail {
	
	public function send($mailTo, $mailCc, $mailBcc, $mailSubject, $mailBody, $mailHeaders, $mailFrom);	
	
}

?>
