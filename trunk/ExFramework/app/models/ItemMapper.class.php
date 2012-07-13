<?php

/*
CREATE TABLE `items` (
  `id` int(32) COLLATE utf8_polish_ci NOT NULL AUTO_INCREMENT,
  `recipe_id` int(32) COLLATE utf8_polish_ci NULL,
  `ingredient_id` int(32) COLLATE utf8_polish_ci NULL,
  `unit_id` int(32) COLLATE utf8_polish_ci NULL, 
  `amount` int(32) COLLATE utf8_polish_ci NULL,
  PRIMARY KEY  (`id`),
  --UNIQUE (`recipe_id`, `ingredient_id`, `unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
*/

class ItemMapper extends DataMapperAbstract {

	protected $_entityClassName = 'Item';
	protected $_entityTableName = 'items';
	protected $_entityName = 'item';
	
	protected $_fields = array(
		'id'=>array('nameInObj'=>'id', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'primary'=>true, 'auto'=>true, 'default'=>null),
		'recipe_id'=>array('nameInObj'=>'recipeId', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'foreignKey'=>true, 'default'=>null),
		'ingredient_id'=>array('nameInObj'=>'ingredientId', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'foreignKey'=>true, 'default'=>null),		
		'unit_id'=>array('nameInObj'=>'unitId', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'foreignKey'=>true, 'default'=>null),
		'amount'=>array('nameInObj'=>'amount', 'sqlType'=>array('int', 32), 'phpType'=>'int'),
	);
		
	protected $_relations = array(
		'recipes'=>array('nameInObj'=>'recipes', 'relation'=>'hasOne', 'mapper'=>'RecipeMapper', 'class'=>'Recipe', 'rtable'=>'same', 'rtablename'=>'items', 'erfkey'=>'recipe_id'),
		'ingredients'=>array('nameInObj'=>'ingredients', 'relation'=>'hasOne', 'mapper'=>'IngredientMapper', 'class'=>'Ingredient', 'rtable'=>'same', 'rtablename'=>'items', 'erfkey'=>'ingredient_id'),
		'units'=>array('nameInObj'=>'units', 'relation'=>'hasOne', 'mapper'=>'UnitMapper', 'class'=>'Unit', 'rtable'=>'same', 'rtablename'=>'items', 'erfkey'=>'unit_id')
	);
	
	protected $_validatorRules = array(
		'amount'=>array('ValidatorIsNumeric', 'allowEmpty'=>true),
	);	
	
	public function __construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter) {
		parent::__construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter);
	}	
	
}

?>
