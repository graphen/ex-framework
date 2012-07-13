<?php

//konfiguracja
$config['fPath'] = '/home/przemek/public_html/ExProject/ExFramework/'; ///////////
$config['fPathCore'] = $config['fPath'] . 'core/';
$config['fPathLib'] = $config['fPath'] . 'library/';
$config['fPathThrdLib'] = $config['fPath'] . 'thrdpartylib/';
$config['fPathApp'] = '/home/przemek/public_html/ExProject/app/';
$config['fPathAppCtrls'] = '/home/przemek/public_html/ExProject/app/controllers/';
$config['fPathAppModels'] = '/home/przemek/public_html/ExProject/app/models/';
$config['fPathTest'] = '/home/przemek/public_html/ExProject/test/';

//Config [Config]
$config['ioc']['Config']['className'] = 'Config';
$config['ioc']['Config']['singleton'] = true;
$config['ioc']['Config']['constructor'] = 'getInstance';
$config['ioc']['Config']['constructorArgs'] = array('&ParserPhp', '&ParserIni', '&ParserXml');

//ParserPhp [Parser]
$config['ioc']['ParserPhp']['className'] = 'ParserPhp';
$config['ioc']['ParserPhp']['singleton'] = true;

//ParserIni [Parser]
$config['ioc']['ParserIni']['className'] = 'ParserIni';
$config['ioc']['ParserIni']['singleton'] = true;

//ParserXml [Parser]
$config['ioc']['ParserXml']['className'] = 'ParserXml';
$config['ioc']['ParserXml']['singleton'] = true;

//Benchmark [Benchmark]
$config['ioc']['Benchmark']['className'] = 'Benchmark';
$config['ioc']['Benchmark']['classFile'] = $config['fPathCore'] . 'Benchmark/' . $config['ioc']['Benchmark']['className'] . '.class.php';
$config['ioc']['Benchmark']['singleton'] = true;

//Db [Db]
$config['ioc']['DbEngine']['redirect'] = 'Db%db.engine%';
$config['ioc']['DbResult']['redirect'] = 'DbResult%db.engine%';

//DbMysql [Db]
$config['ioc']['DbEngine']['DbMysql']['className'] = 'DbMysql';
$config['ioc']['DbEngine']['DbMysql']['singleton'] = true;
$config['ioc']['DbEngine']['DbMysql']['constructorArgs'] = array('&DbResultFactory', '%db.host%', '%db.user%', '%db.pass%', '%db.name%', '%db.persist%', '%db.prefix%', array());

//DbResultMysql [Db]
$config['ioc']['DbResult']['DbResultMysql']['className'] = 'DbResultMysql';

//DbPdo [Db]
$config['ioc']['DbEngine']['DbPdo']['className'] = 'DbPdo';
$config['ioc']['DbEngine']['DbPdo']['singleton'] = true;
$config['ioc']['DbEngine']['DbPdo']['constructorArgs'] = array('&DbResultFactory', '%db.dsn%', '%db.user%', '%db.pass%', '%db.persist%', array());

//DbResultPdo [Db]
$config['ioc']['DbResult']['DbResultPdo']['className'] = 'DbResultPdo';

//DbResultFactory [Db]
$config['ioc']['DbResultFactory']['className'] = 'DbResultFactory';
$config['ioc']['DbResultFactory']['singleton'] = true;
$config['ioc']['DbResultFactory']['constructorArgs'] = array('&IocContainer');

//Collection [DataMapper]
$config['ioc']['Collection']['className'] = 'Collection';

//Inflector [DataMapper]
$config['ioc']['Inflector']['className'] = 'Inflector';
$config['ioc']['Inflector']['DbPdo']['singleton'] = true;

//Query [DataMapper]
$config['ioc']['Query']['className'] = 'Query';

//DataMapperFactory [DataMapper]
$config['ioc']['DataMapperFactory']['className'] = 'DataMapperFactory';
$config['ioc']['DataMapperFactory']['singleton'] = true;
$config['ioc']['DataMapperFactory']['constructorArgs'] = array('&IocContainer');

//EntityFactory [DataMapper]
$config['ioc']['EntityFactory']['className'] = 'EntityFactory';
$config['ioc']['EntityFactory']['singleton'] = true;
$config['ioc']['EntityFactory']['constructorArgs'] = array('&IocContainer');

//RelationMapper [DataMapper]
$config['ioc']['RelationMapper']['className'] = 'RelationMapper';
$config['ioc']['RelationMapper']['constructorArgs'] = array('&DataMapperFactory');

//Validator [Validator]
$config['ioc']['ValidatorComposite']['className'] = 'ValidatorComposite';

