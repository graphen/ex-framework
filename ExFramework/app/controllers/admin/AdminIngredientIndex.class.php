<?php

class AdminIngredientIndex extends AdminIngredientList {
	
	public function __construct(IRequest $request, ISession $session, II18n $i18n, IngredientMapper $ingredientMapper, IViewResolver $viewResolver, IPaginator $paginator) {
		parent::__construct($request, $session, $i18n, $ingredientMapper, $viewResolver, $paginator); 
	}
	
}

?>
