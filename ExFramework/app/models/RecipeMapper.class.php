<?php

/*

CREATE TABLE `recipes` (
  `id` int(32) COLLATE utf8_polish_ci NOT NULL AUTO_INCREMENT,
  `category_id` int(32) COLLATE utf8_polish_ci NULL,
  --`part_id` int(32) COLLATE utf8_polish_ci NULL,
  `user_id` int(32) COLLATE utf8_polish_ci NULL,
  `user_name` varchar(80) COLLATE utf8_polish_ci NULL,
  `title` varchar(240) COLLATE utf8_polish_ci NOT NULL,
  `description` text COLLATE utf8_polish_ci NULL,
  `preparation_method` text COLLATE utf8_polish_ci NOT NULL,    
  `portions` int(10) COLLATE utf8_polish_ci NOT NULL,
  `preparation_time` int(10) COLLATE utf8_polish_ci NOT NULL, 
  `created` datetime COLLATE utf8_polish_ci NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime COLLATE utf8_polish_ci NOT NULL DEFAULT '0000-00-00 00:00:00',
  `visitcount` int(20) COLLATE utf8_polish_ci NOT NULL DEFAULT 0,
  `approved` int(1) COLLATE utf8_polish_ci NOT NULL DEFAULT 0,
  PRIMARY KEY  (`id`),
  UNIQUE (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

*/

class RecipeMapper extends DataMapperAbstract {

	protected $_entityClassName = 'Recipe';
	protected $_entityTableName = 'recipes';
	protected $_entityName = 'recipe';
	
	protected $_fields = array(
		'id'=>array('nameInObj'=>'id','sqlType'=>array('int', 32), 'phpType'=>'int', 'primary'=>true, 'auto'=>true, 'default'=>null),
		'category_id'=>array('nameInObj'=>'categoryId', 'sqlType'=>array('int', 32), 'phpType'=>'int', 'foreignKey'=>true, 'default'=>null),
		'user_id'=>array('nameInObj'=>'userId','sqlType'=>array('int', 32), 'phpType'=>'int', 'foreignKey'=>true, 'default'=>null),
		'user_name'=>array('nameInObj'=>'userName', 'sqlType'=>array('varchar', 80), 'phpType'=>'string'),
		'title'=>array('nameInObj'=>'title', 'sqlType'=>array('varchar', 240), 'phpType'=>'string'),
		'description'=>array('nameInObj'=>'description', 'sqlType'=>'text', 'phpType'=>'string'),
		'preparation_method'=>array('nameInObj'=>'preparationMethod', 'sqlType'=>'text', 'phpType'=>'string'),
		'portions'=>array('nameInObj'=>'portions', 'sqlType'=>array('int', 10), 'phpType'=>'int'),
		'preparation_time'=>array('nameInObj'=>'preparationTime', 'sqlType'=>array('int', 10), 'phpType'=>'int'),
		'created'=>array('nameInObj'=>'created', 'sqlType'=>array('datetime', 'Y-m-d H:i:s'), 'phpType'=>array('date', 'Y-m-d H:i:s')),
		'updated'=>array('nameInObj'=>'updated', 'sqlType'=>array('datetime', 'Y-m-d H:i:s'), 'phpType'=>array('date', 'Y-m-d H:i:s')),
		'visitcount'=>array('nameInObj'=>'visitCount', 'sqlType'=>array('int', 20), 'phpType'=>'int', 'default'=>0),
		'approved'=>array('nameInObj'=>'approved', 'sqlType'=>array('int', 1), 'phpType'=>'int', 'default'=>0)
	);
	
	protected $virtualFields = array(
		'cats'=>array('nameInObj'=>'cats', 'phpType'=>'int', 'default'=>null),
		'usr'=>array('nameInObj'=>'usr', 'phpType'=>'int', 'default'=>null),
		'ingredients'=>array('nameInObj'=>'ings', 'phpType'=>'array', 'default'=>null)
	);
	
	protected $_relations = array(
		'categories'=>array('nameInObj'=>'categories', 'relation'=>'hasOne', 'mapper'=>'CategoryMapper', 'class'=>'Category', 'rtable'=>'same', 'rtablename'=>'recipes', 'erfkey'=>'category_id'),
		'users'=>array('nameInObj'=>'users', 'relation'=>'hasOne', 'mapper'=>'UserMapper', 'class'=>'User', 'rtable'=>'same', 'rtablename'=>'recipes', 'erfkey'=>'user_id'),
		'items'=>array('nameInObj'=>'items', 'relation'=>'hasMany', 'mapper'=>'ItemMapper', 'class'=>'Item', 'rtable'=>'other', 'rtablename'=>'items', 'efkey'=>'recipe_id')
	);
	
