<?php

/*
CREATE TABLE `users` (
  `id` int(32) COLLATE utf8_polish_ci NOT NULL AUTO_INCREMENT,
  `login` varchar(60) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_polish_ci NOT NULL,
  `firtname` varchar(60) COLLATE utf8_polish_ci NULL,
  `lastname` varchar(80) COLLATE utf8_polish_ci NULL,
  `email` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `url` varchar(100) COLLATE utf8_polish_ci NULL,
  `city` varchar(80) COLLATE utf8_polish_ci NULL,
  `address` varchar(200) COLLATE utf8_polish_ci NULL,
  `phone` varchar(20) COLLATE utf8_polish_ci NULL,
  `info` varchar(240) COLLATE utf8_polish_ci NULL,
  `registerdate` datetime COLLATE utf8_polish_ci NOT NULL DEFAULT '000-00-00 00:00:00',
  `code` varchar(32) COLLATE utf8_polish_ci NULL,
  `status` int(1) COLLATE utf8_polish_ci NOT NULL DEFAULT 0,
  `lastaccess` datetime COLLATE utf8_polish_ci NOT NULL DEFAULT '000-00-00 00:00:00',
  `visitcount` int(32) COLLATE utf8_polish_ci NOT NULL DEFAULT 0,
  PRIMARY KEY  (`id`),
  UNIQUE (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
*/

class UserMapper extends DataMapperAbstract {

	protected $_entityClassName = 'User';
	protected $_entityTableName = 'users';
	protected $_entityName = 'user';
	protected $_entityCreatedColumnName = 'registerdate';
	
	protected $_fields = array(
		'id'=>array('nameInObj'=>'id', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'primary'=>true, 'auto'=>true, 'default'=>null),
		'login'=>array('nameInObj'=>'login', 'sqlType'=>array('varchar', 60), 'phpType'=>'string'),
		'password'=>array('nameInObj'=>'password', 'sqlType'=>array('varchar', 60), 'phpType'=>'string'),
		'firstname'=>array('nameInObj'=>'firstName', 'sqlType'=>array('varchar', 60), 'phpType'=>'string', 'default'=>null),
		'lastname'=>array('nameInObj'=>'lastName', 'sqlType'=>array('varchar', 80), 'phpType'=>'string', 'default'=>null),
		'email'=>array('nameInObj'=>'email', 'sqlType'=>array('varchar', 100), 'phpType'=>'string'),
		'url'=>array('nameInObj'=>'url', 'sqlType'=>array('varchar', 100), 'phpType'=>'string', 'default'=>null),
		'city'=>array('nameInObj'=>'city', 'sqlType'=>array('varchar', 80), 'phpType'=>'string', 'default'=>null),				
		'address'=>array('nameInObj'=>'address', 'sqlType'=>array('varchar', 200), 'phpType'=>'string', 'default'=>null),
		'phone'=>array('nameInObj'=>'phone', 'sqlType'=>array('varchar', 20), 'phpType'=>'string', 'default'=>null),
		'info'=>array('nameInObj'=>'info', 'sqlType'=>'text', 'phpType'=>'string', 'default'=>null),	
		'registerdate'=>array('nameInObj'=>'registerDate', 'sqlType'=>array('datetime', 'Y-m-d H:i:s'), 'phpType'=>array('date', 'Y-m-d H:i:s'), 'default'=>'0000-00-00 00:00:00'),	
		'code'=>array('nameInObj'=>'code', 'sqlType'=>array('varchar', 32), 'phpType'=>'string', 'default'=>null),
		'status'=>array('nameInObj'=>'status', 'sqlType'=>array('int', 1), 'phpType'=>'int', 'default'=>0),
		'lastaccess'=>array('nameInObj'=>'lastAccess', 'sqlType'=>array('datetime', 'Y-m-d H:i:s'), 'phpType'=>array('date', 'Y-m-d H:i:s'), 'default'=>'0000-00-00 00:00:00'),
		'visitcount'=>array('nameInObj'=>'visitCount', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'default'=>0),
	);
	
