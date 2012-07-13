<?php

class AdminUserIndex extends AdminUserList {

	public function __construct(IRequest $request, ISession $session, II18n $i18n, UserMapper $userMapper, GroupMapper $groupMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		parent::__construct($request, $session, $i18n, $userMapper, $groupMapper, $viewResolver, $paginator);
	}
	
}

?>