	protected $_validatorRules = array(
		'cats'=>array('ValidatorIsInteger', 'allowEmpty'=>false),
		'usr'=>array('ValidatorIsInteger', 'allowEmpty'=>true),
		'ingredients'=>array(array('ValidatorIsArray', array('notEmptyCount'=>1)), 'allowEmpty'=>false),
		'userName'=>array(array('ValidatorStringMaxLength', array('maxLength'=>80)), 'allowEmpty'=>true),
		'title'=>array(array('ValidatorStringMaxLength', array('maxLength'=>240)), 'allowEmpty'=>false),
		'description'=>array(array('ValidatorStringMaxLength', array('maxLength'=>2000)), 'allowEmpty'=>true),
		'preparationMethod'=>array(array('ValidatorStringMaxLength', array('maxLength'=>2000)), 'allowEmpty'=>false),
		'portions'=>array('ValidatorIsInteger', array('ValidatorIntegerMaxValue', array('maxValue'=>100)), 'allowEmpty'=>false),
		'preparationTime'=>array('ValidatorIsInteger', array('ValidatorIntegerMaxValue', array('maxValue'=>100000)), 'allowEmpty'=>false),		
	);
		
	protected $_preValidationFilterRules = array(
		'title'=>array('FilterXss', 'FilterStringTrim', 'FilterStripEndLines'), 
		'description'=>array('FilterXss', 'FilterStringTrim', 'FilterStripEndLines'),
		'preparationMethod'=>array('FilterXss', 'FilterStringTrim', 'FilterStripEndLines')		
	);
	