//ValidatorFactory [Validator]
$config['ioc']['ValidatorFactory']['className'] = 'ValidatorFactory';
$config['ioc']['ValidatorFactory']['singleton'] = true;
$config['ioc']['ValidatorFactory']['constructorArgs'] = array('&IocContainer');

//ValidatorInput [Validator]
$config['ioc']['ValidatorInput']['className'] = 'ValidatorInput';
$config['ioc']['ValidatorInput']['constructorArgs'] = array('&ValidatorFactory');

//ValidatorDbUnique [Validator]
$config['ioc']['ValidatorDbUnique']['className'] = 'ValidatorDbUnique';
$config['ioc']['ValidatorDbUnique']['singleton'] = true;
$config['ioc']['ValidatorDbUnique']['constructorArgs'] = array('&DbEngine');

//ValidatorAlpha [Validator]
$config['ioc']['ValidatorAlpha']['className'] = 'ValidatorAlpha';
$config['ioc']['ValidatorAlpha']['singleton'] = true;

//ValidatorAlphaNumeric [Validator]
$config['ioc']['ValidatorAlphaNumeric']['className'] = 'ValidatorAlphaNumeric';
$config['ioc']['ValidatorAlphaNumeric']['singleton'] = true;

//ValidatorCellPhoneNumber [Validator]
$config['ioc']['ValidatorCellPhoneNumber']['className'] = 'ValidatorCellPhoneNumber';
$config['ioc']['ValidatorCellPhoneNumber']['singleton'] = true;

//ValidatorEmail [Validator]
$config['ioc']['ValidatorEmail']['className'] = 'ValidatorEmail';
$config['ioc']['ValidatorEmail']['singleton'] = true;

//ValidatorEmailExists [Validator]
$config['ioc']['ValidatorEmailExists']['className'] = 'ValidatorEmailExists';
$config['ioc']['ValidatorEmailExists']['singleton'] = true;

//ValidatorFloatMaxValue [Validator]
$config['ioc']['ValidatorFloatMaxValue']['className'] = 'ValidatorFloatMaxValue';
$config['ioc']['ValidatorFloatMaxValue']['singleton'] = true;

//ValidatorFloatMinValue [Validator]
$config['ioc']['ValidatorFloatMinValue']['className'] = 'ValidatorFloatMinValue';
$config['ioc']['ValidatorFloatMinValue']['singleton'] = true;

//ValidatorIntegerMaxValue [Validator]
$config['ioc']['ValidatorIntegerMaxValue']['className'] = 'ValidatorIntegerMaxValue';
$config['ioc']['ValidatorIntegerMaxValue']['singleton'] = true;

//ValidatorIntegerMinValue [Validator]
$config['ioc']['ValidatorIntegerMinValue']['className'] = 'ValidatorIntegerMinValue';
$config['ioc']['ValidatorIntegerMinValue']['singleton'] = true;

//ValidatorIp4 [Validator]
$config['ioc']['ValidatorIp4']['className'] = 'ValidatorIp4';
$config['ioc']['ValidatorIp4']['singleton'] = true;

//ValidatorIp6 [Validator]
$config['ioc']['ValidatorIp6']['className'] = 'ValidatorIp6';
$config['ioc']['ValidatorIp6']['singleton'] = true;

//ValidatorIsFloat [Validator]
$config['ioc']['ValidatorIsFloat']['className'] = 'ValidatorIsFloat';
$config['ioc']['ValidatorIsFloat']['singleton'] = true;

//ValidatorIsInteger [Validator]
$config['ioc']['ValidatorIsInteger']['className'] = 'ValidatorIsInteger';
$config['ioc']['ValidatorIsInteger']['singleton'] = true;

//ValidatorIsNatural [Validator]
$config['ioc']['ValidatorIsNatural']['className'] = 'ValidatorIsNatural';
$config['ioc']['ValidatorIsNatural']['singleton'] = true;

//ValidatorIsNumeric [Validator]
$config['ioc']['ValidatorIsNumeric']['className'] = 'ValidatorIsNumeric';
$config['ioc']['ValidatorIsNumeric']['singleton'] = true;

//ValidatorIsArray [Validator]
$config['ioc']['ValidatorIsArray']['className'] = 'ValidatorIsArray';
$config['ioc']['ValidatorIsArray']['singleton'] = true;

//ValidatorPesel [Validator]
$config['ioc']['ValidatorPesel']['className'] = 'ValidatorPesel';
$config['ioc']['ValidatorPesel']['singleton'] = true;

//ValidatorPolishPostalCode [Validator]
$config['ioc']['ValidatorPolishPostalCode']['className'] = 'ValidatorPolishPostalCode';
$config['ioc']['ValidatorPolishPostalCode']['singleton'] = true;

