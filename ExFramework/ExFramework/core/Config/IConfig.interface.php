<?php

/**
 * @interface IConfig
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IConfig {

	public function addConfigFile($fileName);
	public function getConfig($dataIndex);
	public function getConfigGroup($group);
	public function getConfigs();

}

?>
