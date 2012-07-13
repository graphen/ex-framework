<?php

/**
 * @interface IDb
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IDb {
	
	public function isConnected();
	public function connect();
	public function beginTransaction();
	public function commit();
	public function errorCode();
	public function errorInfo();
	public function exec($stm);
	public function getAttribute($attribute);
	public function getAvailableDrivers();
	public function lastInsertId();
	public function prepare($query, $driverOptions=array());
	public function query($query);
	public function quote($str, $parameterType=ExDb::PARAM_STR);
	public function rollBack();
	public function setAttribute($attribute, $value);

}

?>