//ValidatorRegExp [Validator]
$config['ioc']['ValidatorRegExp']['className'] = 'ValidatorRegExp';
$config['ioc']['ValidatorRegExp']['singleton'] = true;

//ValidatorEqual [Validator]
$config['ioc']['ValidatorStringEqual']['className'] = 'ValidatorStringEqual';
$config['ioc']['ValidatorStringEqual']['singleton'] = true;

//ValidatorStringLength [Validator]
$config['ioc']['ValidatorStringLength']['className'] = 'ValidatorStringLength';
$config['ioc']['ValidatorStringLength']['singleton'] = true;

//ValidatorStringMaxLength [Validator]
$config['ioc']['ValidatorStringMaxLength']['className'] = 'ValidatorStringMaxLength';
$config['ioc']['ValidatorStringMaxLength']['singleton'] = true;

//ValidatorStringMinLength [Validator]
$config['ioc']['ValidatorStringMinLength']['className'] = 'ValidatorStringMinLength';
$config['ioc']['ValidatorStringMinLength']['singleton'] = true;

//ValidatorStringWordLength [Validator]
$config['ioc']['ValidatorStringWordLength']['className'] = 'ValidatorStringWordLength';
$config['ioc']['ValidatorStringWordLength']['singleton'] = true;

//ValidatorUrl [Validator]
$config['ioc']['ValidatorUrl']['className'] = 'ValidatorUrl';
$config['ioc']['ValidatorUrl']['singleton'] = true;

//FilterComposite [Filter]
$config['ioc']['FilterComposite']['className'] = 'FilterComposite';

//FilterInput [Filter]
$config['ioc']['FilterInput']['className'] = 'FilterInput';
$config['ioc']['FilterInput']['constructorArgs'] = array('&FilterFactory');

//FilterFactory [Filter]
$config['ioc']['FilterFactory']['className'] = 'FilterFactory';
$config['ioc']['FilterFactory']['singleton'] = true;
$config['ioc']['FilterFactory']['constructorArgs'] = array('&IocContainer');

//FilterAlnum [Filter]
$config['ioc']['FilterAlnum']['className'] = 'FilterAlnum';
$config['ioc']['FilterAlnum']['singleton'] = true;

//FilterAlnumWithSpaces [Filter]
$config['ioc']['FilterAlnumWithSpaces']['className'] = 'FilterAlnumWithSpaces';
$config['ioc']['FilterAlnumWithSpaces']['singleton'] = true;

//FilterAlpha [Filter]
$config['ioc']['FilterAlpha']['className'] = 'FilterAlpha';
$config['ioc']['FilterAlpha']['singleton'] = true;

//FilterAlphaWithSpaces [Filter]
$config['ioc']['FilterAlphaWithSpaces']['className'] = 'FilterAlphaWithSpaces';
$config['ioc']['FilterAlphaWithSpaces']['singleton'] = true;

//FilterDigits [Filter]
$config['ioc']['FilterDigits']['className'] = 'FilterDigits';
$config['ioc']['FilterDigits']['singleton'] = true;

//FilterMd5 [Filter]
$config['ioc']['FilterMd5']['className'] = 'FilterMd5';
$config['ioc']['FilterMd5']['singleton'] = true;

//FilterStandarizeEndLines [Filter]
$config['ioc']['FilterStandarizeEndLines']['className'] = 'FilterStandarizeEndLines';
$config['ioc']['FilterStandarizeEndLines']['singleton'] = true;

//FilterStringTrim [Filter]
$config['ioc']['FilterStringTrim']['className'] = 'FilterStringTrim';
$config['ioc']['FilterStringTrim']['singleton'] = true;

//FilterStripEndLines [Filter]
$config['ioc']['FilterStripEndLines']['className'] = 'FilterStripEndLines';
$config['ioc']['FilterStripEndLines']['singleton'] = true;

//FilterStripGetDissallowedChars [Filter]
$config['ioc']['FilterStripGetDissallowedChars']['className'] = 'FilterStripGetDissallowedChars';
$config['ioc']['FilterStripGetDissallowedChars']['singleton'] = true;

//FFilterStripSlashes [Filter]
$config['ioc']['FilterStripSlashes']['className'] = 'FilterStripSlashes';
$config['ioc']['FilterStripSlashes']['singleton'] = true;

//FFilterStripSlashes [Filter]
$config['ioc']['FilterStripTags']['className'] = 'FilterStripTags';
$config['ioc']['FilterStripTags']['singleton'] = true;

