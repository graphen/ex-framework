<?php

/**
 * @interface IAcl
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IAcl {
	public function getUserGroups($userLogin=null);
	public function getGroups();
	public function getResources();
	public function getUserPermissions($userLogin=null);
	public function hasGroup($groupName, $userLogin);
	public function isAllowed($resourceRule, $userLogin=null);
}

?>
