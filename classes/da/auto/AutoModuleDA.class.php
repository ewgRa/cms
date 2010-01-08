<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoModuleDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Module';
		
		/**
		 * @return Module
		 */
		protected function build(array $array)
		{
			return
				Module::create()->
					setId($array['id'])->
					setName($array['name'])->
					setSettings($array['settings'] ? unserialize($array['settings']) : null);
		}
	}
?>