//FilterXss [Filter]
$config['ioc']['FilterXss']['className'] = 'FilterXss';
$config['ioc']['FilterXss']['singleton'] = true;

//SaServer [Request]
$config['ioc']['SaServer']['className'] = 'SaServer';
$config['ioc']['SaServer']['singleton'] = true;
$config['ioc']['SaServer']['constructorArgs'] = array('&FilterComposite', '&FilterStripSlashes');

//SaPost [Request]
$config['ioc']['SaPost']['className'] = 'SaPost';
$config['ioc']['SaPost']['singleton'] = true;
$config['ioc']['SaPost']['constructorArgs'] = array('&FilterComposite', '&FilterStripSlashes', '&FilterXss', '%sa.globalXss%', );

//SaEnv [Request]
$config['ioc']['SaEnv']['className'] = 'SaEnv';
$config['ioc']['SaEnv']['singleton'] = true;
$config['ioc']['SaEnv']['constructorArgs'] = array('&FilterComposite', '&FilterStripSlashes');

//SaGet [Request]
$config['ioc']['SaGet']['className'] = 'SaGet';
$config['ioc']['SaGet']['singleton'] = true;
$config['ioc']['SaGet']['constructorArgs'] = array('&FilterComposite', '&FilterStripSlashes', '&FilterStripGetDissallowedChars',  '%sa.allowGet%');

//SaCookie [Request]
$config['ioc']['SaCookie']['className'] = 'SaCookie';
$config['ioc']['SaCookie']['singleton'] = true;
$config['ioc']['SaCookie']['constructorArgs'] = array('&FilterComposite', '&FilterStripSlashes');

//Request [Request]
$config['ioc']['Request']['className'] = 'Request';
$config['ioc']['Request']['singleton'] = true;
$config['ioc']['Request']['constructorArgs'] = array('&SaCookie', '&SaGet', '&SaPost', '&SaServer', '&SaEnv', '%app.url%');

//[Template]
$config['ioc']['Template']['redirect'] = 'Template%template.engine%';

//TemplatePhp [Template]
$config['ioc']['Template']['TemplatePhp']['className'] = 'TemplatePhp';
$config['ioc']['Template']['TemplatePhp']['singleton'] = true;
$config['ioc']['Template']['TemplatePhp']['constructorArgs'] = array('%template.templateDir%', '%template.cacheDir%', '%template.configDir%', '%template.caching%', '%template.cacheLifeTime%', '%template.forceCompile%', '%template.compileId%');

//TemplateSmarty [Template]
$config['ioc']['Template']['TemplateSmarty']['className'] = 'TemplateSmarty';
$config['ioc']['Template']['TemplateSmarty']['singleton'] = true;
$config['ioc']['Template']['TemplateSmarty']['constructorArgs'] = array('%template.templateDir%', '%template.cacheDir%', '%template.compileDir%', '%template.configDir%', '%template.caching%', '%template.security%', '%template.cacheLifeTime%', '%template.debugging%', '%template.forceCompile%', '%template.compileId%');

//DirManager [FileSystem]
$config['ioc']['DirManager']['className'] = 'DirManager';
$config['ioc']['DirManager']['singleton'] = true;

//FileManager [FileSystem]
$config['ioc']['FileManager']['className'] = 'FileManager';
$config['ioc']['FileManager']['singleton'] = true;
$config['ioc']['FileManager']['constructorArgs'] = array('&FileHandler');

//FileHandler [FileSystem]
$config['ioc']['FileHandler']['className'] = 'FileHandler';
$config['ioc']['FileHandler']['singleton'] = true;

//Ftp [FileSystem]
$config['ioc']['Ftp']['className'] = 'Ftp';
$config['ioc']['Ftp']['singleton'] = true;
$config['ioc']['Ftp']['constructorArgs'] = array('%ftp.host%', '%ftp.user%', '%ftp.password%', '%ftp.dirPath%', '%ftp.port%', '%ftp.timeout%', '%ftp.passive%', '%ftp.ssl%', '%ftp.prealloc%');

//Upload [FileSystem]
$config['ioc']['Upload']['className'] = 'Upload';
$config['ioc']['Upload']['singleton'] = true;
$config['ioc']['Upload']['constructorArgs'] = array('&FileManager', '&FilterXss', '%upload.uploadPath%', '%upload.allowedFileExt%', '%upload.maxFileSize%', '%upload.maxFileNameLength%', '%upload.maxImageWidth%', '%upload.maxImageHeight%', '%upload.randomName%', '%upload.overwrite%', '%upload.allMimes%', '%upload.stripSpaces%', '%upload.cleanXss%');

//Exif [Image]
$config['ioc']['Exif']['className'] = 'Exif';
$config['ioc']['Exif']['singleton'] = true;

