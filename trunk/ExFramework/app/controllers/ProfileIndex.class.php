<?php

class ProfileIndex extends ProfileView {
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, UserMapper $userMapper, GroupMapper $groupMapper, IViewResolver $viewResolver) {
		parent::__construct($request, $session, $i18n, $userMapper, $groupMapper, $viewResolver);
	}
	
}

?>
