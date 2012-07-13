<?php

try {
	
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	ini_set('html_errors', true);

	
	$basePath = '/home/przemek/public_html/www.cookbook.com/';
	$frameworkPath = $basePath . 'ExFramework/';
	$publicPath = $basePath . 'public/';	
	$appPath = $basePath . 'app/';	

	require_once('../ExFramework/core/Exception/AppException.class.php');
	require_once('../ExFramework/core/Exception/ErrorHandler.class.php');	
	require_once('../ExFramework/core/Exception/ErrorHandlerException.class.php');	
	require_once('../ExFramework/core/Exception/ExceptionHandler.class.php');	
	require_once('../ExFramework/core/Exception/AppException.class.php');	
	require_once('../ExFramework/core/Cache/ICache.interface.php');
	require_once('../ExFramework/core/Cache/ICacheFiles.interface.php');
	require_once('../ExFramework/core/Cache/CacheException.class.php');		
	require_once('../ExFramework/core/Cache/CacheFiles.class.php');
	require_once('../ExFramework/core/AutoLoader/IAutoLoader.interface.php');
	require_once('../ExFramework/core/AutoLoader/AutoLoaderException.class.php');
	require_once('../ExFramework/core/AutoLoader/AutoLoader.class.php');

	//$exceptionHandler = new ExceptionHandler(true);
	//$errorHandler = new ErrorHandler(false,true);
	$cache = new CacheFiles('60', $appPath.'tmp/cache', '.cache');
	$appLoader = new AutoLoader($basePath, null, array('ExFramework', 'app'));
	$bench = new Benchmark();
	$cfg = new Config(new ParserPhp(), new ParserIni(), new ParserXml());
	
	$cfg->addConfigFile($appPath.'config/config.php');
	$cfg->addConfigFile($appPath.'config/routes.php');

	$builder = new IocMapBuilder($cfg, $cache, $frameworkPath.'core/Ioc/IocConfig.php');
	$builder->addConfigFile($appPath.'config/IocAppConfig.php');
	//$builder->build();
	$ioc = new IocContainer($builder->getAppMap(), true, array('Config',$cfg));
	
	$db = $ioc->create('DbEngine');	
	
	$db->exec("SET NAMES 'utf8'");
	$db->exec("SET CHARACTER SET utf8");
	$layout = $ioc->create('Layout');
	$layout->addMeta('author','Przemek Szamraj');
	$layout->addMeta('keywords','Książka kucharska, gotowanie, potrawy');
	$layout->addMeta('description','Moja książka kucharska');
	$layout->addHttpMeta('content-type','text/html; charset=utf-8');
	$layout->addHttpMeta('reply-to','szamraj@gmail.com');
	$layout->addCss('layout.css');
	$layout->addFavicon('favicon.ico');
	$layout->addJavaScript('dzisiaj.js');

	$fc = $ioc->create('ControllerFront');
	$fc->execute();
	
	
}
catch(AppException $e) {
	echo $e->printAll();
}
	
?>