//Crypt [Crypt]
$config['ioc']['Crypt']['className'] = 'Crypt';
$config['ioc']['Crypt']['singleton'] = true;
$config['ioc']['Crypt']['constructorArgs'] = array('%crypt.key%', '%crypt.cipherAlgorithm%', '%crypt.cryptMode%', '%crypt.iv%', '%crypt.saveIv%');

//Browser [Browser]
$config['ioc']['Browser']['className'] = 'Browser';
$config['ioc']['Browser']['singleton'] = true;

//Logger [Log]
$config['ioc']['LoggerFiles']['className'] = 'LoggerFiles';
$config['ioc']['LoggerFiles']['singleton'] = true;
$config['ioc']['LoggerFiles']['constructorArgs'] = array('%logger.logFilePath%', '%logger.logEnabled%');

//[Cache]
$config['ioc']['Cache']['redirect'] = 'Cache%cache.engine%';

//CacheApc [Cache]
$config['ioc']['Cache']['CacheApc']['className'] = 'CacheApc';
$config['ioc']['Cache']['CacheApc']['singleton'] = true;
$config['ioc']['Cache']['CacheApc']['constructorArgs'] = array('%cache.lifeTime%');

//CacheEaccelerator [Cache]
$config['ioc']['Cache']['CacheEaccelerator']['className'] = 'CacheEaccelerator';
$config['ioc']['Cache']['CacheEaccelerator']['singleton'] = true;
$config['ioc']['Cache']['CacheEaccelerator']['constructorArgs'] = array('%cache.lifeTime%');

//CacheXcache [Cache]
$config['ioc']['Cache']['CacheXcache']['className'] = 'CacheXcache';
$config['ioc']['Cache']['CacheXcache']['singleton'] = true;
$config['ioc']['Cache']['CacheXcache']['constructorArgs'] = array('%cache.lifeTime%');

//CacheFiles [Cache]
$config['ioc']['Cache']['CacheFiles']['className'] = 'CacheFiles';
$config['ioc']['Cache']['CacheFiles']['singleton'] = true;
$config['ioc']['Cache']['CacheFiles']['constructorArgs'] = array('%cache.lifeTime%', '%cache.cacheDir%', '%cache.cacheFileExt%');

//CacheMemcache [Cache]
$config['ioc']['Cache']['CacheMemcache']['className'] = 'CacheMemcache';
$config['ioc']['Cache']['CacheMemcache']['singleton'] = true;
$config['ioc']['Cache']['CacheMemcache']['constructorArgs'] = array('%cache.lifeTime%', '%cache.memcacheHost%', '%cache.memcachePort%');

//Mailer [Mail]
$config['ioc']['Mailer']['className'] = 'Mailer';
$config['ioc']['Mailer']['singleton'] = true;
$config['ioc']['Mailer']['constructorArgs'] = array('&TransportMail', '%mail.textEncoding%');

//TransportMail [Mail]
$config['ioc']['TransportMail']['redirect'] = 'Mail%mail.engine%';

//MailPhp [Mail]
$config['ioc']['TransportMail']['MailPhp']['className'] = 'MailPhp';
$config['ioc']['TransportMail']['MailPhp']['singleton'] = true;

//MailSendmail [Mail]
$config['ioc']['TransportMail']['MailSendmail']['className'] = 'MailSendmail';
$config['ioc']['TransportMail']['MailSendmail']['singleton'] = true;
$config['ioc']['TransportMail']['MailSendmail']['constructorArgs'] = array('%mail.sendmailPath%');

//MailSmtp [Mail]
$config['ioc']['TransportMail']['MailSmtp']['className'] = 'MailSmtp';
$config['ioc']['TransportMail']['MailSmtp']['singleton'] = true;
$config['ioc']['TransportMail']['MailSmtp']['constructorArgs'] = array('%mail.smtpAuth%', '%mail.smtpUser%', '%mail.smtpPass%', '%mail.smtpTimeout%', '%mail.smtpHost%', '%mail.smtpPort%', '%mail.textEncoding%');

//FormatterForm [Formatter]
$config['ioc']['FormatterForm']['className'] = 'FormatterForm';

//FormatterFormElement [Formatter]
$config['ioc']['FormatterFormElement']['className'] = 'FormatterFormElement';

//HtmlElementFactory [Form]
$config['ioc']['HtmlElementFactory']['className'] = 'HtmlElementFactory';
$config['ioc']['HtmlElementFactory']['singleton'] = true;
$config['ioc']['HtmlElementFactory']['constructorArgs'] = array('&IocContainer');

