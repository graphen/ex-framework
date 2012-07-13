<?php

/**
 * @interface ILayout
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface ILayout {
	
	public function setContent(IViewTemplate $content);		
	public function addWidget($widgetName, IViewTemplate $widgetContent);
	public function removeWidget($widgetName);
	public function setLayoutPath($layoutPath);
	public function fetch($layoutPath=null);
	
	public function setDocType($docType='');
	public function setTitle($title='');
	public function addHttpMeta($httpMeta, $content);
	public function addMeta($name, $content);
	public function addCss($href, $media='screen', $rel='stylesheet');
	public function addFavicon($href);
	public function addJavaScript($src, $language='JavaScript');
	public function addScriptSource($scriptSource, $language='JavaScript', $type='text/javascript');
	
}

?>
