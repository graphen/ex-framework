<?php

/**
 * @interface ISession
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface ISession {
	
	public function regenerateId();
	public function writeClose();
	public function start();
	public function stop();
	public function destroy();
	public function setFlashData($var, $value);
	public function getFlashData($var);
	public function keepFlashData($var);

}

?>