//Form [Form]
$config['ioc']['Form']['className'] = 'Form';
$config['ioc']['Form']['constructorArgs'] = array('&HtmlElementFactory', '&ValidatorInput', '&FilterInput', '&FormatterForm', '&FormatterFormElement');

//FormElementButtonButton [Form]
$config['ioc']['FormElementButtonButton']['className'] = 'FormElementButtonButton';
$config['ioc']['FormElementButtonButton']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementButtonReset [Form]
$config['ioc']['FormElementButtonReset']['className'] = 'FormElementButtonReset';
$config['ioc']['FormElementButtonReset']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementButtonSubmit [Form]
$config['ioc']['FormElementButtonSubmit']['className'] = 'FormElementButtonSubmit';
$config['ioc']['FormElementButtonSubmit']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementTextArea [Form]
$config['ioc']['FormElementTextArea']['className'] = 'FormElementTextArea';
$config['ioc']['FormElementTextArea']['constructorArgs'] = array('&HtmlElementFactory');

//HtmlElementOption [Form]
$config['ioc']['HtmlElementOption']['className'] = 'HtmlElementOption';
$config['ioc']['HtmlElementOption']['constructorArgs'] = array('&HtmlElementFactory');

//HtmlElementOptgroup [Form]
$config['ioc']['HtmlElementOptgroup']['className'] = 'HtmlElementOptgroup';
$config['ioc']['HtmlElementOptgroup']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementSelect [Form]
$config['ioc']['FormElementSelect']['className'] = 'FormElementSelect';
$config['ioc']['FormElementSelect']['constructorArgs'] = array('&HtmlElementFactory');

//HtmlElementLabel [Form]
$config['ioc']['HtmlElementLabel']['className'] = 'HtmlElementLabel';
$config['ioc']['HtmlElementLabel']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementMultiRadio [Form]
$config['ioc']['FormElementMultiRadio']['className'] = 'FormElementMultiRadio';
$config['ioc']['FormElementMultiRadio']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementMultiCheckbox [Form]
$config['ioc']['FormElementMultiCheckbox']['className'] = 'FormElementMultiCheckbox';
$config['ioc']['FormElementMultiCheckbox']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementInputButton [Form]
$config['ioc']['FormElementInputButton']['className'] = 'FormElementInputButton';
$config['ioc']['FormElementInputButton']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementInputCheckbox [Form]
$config['ioc']['FormElementInputCheckbox']['className'] = 'FormElementInputCheckbox';
$config['ioc']['FormElementInputCheckbox']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementInputRadio [Form]
$config['ioc']['FormElementInputRadio']['className'] = 'FormElementInputRadio';
$config['ioc']['FormElementInputRadio']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementInputFile [Form]
$config['ioc']['FormElementInputFile']['className'] = 'FormElementInputFile';
$config['ioc']['FormElementInputFile']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementInputHidden [Form]
$config['ioc']['FormElementInputHidden']['className'] = 'FormElementInputHidden';
$config['ioc']['FormElementInputHidden']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementInputImage [Form]
$config['ioc']['FormElementInputImage']['className'] = 'FormElementInputImage';
$config['ioc']['FormElementInputImage']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementInputReset [Form]
$config['ioc']['FormElementInputReset']['className'] = 'FormElementInputReset';
$config['ioc']['FormElementInputReset']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementInputSubmit [Form]
$config['ioc']['FormElementInputSubmit']['className'] = 'FormElementInputSubmit';
$config['ioc']['FormElementInputSubmit']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementInputPassword [Form]
$config['ioc']['FormElementInputPassword']['className'] = 'FormElementInputPassword';
$config['ioc']['FormElementInputPassword']['constructorArgs'] = array('&HtmlElementFactory');

//FormElementInputText [Form]
$config['ioc']['FormElementInputText']['className'] = 'FormElementInputText';
$config['ioc']['FormElementInputText']['constructorArgs'] = array('&HtmlElementFactory');

//Router [Router]
$config['ioc']['Router']['className'] = 'Router';
$config['ioc']['Router']['singleton'] = true;
$config['ioc']['Router']['constructorArgs'] = array('&Request', '%router.urlMode%', '%router.urlSuffix%', '%router.useAssoc%', '%router.controllerKey%', '%router.actionKey%', '%router.defaultController%', '%router.defaultAction%', '%router.areasEnabled%', '%router.areaKey%', '%router.areas%', '%router.routes%');

