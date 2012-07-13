<?php

/*
CREATE TABLE `menus` (
  `id` int(32) COLLATE utf8_polish_ci NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `info` varchar(200) COLLATE utf8_polish_ci NULL,
  PRIMARY KEY  (`id`),
  UNIQUE (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
*/

class MenuMapper extends DataMapperAbstract {

	protected $_entityClassName = 'Menu';
	protected $_entityTableName = 'menus';
	protected $_entityName = 'menu';
	
	protected $_fields = array(
		'id'=>array('nameInObj'=>'id', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'primary'=>true, 'auto'=>true, 'default'=>null),
		'title'=>array('nameInObj'=>'title', 'sqlType'=>array('varchar', 100), 'phpType'=>'string'),
		'info'=>array('nameInObj'=>'info', 'sqlType'=>array('varchar', 200), 'phpType'=>'string', 'default'=>null)
	);
		
	protected $_relations = array(
		'entries'=>array('nameInObj'=>'entries', 'relation'=>'hasMany', 'mapper'=>'EntryMapper', 'class'=>'Entry', 'rtable'=>'other', 'rtablename'=>'entries', 'efkey'=>'menu_id'),
	);
	
	protected $_validatorRules = array(
		'title'=>array(array('ValidatorStringMaxLength', array('maxLength'=>100)), 'allowEmpty'=>false),
		'info'=>array(array('ValidatorStringMaxLength', array('maxLength'=>200)), 'allowEmpty'=>true)
	);	
	
	public function __construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter) {
		parent::__construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter);
	}	
		
}

?>
