<?php

/*
CREATE TABLE `ingredients` (
  `id` int(32) COLLATE utf8_polish_ci NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `info` varchar(200) COLLATE utf8_polish_ci NULL,
  PRIMARY KEY  (`id`),
  UNIQUE (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
*/

class IngredientMapper extends DataMapperAbstract {

	protected $_entityClassName = 'Ingredient';
	protected $_entityTableName = 'ingredients';
	protected $_entityName = 'ingredient';
	
	protected $_fields = array(
		'id'=>array('nameInObj'=>'id', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'primary'=>true, 'auto'=>true, 'default'=>null),
		'name'=>array('nameInObj'=>'name', 'sqlType'=>array('varchar', 100), 'phpType'=>'string'),
		'info'=>array('nameInObj'=>'info', 'sqlType'=>array('varchar', 200), 'phpType'=>'string', 'default'=>null),
	);
		
	protected $_relations = array(
		'items'=>array('nameInObj'=>'items', 'relation'=>'hasMany', 'mapper'=>'ItemMapper', 'class'=>'Resource', 'rtable'=>'other', 'rtablename'=>'items', 'efkey'=>'ingredient_id')
	);
	
	protected $_validatorRules = array(
		'name'=>array(array('ValidatorStringMaxLength', array('maxLength'=>100)), 'allowEmpty'=>false),
		'info'=>array(array('ValidatorStringMaxLength', array('maxLength'=>240)), 'allowEmpty'=>true)
	);	
	
	public function __construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter) {
		parent::__construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter);
	}	
	
	public function getAllIngredients($limit=null, $offset=null) {
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
