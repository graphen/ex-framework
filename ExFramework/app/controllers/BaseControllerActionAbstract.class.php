<?php

abstract class BaseControllerActionAbstract extends ControllerActionAbstract implements IController {

	protected $_widgetsDefinitions = array('CategoryMenu'=>'/categoryMenu', 'PublicMenu'=>'/publicMenu', 'SearchBlock'=>'/searchBlock', 'UserMenu'=>'/userMenu', 'ProfileBlock'=>'/profileBlock');
	
}

?>
