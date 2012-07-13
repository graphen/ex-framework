<?php

/*Application Global Variables*/
$config['app']['appPath'] = '/home/przemek/public_html/www.cookbook.com/app';
$config['app']['url'] = 'http://www.cookbook.com';
$config['app']['defaultCharset'] = 'UTF-8';

/*[Router]*/
$config['router']['urlMode'] = '_MOD_REWRITE';//'_REQUEST_URI';
$config['router']['useAssoc'] = 1;
$config['router']['urlSuffix'] = '.html';
$config['router']['defaultController'] = 'index';
$config['router']['defaultAction'] = 'index';
$config['router']['host'] = 'localhost';
$config['router']['controllerKey'] = 'c';
$config['router']['actionKey'] = 'a';
$config['router']['areaKey'] = 'ar';
$config['router']['areas'] = 'admin';
$config['router']['areasEnabled'] = 1;

/*[Db]*/
$config['db']['engine'] = 'Pdo';//'Mysql';
$config['db']['dsn'] = 'mysql:host=localhost;dbname=ex';
$config['db']['host'] = 'localhost';
$config['db']['name'] = 'ex';
$config['db']['user'] = '....';
$config['db']['pass'] = '.....';
$config['db']['persist'] = 0;
$config['db']['prefix'] = '';

/*[Sa]*/
$config['sa']['globalXss'] = 1;
$config['sa']['allowGet'] = 0;

/*[Layout]*/
$config['layout']['publicPath'] = 'http://www.cookbook.com';
$config['layout']['cssPath'] = 'http://www.cookbook.com/css';
$config['layout']['jsPath'] = 'http://www.cookbook.com/js';
$config['layout']['gfxPath'] = 'http://www.cookbook.com/gfx';
$config['layout']['docType'] = '';
$config['layout']['title'] = 'Strona testowa';
$config['layout']['icon'] = '';
$config['layout']['meta'] = '';
$config['layout']['httpMeta'] = '';
$config['layout']['css'] = '';
$config['layout']['js'] = '';

/*[Session]*/
$config['sess']['engine'] = 'Db';//'Native';//'Files';
$config['sess']['name'] = 'ex_session';
$config['sess']['autostart'] = 1;
$config['sess']['maxLifeTime'] = 60;
$config['sess']['gcProbability'] = 1;
$config['sess']['gcDivisor'] = 1000;
$config['sess']['cookiePath'] = '/';
$config['sess']['cookieDomain'] = '';
$config['sess']['cookieSecure'] = '';
$config['sess']['cookieHttpOnly'] = '';
$config['sess']['savePath'] = '/home/przemek/public_html/www.cookbook.com/app/tmp/cache';
$config['sess']['dbTableName'] = 'sessions';
$config['sess']['memcacheHost'] = 'localhost';
$config['sess']['memcachePort'] = 11211;

/*[Response]*/
$config['response']['compressOutput'] = 1;
$config['response']['timeElapsedTag'] = '<!--#timeElapsedTag#-->';
$config['response']['memoryUsageTag'] = '<!--#memoryUsageTag#-->';

/*[ViewResolver]*/
$config['viewResolver']['appPath'] = '/home/przemek/public_html/www.cookbook.com/app';
$config['viewResolver']['tplExt'] = '.tpl';
$config['viewResolver']['defaultViewFormat'] = 'html';

/*[Template]*/
$config['template']['engine'] = 'Smarty';//'Php';//'Smarty';//'Php';
$config['template']['templateDir'] = '/home/przemek/public_html/www.cookbook.com/app/views';
$config['template']['cacheDir'] = '/home/przemek/public_html/www.cookbook.com/app/tmp/cache';
$config['template']['compileDir'] = '/home/przemek/public_html/www.cookbook.com/app/tmp/cpl';
$config['template']['configDir'] = '/home/przemek/public_html/www.cookbook.com/app/config';
$config['template']['pluginsDir'] = '/home/przemek/public_html/www.cookbook.com/app/plugins';
$config['template']['caching'] = 1;
$config['template']['cacheLifeTime'] = 1;
$config['template']['forceCompile'] = 0;
$config['template']['security'] = 0;
$config['template']['debugging'] = 0;
$config['template']['compileId'] = 'pl';

/*[Cache]*/
$config['cache']['engine'] = 'Files';//'Memcache';//'Xcache';//'Apc';
$config['cache']['lifeTime'] = 3600;
$config['cache']['cacheDir'] =  '/home/przemek/public_html/www.cookbook.com/app/tmp/cache';
$config['cache']['cacheFileExt'] = '.cache';
$config['cache']['memcacheHost'] = 'localhost';
$config['cache']['memcachePort'] = 11211;

