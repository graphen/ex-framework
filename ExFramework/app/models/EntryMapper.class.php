<?php

/*
CREATE TABLE `entries` (
  `id` int(32) COLLATE utf8_polish_ci NOT NULL AUTO_INCREMENT,
  `menu_id` int(32) COLLATE utf8_polish_ci NULL, 
  `title` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `url` varchar(100) COLLATE utf8_polish_ci NOT NULL,  
  `description` varchar(200) COLLATE utf8_polish_ci NULL,
  PRIMARY KEY  (`id`),
  UNIQUE (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
*/

class EntryMapper extends DataMapperAbstract {

	protected $_entityClassName = 'Entry';
	protected $_entityTableName = 'entries';
	protected $_entityName = 'entry';
	
	protected $_fields = array(
		'id'=>array('nameInObj'=>'id', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'primary'=>true, 'auto'=>true, 'default'=>null),
		'menu_id'=>array('nameInObj'=>'recipeId', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'foreignKey'=>true, 'default'=>null),		
		'title'=>array('nameInObj'=>'title', 'sqlType'=>array('varchar', 100), 'phpType'=>'string'),
		'url'=>array('nameInObj'=>'url', 'sqlType'=>array('varchar', 100), 'phpType'=>'string'),
		'description'=>array('nameInObj'=>'description', 'sqlType'=>array('varchar', 200), 'phpType'=>'string', 'default'=>null)
	);
	
	protected $_virtualFields = array(
		'menuId'=>array('nameInObj'=>'menuId', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'default'=>null)
	);
		
	protected $_relations = array(
		'menus'=>array('nameInObj'=>'menus', 'relation'=>'hasOne', 'mapper'=>'MenuMapper', 'class'=>'Menu', 'rtable'=>'same', 'rtablename'=>'entries', 'erfkey'=>'menu_id')
	);
	
	protected $_validatorRules = array(
		'title'=>array(array('ValidatorStringMaxLength', array('maxLength'=>100)), 'allowEmpty'=>false),
		'url'=>array(array('ValidatorUrl', array('maxLength'=>100)), 'allowEmpty'=>false),
		'description'=>array(array('ValidatorStringMaxLength', array('maxLength'=>200)), 'allowEmpty'=>true),
		'menuId'=>array('ValidatorIsInteger', 'allowEmpty'=>false)
	);	
	
	public function __construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter) {
		parent::__construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter);
	}	
	
}

?>