	protected $_virtualFields = array(
		'password_confirmation'=>array('nameInObj'=>'passwordConfirmation', 'default'=>null),
		'new_password'=>array('nameInObj'=>'newPassword', 'default'=>null)
	);	
	
	protected $_relations = array(
		'groups'=>array('nameInObj'=>'groups', 'relation'=>'hasMany', 'mapper'=>'GroupMapper', 'class'=>'Group', 'through'=>'groups_users'),
		'recipes'=>array('nameInObj'=>'recipes', 'relation'=>'hasMany', 'mapper'=>'RecipeMapper', 'class'=>'Recipe', 'rtable'=>'other', 'rtablename'=>'recipes', 'efkey'=>'user_id')	
	);
	
	protected $_validatorRules = array(
		'firstName'=>array(array('ValidatorStringMaxLength', array('maxLength'=>60)), 'allowEmpty'=>true),
		'lastName'=>array(array('ValidatorStringMaxLength', array('maxLength'=>80)), 'allowEmpty'=>true),
		'email'=>array('ValidatorEmail', 'allowEmpty'=>false, 'breakChainOnFailure' => true),
		'url'=>array('ValidatorUrl', 'allowEmpty'=>true),
		'city'=>array(array('ValidatorStringMaxLength', array('maxLength'=>80)), 'allowEmpty'=>true),		
		'address'=>array(array('ValidatorStringMaxLength', array('maxLength'=>200)), 'allowEmpty'=>true),
		'phone'=>array(array('ValidatorStringMaxLength', array('maxLength'=>20)), 'allowEmpty'=>true),
		'info'=>array(array('ValidatorStringMaxLength', array('maxLength'=>2000)), 'allowEmpty'=>true),
		'status'=>array(array('ValidatorIntegerMaxValue', array('maxValue'=>1)), array('ValidatorIntegerMinValue', array('minValue'=>0)), 'allowEmpty'=>false)
	);
	
	protected $_insertValidatorRules = array(
		'login'=>array(array('ValidatorDbUnique', array('tableName'=>'users', 'fieldName'=>'login')), array('ValidatorStringMinLength', array('minLength'=>6)), array('ValidatorStringMaxLength', array('maxLength'=>60)), 'allowEmpty'=>false, 'breakChainOnFailure' => true),
		'password'=>array(array('ValidatorStringMinLength', array('minLength'=>6)), array('ValidatorStringMaxLength', array('maxLength'=>60)), 'allowEmpty'=>false, 'breakChainOnFailure' => true),
		'passwordConfirmation'=>array('ValidatorStringEqual', 'fields'=>array('password', 'passwordConfirmation'))
	);
	
	protected $_updateValidatorRules = array(
		//'login'=>array(array('ValidatorDbUnique', array('tableName'=>'users', 'fieldName_1'=>'login', 'fieldName_2'=>'id'), 'fields'=>array('login', 'id')), array('ValidatorStringMinLength', array('minLength'=>6)), array('ValidatorStringMaxLength', array('maxLength'=>60)), 'allowEmpty'=>false, 'breakChainOnFailure' => true),
		'login'=>array(array('ValidatorStringMinLength', array('minLength'=>6)), array('ValidatorStringMaxLength', array('maxLength'=>60)), 'allowEmpty'=>false, 'breakChainOnFailure' => true),
		'newPassword'=>array(array('ValidatorStringMinLength', array('minLength'=>6)), array('ValidatorStringMaxLength', array('maxLength'=>60)), 'allowEmpty'=>true, 'breakChainOnFailure' => true),
		'passwordConfirmation'=>array('ValidatorStringEqual', 'fields'=>array('newPassword', 'passwordConfirmation'))	
	);
	
	protected $_postValidationFilterRules = array(
		'password'=>array('FilterMd5')
	);	
	
	public function __construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter) {
		parent::__construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter);
	}
	
	public function getAllUsers($limit=null, $offset=null) {
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
