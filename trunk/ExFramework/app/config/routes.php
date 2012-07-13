<?php

//$config['routes']['defaultController'] = 'welcome';
//$config['routes']['defaultAction'] = 'welcome';
//$config['routes']['route'][0]['expr'] = 'blog';
//$config['routes']['route'][0]['route'] = 'blog/showall';
//$config['routes']['route'][1]['expr'] = 'blog/(:num)';
//$config['routes']['route'][1]['route'] = 'blog/show/$1';


$config['router']['routes'][1]['expr'] = 'recipe/(:num)';
$config['router']['routes'][1]['route'] = 'recipe/view/id/$1';
$config['router']['routes'][1]['expr'] = '(:num)';
$config['router']['routes'][1]['route'] = 'recipe/view/id/$1'

?>
