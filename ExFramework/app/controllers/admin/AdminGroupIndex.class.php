<?php

class AdminGroupIndex extends AdminGroupList {
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, GroupMapper $groupMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		parent::__construct($request, $session, $i18n, $groupMapper, $viewResolver, $paginator);
	}
	
}

?>