//Session [Session]
$config['ioc']['Session']['className'] = 'Session';
$config['ioc']['Session']['singleton'] = true;
$config['ioc']['Session']['constructorArgs'] = array('&SessionHandler', '%sess.name%', '%sess.autostart%', '%sess.maxLifeTime%', '%sess.gcProbability%', '%sess.gcDivisor%', '%sess.cookiePath%', '%sess.cookieDomain%', '%sess.cookieSecure%', '%sess.cookieHttpOnly%');

//SessionHandler [Session]
$config['ioc']['SessionHandler']['redirect'] = 'SessionHandler%sess.engine%';

//SessionHandlerNative [Session]
$config['ioc']['SessionHandler']['SessionHandlerNative']['className'] = 'SessionHandlerNative';
$config['ioc']['SessionHandler']['SessionHandlerNative']['singleton'] = true;

//SessionHandlerFiles [Session]
$config['ioc']['SessionHandler']['SessionHandlerFiles']['className'] = 'SessionHandlerFiles';
$config['ioc']['SessionHandler']['SessionHandlerFiles']['singleton'] = true;
$config['ioc']['SessionHandler']['SessionHandlerFiles']['constructorArgs'] = array('%sess.savePath%', '%sess.maxLifeTime%');

//SessionHandlerDb [Session]
$config['ioc']['SessionHandler']['SessionHandlerDb']['className'] = 'SessionHandlerDb';
$config['ioc']['SessionHandler']['SessionHandlerDb']['singleton'] = true;
$config['ioc']['SessionHandler']['SessionHandlerDb']['constructorArgs'] = array('&DbEngine', '%sess.dbTableName%', '%sess.maxLifeTime%');

//SessionHandlerMemcache [Session]
$config['ioc']['SessionHandler']['SessionHandlerMemcache']['className'] = 'SessionHandlerMemcache';
$config['ioc']['SessionHandler']['SessionHandlerMemcache']['singleton'] = true;
$config['ioc']['SessionHandler']['SessionHandlerMemcache']['constructorArgs'] = array('%sess.memcacheHost%', '%sess.memcachePort%', '%sess.maxLifeTime%');

//ViewResolver [View]
$config['ioc']['ViewResolver']['className'] = 'ViewResolver';
$config['ioc']['ViewResolver']['singleton'] = true;
$config['ioc']['ViewResolver']['constructorArgs'] = array('&ViewFactory', '&Request', '%viewResolver.appPath%', '%viewResolver.tplExt%', '%viewResolver.defaultViewFormat%');

//ViewFactory [View]
$config['ioc']['ViewFactory']['className'] = 'ViewFactory';
$config['ioc']['ViewFactory']['singleton'] = true;
$config['ioc']['ViewFactory']['constructorArgs'] = array('&IocContainer');

//ViewPlain [View]
$config['ioc']['ViewPlain']['className'] = 'ViewPlain';

//ViewXml [View]
$config['ioc']['ViewXml']['className'] = 'ViewXml';

//ViewJson [View]
$config['ioc']['ViewJson']['className'] = 'ViewJson';

//ViewFile [View]
$config['ioc']['ViewFile']['className'] = 'ViewFile';
$config['ioc']['ViewFile']['constructorArgs'] = array('&FileManager');

//ViewTemplate [View]
$config['ioc']['ViewTemplate']['className'] = 'ViewTemplate';
$config['ioc']['ViewTemplate']['constructorArgs'] = array('&Template');

//ViewPdf [View]
$config['ioc']['ViewPdf']['className'] = 'ViewPdf';
$config['ioc']['ViewPdf']['constructorArgs'] = array('&Template', '&Html2Pdf');

//Html2Pdf [TCPDF]
$config['ioc']['Html2Pdf']['className'] = 'Html2Pdf';

//ViewHtml [View]
$config['ioc']['ViewHtml']['className'] = 'ViewHtml';
$config['ioc']['ViewHtml']['constructorArgs'] = array('&Template', '&Layout');

//Layout [Layout]
$config['ioc']['Layout']['className'] = 'Layout';
$config['ioc']['Layout']['singleton'] = true;
$config['ioc']['Layout']['constructorArgs'] = array('&Template', '%layout.publicPath%', '%layout.cssPath%', '%layout.jsPath%', '%layout.gfxPath%', '%layout.docType%', '%layout.title%', '%layout.icon%', '%layout.meta%', '%layout.httpMeta%', '%layout.css%', '%layout.js%');

//Response [Response]
$config['ioc']['Response']['className'] = 'Response';
$config['ioc']['Response']['singleton'] = true;
$config['ioc']['Response']['constructorArgs'] = array('&Request', '&Benchmark', '&FileManager', '%response.compressOutput%', '%response.timeElapsedTag%', '%response.memoryUsageTag%');

