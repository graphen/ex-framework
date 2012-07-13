<?php

class AdminCategoryIndex extends AdminCategoryList {
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, CategoryMapper $categoryMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		parent::__construct( $request, $session, $i18n, $categoryMapper, $viewResolver, $paginator);
	}	
	
} 

?>
