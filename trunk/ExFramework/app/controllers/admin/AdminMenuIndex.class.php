<?php

class AdminMenuIndex extends AdminMenuList {
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, MenuMapper $menuMapper, IViewResolver $viewResolver) {
		parent::__construct($request, $session, $i18n, $menuMapper, $viewResolver);
	}	
	
}

?>