	public function __construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter) {
		parent::__construct($mapperFactory, $entityFactory, $collection, $db, $query, $inflector, $inputValidator, $inputFilter);
	}
	
	public function searchApprovedRecipes($searchKeysArr, $catId=null, $userId=null, $limit=null, $offset=null) {
		$queryParts = array();
		$params = array();
		
		//select
		$queryParts['select'][] = "`recipes`.*";
		//from
		$queryParts['from'][] = "`recipes`";
		//limit,offset
		if($limit !== null) {
			$queryParts['limit'] = intval($limit);
		}
		if($offset !== null) {
			$queryParts['offset'] = intval($offset);
		}	
		//where
		$whereStr = '';
		$where = array();
		foreach($searchKeysArr AS $index=>$str) {
			if(strlen($str) < 2) {
				continue;
			}
			$prm_0 = ':STR_'.$index.'_0';
			$prm_1 = ':STR_'.$index.'_1';
			$prm_2 = ':STR_'.$index.'_2';								
			$params[$prm_0] = $params[$prm_1] = $params[$prm_2] = "%$str%"; 				
			$where[] = trim("`title` LIKE ".$prm_0." OR `description` LIKE ".$prm_1." OR `preparation_method` LIKE ".$prm_2);
		}
		$whereStr = "(" . implode(' OR ', $where) . ")";
		$queryParts['where'][] = trim($whereStr);
		
		if($catId !== null) {
			//join
			$queryParts['join'][] = "JOIN `categories` ON `categories`.`id`=`recipes`.`category_id`";
			//where
			$queryParts['where'][] = "`categories`.`id`=:catId";
			$params[':catId'] = $catId;
		}
		
		if($userId !== null) {
			//join
			$queryParts['join'][] = "JOIN `users` ON `users`.`id`=`recipes`.`user_id`";
			//where
			$queryParts['where'][] = "`users`.`id`=:userId";
			$params[':userId'] = $userId;
		}		

		$queryParts['where'][] = "`recipes`.`approved`=1";		
		return parent::getAll($queryParts, $params);
	}
	
	public function getAllRecipesByCategory($catId, $limit=null, $offset=null) {
		$queryParts = array();
		$params = array();
		
		//select
		$queryParts['select'][] = "`recipes`.*";
		//from
		$queryParts['from'][] = "`recipes`";
		//limit,offset
		if($limit !== null) {
			$queryParts['limit'] = intval($limit);
		}
		if($offset !== null) {
			$queryParts['offset'] = intval($offset);
		}			
		
		//join
		$queryParts['join'][] = "JOIN `categories` ON `categories`.`id`=`recipes`.`category_id`";
		//where
		$queryParts['where'][] = "`categories`.`id`=:catId";
		$params[':catId'] = $catId;
		return parent::getAll($queryParts, $params);
	}
	
	public function getAllApprovedRecipesByCategory($catId, $limit=null, $offset=null) {
		$queryParts = array();
		$params = array();
		
		//select
		$queryParts['select'][] = "`recipes`.*";
		//from
		$queryParts['from'][] = "`recipes`";
		//limit,offset
		if($limit !== null) {
			$queryParts['limit'] = intval($limit);
		}
		if($offset !== null) {
			$queryParts['offset'] = intval($offset);
		}			
		
		//join
		$queryParts['join'][] = "JOIN `categories` ON `categories`.`id`=`recipes`.`category_id`";
		//where
		$queryParts['where'][] = "`categories`.`id`=:catId";
		$params[':catId'] = $catId;

		$queryParts['where'][] = "`recipes`.`approved`=1";
		return parent::getAll($queryParts, $params);
	}	
	
	public function getAllRecipesByUser($userId, $limit=null, $offset=null) {
		$queryParts = array();
		$params = array();
		
		//select
		$queryParts['select'][] = "`recipes`.*";
		//from
		$queryParts['from'][] = "`recipes`";
		//limit,offset
		if($limit !== null) {
			$queryParts['limit'] = intval($limit);
		}
		if($offset !== null) {
			$queryParts['offset'] = intval($offset);
		}			
		
		//join
		$queryParts['join'][] = "JOIN `users` ON `users`.`id`=`recipes`.`user_id`";
		//where
		$queryParts['where'][] = "`users`.`id`=:userId";
		$params[':userId'] = $userId;
		return parent::getAll($queryParts, $params);
	}
	
	public function getAllApprovedRecipesByUser($userId, $limit=null, $offset=null) {
		$queryParts = array();
		$params = array();
		
		//select
		$queryParts['select'][] = "`recipes`.*";
		//from
		$queryParts['from'][] = "`recipes`";
		//limit,offset
		if($limit !== null) {
			$queryParts['limit'] = intval($limit);
		}
		if($offset !== null) {
			$queryParts['offset'] = intval($offset);
		}			
		
		//join
		$queryParts['join'][] = "JOIN `users` ON `users`.`id`=`recipes`.`user_id`";
		//where
		$queryParts['where'][] = "`users`.`id`=:userId";
		$params[':userId'] = $userId;

		$queryParts['where'][] = "`recipes`.`approved`=1";
		return parent::getAll($queryParts, $params);
	}		

	public function getAllRecipesByUserAndCategory($userId, $catId, $limit=null, $offset=null) {
		$queryParts = array();
		$params = array();
		
		//select
		$queryParts['select'][] = "`recipes`.*";
		//from
		$queryParts['from'][] = "`recipes`";
		//limit,offset
		if($limit !== null) {
			$queryParts['limit'] = intval($limit);
		}
		if($offset !== null) {
			$queryParts['offset'] = intval($offset);
		}			
		
		//join
		$queryParts['join'][] = "JOIN `categories` ON `categories`.`id`=`recipes`.`category_id`";
		//where
		$queryParts['where'][] = "`categories`.`id`=:catId";
		$params[':catId'] = $catId;
		//join
		$queryParts['join'][] = "JOIN `users` ON `users`.`id`=`recipes`.`user_id`";
		//where
		$queryParts['where'][] = "`users`.`id`=:userId";
		$params[':userId'] = $userId;
		
		return parent::getAll($queryParts, $params);
	}

	public function getAllApprovedRecipesByUserAndCategory($userId, $catId, $limit=null, $offset=null) {
		$queryParts = array();
		$params = array();
		
		//select
		$queryParts['select'][] = "`recipes`.*";
		//from
		$queryParts['from'][] = "`recipes`";
		//limit,offset
		if($limit !== null) {
			$queryParts['limit'] = intval($limit);
		}
		if($offset !== null) {
			$queryParts['offset'] = intval($offset);
		}			
		
		//join
		$queryParts['join'][] = "JOIN `categories` ON `categories`.`id`=`recipes`.`category_id`";
		//where
		$queryParts['where'][] = "`categories`.`id`=:catId";
		$params[':catId'] = $catId;
		//join
		$queryParts['join'][] = "JOIN `users` ON `users`.`id`=`recipes`.`user_id`";
		//where
		$queryParts['where'][] = "`users`.`id`=:userId";
		$params[':userId'] = $userId;

		$queryParts['where'][] = "`recipes`.`approved`=1";
		return parent::getAll($queryParts, $params);
	}
	
	public function getAllRecipes($limit=null, $offset=null) {
		$queryParts = array();
		if($limit !== null) {
			$queryParts['limit'] = intval($limit);
		}
		if($offset !== null) {
			$queryParts['offset'] = intval($offset);
		}	
		return parent::getAll($queryParts);
	}
	
	public function getAllApprovedRecipes($limit=null, $offset=null) {
		$queryParts = array();
		if($limit !== null) {
			$queryParts['limit'] = intval($limit);
		}
		if($offset !== null) {
			$queryParts['offset'] = intval($offset);
		}
		$queryParts['where'][] = "`recipes`.`approved`=1";
		return parent::getAll($queryParts);
	}	
	
	public function countAllByUser($userId) {
		$queryParts = array();
		$queryParts['join'][] = "JOIN `users` ON `recipes`.`user_id`=`users`.`id`";
		$queryParts['where'][] = "`user_id`=".$userId;
		return parent::countAll($queryParts);
	}		
	
	public function countAllApproved() {
		$queryParts = array();
		$queryParts['where'][] = "`recipes`.`approved`=1";
		return parent::countAll($queryParts);
	}
	
	public function countAllApprovedByCategory($catId) {
		$queryParts = array();
		$queryParts['join'][] = "JOIN `categories` ON `recipes`.`category_id`=`categories`.`id`";
		$queryParts['where'][] = "`recipes`.`approved`=1";
		$queryParts['where'][] = "`category_id`=".$catId;
		return parent::countAll($queryParts);
	}

	public function countAllApprovedByUser($userId) {
		$queryParts = array();
		$queryParts['join'][] = "JOIN `users` ON `recipes`.`user_id`=`users`.`id`";
		$queryParts['where'][] = "`recipes`.`approved`=1";
		$queryParts['where'][] = "`user_id`=".$userId;
		return parent::countAll($queryParts);
	}
	
	public function countAllApprovedByUserAndCategory($userId, $catId) {
		$queryParts = array();
		$queryParts['join'][] = "JOIN `users` ON `recipes`.`user_id`=`users`.`id`";
		$queryParts['join'][] = "JOIN `categories` ON `recipes`.`category_id`=`categories`.`id`";
		$queryParts['where'][] = "`recipes`.`approved`=1";
		$queryParts['where'][] = "`user_id`=".$userId;
		$queryParts['where'][] = "`category_id`=".$catId;
		return parent::countAll($queryParts);
	}	
	
	public function countAllApprovedSearched($searchKeysArr, $catId, $userId) {
		//where
		$whereStr = '';
		$where = array();
		foreach($searchKeysArr AS $index=>$str) {
			if(strlen($str) < 2) {
				continue;
			}
			$prm_0 = ':STR_'.$index.'_0';
			$prm_1 = ':STR_'.$index.'_1';
			$prm_2 = ':STR_'.$index.'_2';								
			$params[$prm_0] = $params[$prm_1] = $params[$prm_2] = "%$str%"; 				
			$where[] = trim("`title` LIKE ".$prm_0." OR `description` LIKE ".$prm_1." OR `preparation_method` LIKE ".$prm_2);
		}
		$whereStr = "(" . implode(' OR ', $where) . ")";
		$queryParts['where'][] = trim($whereStr);
		
		if($catId !== null) {
			//join
			$queryParts['join'][] = "JOIN `categories` ON `categories`.`id`=`recipes`.`category_id`";
			//where
			$queryParts['where'][] = "`categories`.`id`=:catId";
			$params[':catId'] = $catId;
		}
		
		if($userId !== null) {
			//join
			$queryParts['join'][] = "JOIN `users` ON `users`.`id`=`recipes`.`user_id`";
			//where
			$queryParts['where'][] = "`users`.`id`=:userId";
			$params[':userId'] = $userId;
		}
		return parent::countAll($queryParts, $params);		
	}	
	
}

?>
