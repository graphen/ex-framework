<?php

/**
 * @interface IDbResult
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IDbResult {
	
	public function bindColumn($column, &$param, $type=ExDb::PARAM_STR);
	public function bindParam($parameter, &$variable, $type=ExDb::PARAM_STR, $length=null);
	public function bindValue($parameter, $variable, $type=ExDb::PARAM_STR);
	public function closeCursor();
	public function columnCount();
	public function debugDumpParams();
	public function errorCode();
	public function errorInfo();
	public function execute($inputParameters=array());
	public function fetch($fetchStyle=ExDb::FETCH_BOTH);
	public function fetchAll($fetchStyle=ExDb::FETCH_BOTH);
	public function fetchColumn($columnNumber=0);
	public function fetchObject($className="stdClass", $ctorArgs=array());
	public function getAttribute($attribute);
	public function getColumnMeta($column);
	public function nextRowset();
	public function rowCount();
	public function setAttribute($attribute, $value);
	public function setFetchMode($mode);	//todo Sprawdzic dzialanie w PDO

}

?>
