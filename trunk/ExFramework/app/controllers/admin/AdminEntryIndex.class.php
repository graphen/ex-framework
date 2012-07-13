<?php

class AdminEntryIndex extends AdminEntryList {
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, EntryMapper $entryMapper, MenuMapper $menuMapper, IViewResolver $viewResolver) {
		parent::__construct($request, $session, $i18n, $entryMapper, $menuMapper, $viewResolver);
	}	
	
}

?>
