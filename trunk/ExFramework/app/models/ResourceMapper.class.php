<?php

/*

CREATE TABLE `groups_resources` (
  `id` int(32) COLLATE utf8_polish_ci NOT NULL AUTO_INCREMENT,
  `group_id` int(32) COLLATE utf8_polish_ci NULL,
  `resource_id` int(32) COLLATE utf8_polish_ci NULL,
  PRIMARY KEY  (`id`),
  UNIQUE (`group_id`, `resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `resources` (
  `id` int(32) COLLATE utf8_polish_ci NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_polish_ci NULL,
  `resource` varchar(240) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE (`name`),
  UNIQUE (`resource`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

*/

class ResourceMapper extends DataMapperAbstract {

	protected $_entityClassName = 'Resource';
	protected $_entityTableName = 'resources';
	protected $_entityName = 'resource';
	
	protected $_fields = array(
		'id'=>array('nameInObj'=>'id', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'primary'=>true, 'auto'=>true, 'default'=>null),
		'name'=>array('nameInObj'=>'name', 'sqlType'=>array('varchar', 60), 'phpType'=>'string', 'default'=>null),
		'resource'=>array('nameInObj'=>'resource', 'sqlType'=>array('varchar', 240), 'phpType'=>'string')		
	);
		
	protected $_relations = array(
		'groups'=>array('nameInObj'=>'groups', 'relation'=>'hasMany', 'mapper'=>'GroupMapper', 'class'=>'Group', 'through'=>'groups_resources')
	);
	
	protected $_validatorRules = array(
		'name'=>array(array('ValidatorStringMaxLength', array('maxLength'=>60)), 'allowEmpty'=>true),
		'resource'=>array(array('ValidatorStringMaxLength', array('maxLength'=>240)), 'allowEmpty'=>false)
	);	
	
	public function __construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter) {
		parent::__construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter);
	}
	
	public function getAllResources($limit=null, $offset=null) {
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
