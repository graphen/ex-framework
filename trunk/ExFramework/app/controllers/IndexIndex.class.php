<?php

class IndexIndex extends RecipeList {
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, RecipeMapper $recipeMapper, CategoryMapper $categoryMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		parent::__construct($request, $session, $i18n, $recipeMapper, $categoryMapper, $viewResolver, $paginator);
	}	
	
}

?>
