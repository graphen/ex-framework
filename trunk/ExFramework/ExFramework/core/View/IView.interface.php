<?php

/**
 * @interface IView
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IView {

	public function assign($var, $value);
	public function fetch();
	public function getMimeType();
	public function setMimeType($mimeType);	

}

?>
