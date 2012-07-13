<?php

/**
 * @interface IIocContainer
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 * 
 */
interface IIocContainer {
	public function create($className);
}

?>
