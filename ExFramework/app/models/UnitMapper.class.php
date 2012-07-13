<?php

/*
CREATE TABLE `units` (
  `id` int(32) COLLATE utf8_polish_ci NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `abbreviation` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_polish_ci NULL,
  PRIMARY KEY  (`id`),
  UNIQUE (`name`),
  UNIQUE (`abbreviation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
*/

class UnitMapper extends DataMapperAbstract {

	protected $_entityClassName = 'Unit';
	protected $_entityTableName = 'units';
	protected $_entityName = 'unit';
	
	protected $_fields = array(
		'id'=>array('nameInObj'=>'id', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'primary'=>true, 'auto'=>true, 'default'=>null),
		'name'=>array('nameInObj'=>'name', 'sqlType'=>array('varchar', 100), 'phpType'=>'string'),
		'abbreviation'=>array('nameInObj'=>'abbreviation', 'sqlType'=>array('varchar', 50), 'phpType'=>'string'),
		'description'=>array('nameInObj'=>'description', 'sqlType'=>array('varchar', 200), 'phpType'=>'string', 'default'=>null),
	);
		
	protected $_relations = array(
		'items'=>array('nameInObj'=>'items', 'relation'=>'hasMany', 'mapper'=>'ItemMapper', 'class'=>'Resource', 'rtable'=>'other', 'rtablename'=>'items', 'efkey'=>'ingredient_id')
	);
	
	protected $_validatorRules = array(
		'name'=>array(array('ValidatorStringMaxLength', array('maxLength'=>100)), 'allowEmpty'=>false),
		'abbreviation'=>array(array('ValidatorStringMaxLength', array('maxLength'=>50)), 'allowEmpty'=>false),
		'description'=>array(array('ValidatorStringMaxLength', array('maxLength'=>200)), 'allowEmpty'=>true)
	);	
	
	public function __construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter) {
		parent::__construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter);
	}	
	
	public function getAllUnits($limit=null, $offset=null) {
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