//ControllerFactory [Controller]
$config['ioc']['ControllerFactory']['className'] = 'ControllerFactory';
$config['ioc']['ControllerFactory']['singleton'] = true;
$config['ioc']['ControllerFactory']['constructorArgs'] = array('&IocContainer');

//FrontController [Controller]
$config['ioc']['ControllerFront']['className'] = 'ControllerFront';
$config['ioc']['ControllerFront']['singleton'] = true;
$config['ioc']['ControllerFront']['constructorArgs'] = array('&Request', '&Response', '&Router', '&ControllerFactory', '&Auth', '&Acl', '%frontCtrl.useAcl%');

//I18nTranslator [I18n]
$config['ioc']['I18nTranslator']['className'] = 'I18nTranslator';
$config['ioc']['I18nTranslator']['singleton'] = true;
//$config['ioc']['I18nTranslator']['constructor'] = 'getInstance';
$config['ioc']['I18nTranslator']['constructorArgs'] = array('&ParserPhp', '&ParserIni', '&ParserXml');

//I18n[I18n]
$config['ioc']['I18n']['className'] = 'I18n';
$config['ioc']['I18n']['singleton'] = true;
//$config['ioc']['I18n']['constructor'] = 'getInstance';
$config['ioc']['I18n']['constructorArgs'] = array('&Request', '&Session', '&I18nTranslator', '%i18n.defaultLocale%', '%i18n.localeDir%', '%i18n.localeFileExt%', '%i18n.defaultLanguageCode%', '%i18n.defaultCountryCode%');

//Paginator [Paginator]
$config['ioc']['Paginator']['className'] = 'Paginator';
$config['ioc']['Paginator']['constructorArgs'] = array('&PaginatorViewHelper', '%paginator.recordsPerPage%', '%paginator.getVarName%', '%paginator.midRange%', '%paginator.firstLast%', '%paginator.delimiter%');

//PaginatorViewHelper [Paginator]
$config['ioc']['PaginatorViewHelper']['className'] = 'PaginatorViewHelper';
$config['ioc']['PaginatorViewHelper']['constructorArgs'] = array('&I18n');

//Auth
$config['ioc']['AuthAdapter']['redirect'] = 'AuthAdapter%auth.adapter%';

$config['ioc']['Auth']['className'] = 'Auth';
$config['ioc']['Auth']['constructorArgs'] = array('&Request', '&Session', '&AuthAdapter', '&AuthAdapterFactory');

//AuthAdapterFactory [Auth]
$config['ioc']['AuthAdapterFactory']['className'] = 'AuthAdapterFactory';
$config['ioc']['AuthAdapterFactory']['singleton'] = true;
$config['ioc']['AuthAdapterFactory']['constructorArgs'] = array('&IocContainer');

//AuthAdapter [Auth]
$config['ioc']['AuthAdapter']['AuthAdapterLdap']['className'] = 'AuthAdapterLdap';
$config['ioc']['AuthAdapter']['AuthAdapterLdap']['constructorArgs'] = array('%auth.ldapServer%', '%auth.ldapServerPort%', '%auth.ldapProtoVer%', '%auth.ldapTimeLimit%', '%auth.ldapBaseDn%', '%auth.passwordCryptMethod%');

$config['ioc']['AuthAdapter']['AuthAdapterFile']['className'] = 'AuthAdapterFile';
$config['ioc']['AuthAdapter']['AuthAdapterFile']['constructorArgs'] = array('%auth.filePath%', '%auth.loginColumnNumber%', '%auth.passwordColumnNumber%', '%auth.statusColumnNumber%', '%auth.delimiter%', '%auth.passwordCryptMethod%');

$config['ioc']['AuthAdapter']['AuthAdapterDb']['className'] = 'AuthAdapterDb';
$config['ioc']['AuthAdapter']['AuthAdapterDb']['constructorArgs'] = array('&DbEngine', '%auth.userTableName%', '%auth.loginColumnName%', '%auth.passwordColumnName%', '%auth.statusColumnName%', '%auth.passwordCryptMethod%');


//Acl
$config['ioc']['Acl']['redirect'] = 'Acl%acl.adapter%';

$config['ioc']['Acl']['AclDb']['className'] = 'AclDb';
$config['ioc']['Acl']['AclDb']['constructorArgs'] = array('&DbEngine', '%acl.loginPath%', '%acl.errorPath%', '%acl.anonymousGroupName%', '%acl.checkTree%');


//Test [MyForm]
//$config['ioc']['MyForm']['className'] = 'MyForm';
//$config['ioc']['MyForm']['constructorArgs'] = array('&HtmlElementFactory', '&ValidatorInput', '&FilterInput', '&FormatterForm', '&FormatterFormElement');

?>
