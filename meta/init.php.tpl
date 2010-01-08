<?php
	/* $Id$ */

	define('EWGRA_PROJECTS_DIR', '/home/www/ewgraProjects');
	define('FRAMEWORK_DIR', EWGRA_PROJECTS_DIR . '/framework');
	define('CMS_DIR', EWGRA_PROJECTS_DIR . '/cms');
	define('META_BUILDER_DIR', dirname(__FILE__));
	
	require_once(FRAMEWORK_DIR . '/core/patterns/SingletonInterface.class.php');
	require_once(FRAMEWORK_DIR . '/core/patterns/Singleton.class.php');
	require_once(FRAMEWORK_DIR . '/ClassesAutoloader.class.php');

	ClassesAutoloader::me()->
		addSearchDirectories(
			array(
				FRAMEWORK_DIR,
				CMS_DIR
			)
		);
	
	function __autoload($className)
	{
		if (!class_exists('ClassesAutoloader', false))
			return null;
		
		ClassesAutoloader::me()->load($className);
	}
?>