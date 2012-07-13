<?php

class RecipeSearch extends BaseControllerActionAbstract implements IController {
	
	protected $_request = null;
	
	protected $_templateName = '';
	protected $_layoutName = '';
	
	public function __construct(IRequest $request) {
		$this->_request = $request;
	}
	
	public function execute() {
		
		//pobieranie danych z zadania i sesji
		$baseUrl = $this->_request->getBaseUrl();
		$catId = $this->_request->post('cId');
		$userId = $this->_request->post('uId');
		$searchQuery = $this->_request->post('q');

		$catIdStr = '';
		if(($catId != null) && ($catId != '')) {
			$catId = (int)$catId;
			$catIdStr = '/cId/'.$catId;
		}
		$userIdStr = '';
		if(($userId != null) && ($userId != '')) {
			$userId = (int)$userId;
			$userIdStr = '/uId/'.$userId;
		}
		
		if(($searchQuery == null) || ($searchQuery == '')) {
			$this->redirect($baseUrl.'/recipe/list'.$catIdStr.$userIdStr);
		}
		else {
			$searchQueryEncoded = urlencode(htmlspecialchars($searchQuery));
			$searchQueryEncodedStr = '/q/'. $searchQueryEncoded;
			$this->redirect($baseUrl.'/recipe/list'.$searchQueryEncodedStr.$catIdStr.$userIdStr);
		}
		
	}
	
}

?>
