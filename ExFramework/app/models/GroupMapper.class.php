<?php

/*
CREATE TABLE `groups_users` (
  `id` int(32) COLLATE utf8_polish_ci NOT NULL AUTO_INCREMENT,
  `group_id` int(32) COLLATE utf8_polish_ci NULL,
  `user_id` int(32) COLLATE utf8_polish_ci NULL,
  PRIMARY KEY  (`id`),
  UNIQUE (`group_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
*/

/*
CREATE TABLE `groups` (
  `id` int(32) COLLATE utf8_polish_ci NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `info` varchar(200) COLLATE utf8_polish_ci NULL,
  `root` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  (`id`),
  UNIQUE (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
*/

class GroupMapper extends DataMapperAbstract {

	protected $_entityClassName = 'Group';
	protected $_entityTableName = 'groups';
	protected $_entityName = 'group';
	
	protected $_fields = array(
		'id'=>array('nameInObj'=>'id', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'primary'=>true, 'auto'=>true, 'default'=>null),
		'name'=>array('nameInObj'=>'name', 'sqlType'=>array('varchar', 100), 'phpType'=>'string'),
		'info'=>array('nameInObj'=>'info', 'sqlType'=>array('varchar', 240), 'phpType'=>'string', 'default'=>null),
		'root'=>array('nameInObj'=>'root', 'sqlType'=>array('int', 1), 'phpType'=>'int', 'default'=>0),
	);
		
	protected $_relations = array(
		'users'=>array('nameInObj'=>'users', 'relation'=>'hasMany', 'mapper'=>'UserMapper', 'class'=>'User', 'through'=>'groups_users'),
		'resources'=>array('nameInObj'=>'resources', 'relation'=>'hasMany', 'mapper'=>'ResourceMapper', 'class'=>'Resource', 'through'=>'groups_resources')
	);
	
	protected $_validatorRules = array(
		'name'=>array(array('ValidatorStringMaxLength', array('maxLength'=>100)), 'allowEmpty'=>false),
		'info'=>array(array('ValidatorStringMaxLength', array('maxLength'=>200)), 'allowEmpty'=>true)
	);	
	
	public function __construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter) {
		parent::__construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter);
	}
	
	public function getAllGroups($limit=null, $offset=null) {
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
