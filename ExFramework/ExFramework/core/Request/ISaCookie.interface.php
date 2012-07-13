<?php

/**
 * @interface ISaCookie
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface ISaCookie extends ISa {

	public function set($name, $value='', $expired=3600, $path=null, $domain=null, $secure=false, $httponly=false);
	public function delete($name, $path=null, $domain=null, $secure=false, $httponly=false);

}

?>