/*[Logger]*/
$config['logger']['logFilePath'] = '/home/przemek/public_html/www.cookbook.com/app/logs/ex.log';
$config['logger']['logEnabled'] = 1;

/*[Crypt]*/
$config['crypt']['key'] = '$#%#45%e*fgG?#^F$%fd}0^^9#$?!-+sd*&$%)%_VZx{z(vcOGS=09--.<65erRT';
$config['crypt']['iv'] = '';
$config['crypt']['cryptMode'] = 'MCRYPT_MODE_CFB';
$config['crypt']['cipherAlgorithm'] = 'MCRYPT_TRIPLEDES';
$config['crypt']['saveIv'] = 1;

/*[Upload]*/
$config['upload']['uploadPath'] = '/home/przemek/public_html/www.cookbook.com/public/upload';
$config['upload']['allowedFileExt'] = '';
$config['upload']['maxFileSize'] = 1000000000;
$config['upload']['maxFileNameLength'] = 24;
$config['upload']['maxImageWidth'] = 1024;
$config['upload']['maxImageHeight'] = 1024;
$config['upload']['randomName'] = 0;
$config['upload']['overwrite'] = 0;
$config['upload']['allMimes'] = 1;
$config['upload']['stripSpaces'] = 0;
$config['upload']['cleanXss'] = 0;

/*[Ftp]*/
$config['ftp']['host'] = '';
$config['ftp']['user'] = '';
$config['ftp']['password'] = '';
$config['ftp']['dirPath'] = '';
$config['ftp']['port'] = 21;
$config['ftp']['timeout'] = 120;
$config['ftp']['passive'] = 1;
$config['ftp']['ssl'] = 0;
$config['ftp']['prealloc'] = 0;

/*[Mail]*/
$config['mail']['engine'] = 'Smtp';//'Php'; //'Mail';
$config['mail']['sendmailPath'] = '/usr/sbin/qmail';
$config['mail']['smtpAuth'] = 1;
$config['mail']['smtpUser'] = 'borg';
$config['mail']['smtpPass'] = '1901kasia_1901';
$config['mail']['smtpTimeout'] = 45;
$config['mail']['smtpHost'] = 'smtp.boo.pl';
$config['mail']['smtpPort'] = 587;
$config['mail']['textEncoding'] = 'quoted_printable'; //base64
$config['mail']['emailFrom'] = 'borg@boo.pl';

/*[Paginator]*/
$config['paginator']['recordsPerPage'] = 5;
$config['paginator']['getVarName'] = 'page';
$config['paginator']['midRange'] = 9;
$config['paginator']['firstLast'] = 1;
$config['paginator']['delimiter'] = '...';

/*[Auth]*/
$config['auth']['adapter'] = 'Db';
$config['auth']['passwordCryptMethod'] = 'md5';
//
$config['auth']['ldapServer'] = '';
$config['auth']['ldapServerPort'] = '389';
$config['auth']['ldapProtoVer'] = '3';
$config['auth']['ldapTimeLimit'] = '10';
$config['auth']['ldapBaseDn'] = 'dc=localhost,dc=com';
//
$config['auth']['filePath'] = '';
$config['auth']['loginColumnNumber'] = '';
$config['auth']['passwordColumnNumber'] = '';
$config['auth']['statusColumnNumber'] = '';
$config['auth']['delimiter'] = ':';
//
$config['auth']['userTableName'] = 'users';
$config['auth']['loginColumnName'] = 'login';
$config['auth']['passwordColumnName'] = 'password';
$config['auth']['statusColumnName'] = 'status';

/*[Acl]*/
$config['acl']['adapter'] = 'Db';
$config['acl']['loginPath'] = '/profile/loginForm';
$config['acl']['errorPath'] = '/';
$config['acl']['anonymousGroupName'] = 'guest';
$config['acl']['checkTree'] = 0;

/*[FrontController]*/
$config['frontCtrl']['useAcl'] = 1;

/*[I18n]*/
$config['i18n']['defaultLocale'] = 'pl_PL';
$config['i18n']['defaultLanguageCode'] = 'pl';
$config['i18n']['defaultCountryCode'] = 'PL';
$config['i18n']['localeDir'] = '/home/przemek/public_html/www.cookbook.com/app/languages';
$config['i18n']['localeFileExt'] = '.php';

?>
