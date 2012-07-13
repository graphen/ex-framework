<?php

/**
 * @interface IViewTemplate
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IViewTemplate extends IView {

	public function setTemplatePath($templatePath);
	public function getTemplatePath();
	//public function fetch($templatePath=null);

}

?>
