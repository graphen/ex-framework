<?php

/**
 * @interface IParser
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IParser {

	public function addFile($fileName);
	public function getData($dataIndex);
	public function getDataGroup($group);
	public function getAll();

}

?>
