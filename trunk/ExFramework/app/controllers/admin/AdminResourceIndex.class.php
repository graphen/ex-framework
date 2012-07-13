<?php

class AdminResourceIndex extends AdminResourceList {
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, ResourceMapper $resourceMapper, GroupMapper $groupMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		parent::__construct($request, $session, $i18n, $resourceMapper, $groupMapper, $viewResolver, $paginator);
	}
	
}

?>
