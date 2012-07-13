<?php

class AdminUnitIndex extends AdminUnitList {
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UnitMapper $unitMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		parent::__construct($request, $session, $i18n, $unitMapper, $viewResolver, $paginator);
	}	
	
}

?>
