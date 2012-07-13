<?php

abstract class AdminBaseControllerActionAbstract extends ControllerActionAbstract implements IController {

	protected $_widgetsDefinitions = array('AdminMenu'=>'/adminMenu', 'AdminAuthBlock'=>'/adminAuthBlock');
	
}

?>
