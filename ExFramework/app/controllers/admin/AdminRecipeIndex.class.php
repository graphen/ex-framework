<?php

class AdminRecipeIndex extends AdminRecipeList {
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, RecipeMapper $recipeMapper, CategoryMapper $categoryMapper, UserMapper $userMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		parent::__construct($request, $session, $i18n, $recipeMapper, $categoryMapper, $userMapper, $viewResolver, $paginator);
	}	
	
}

?>
