<?php

/**
 * @abstract class DbAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class DbAbstract implements IDb {
	
	const PARAM_BOOL = 5;
	const PARAM_NULL = 0;
	const PARAM_INT = 1;
	const PARAM_STR = 2;
	const PARAM_LOB = 3;
	const PARAM_INPUT_OUTPUT = -2147483648;
	
	const FETCH_ASSOC = 2;
	const FETCH_NUM = 3;
	const FETCH_BOTH = 4;
	const FETCH_OBJ = 5;
	const FETCH_BOUND = 6;
	const FETCH_COLUMN = 7;
	const FETCH_CLASS = 8;
	const FETCH_INTO = 9;
	const FETCH_LAZY = 1;
	
	const ATTR_AUTOCOMMIT = 0;
	const ATTR_PERSISTENT = 12;
	const ATTR_SERVER_VERSION = 4;
	const ATTR_CLIENT_VERSION = 5;
	const ATTR_DRIVER_NAME = 16;
	const ATTR_CURSOR_NAME = 9;
	const MYSQL_ATTR_INIT_COMMAND = 1002;
	
}

?>
