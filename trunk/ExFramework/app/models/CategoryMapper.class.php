<?php

/*

CREATE TABLE `categories` (
  `id` int(32) COLLATE utf8_polish_ci NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE (`name`)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

*/

class CategoryMapper extends DataMapperAbstract {

	protected $_entityClassName = 'Category';
	protected $_entityTableName = 'categories';
	protected $_entityName = 'category';
	
	protected $_fields = array(
		'id'=>array('nameInObj'=>'id','sqlType'=>array('int', 32), 'phpType'=>'int', 'primary'=>true, 'auto'=>true),
		'name'=>array('nameInObj'=>'name','sqlType'=>array('varchar', 150), 'phpType'=>'string'),
		'info'=>array('nameInObj'=>'info', 'sqlType'=>array('varchar', 240), 'phpType'=>'string', 'default'=>null),		
	);
		
	protected $_relations = array(
		'recipes'=>array('nameInObj'=>'recipes', 'relation'=>'hasMany', 'mapper'=>'RecipeMapper', 'class'=>'Recipe', 'rtable'=>'other', 'rtablename'=>'recipes', 'efkey'=>'category_id'),
	);
	
	protected $_validatorRules = array(
		'name'=>array(array('ValidatorStringMaxLength', array('maxLength'=>150)), 'allowEmpty'=>false),
		'info'=>array(array('ValidatorStringMaxLength', array('maxLength'=>200)), 'allowEmpty'=>true)		
	);		
	
	public function __construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter) {
		parent::__construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter);
	}	
	
	public function getAllCategories($limit=null, $offset=null) {
		$queryParts = array();
		if($limit !== null) {
			$queryParts['limit'] = intval($limit);
		}
		if($offset !== null) {
			$queryParts['offset'] = intval($offset);
		}	
		return parent::getAll($queryParts);
	}	
	
}

?>
