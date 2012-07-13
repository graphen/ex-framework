<?php

/**
 * @interface IRouter
 *
 * @author Przemyslaw Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IRouter {
	
	public function route();
	public function getHost();
	public function getDirName();
	public function getBaseName();
	public function getController();
	public function getAction();
	public function getArgs();
	public function getArea();
	public function getQueryString();
	public function getQuerySegments();
	public function getRString();
	public function getRSegments();
	public function makeQueryString($controller=null, $action=null, $args=null, $area=null);
	public function makeUrl($controller=null, $action=null, $args=null, $url=null, $proto=null, $host=null, $port=null, $dirName=null, $baseName=null, $area=null);

}

?>
