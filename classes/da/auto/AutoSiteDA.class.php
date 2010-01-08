<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoSiteDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Site';
		
		/**
		 * @return Site
		 */
		protected function build(array $array)
		{
			return
				Site::create()->
					setId($array['id'])->
					setAlias($array['alias']);
		}
	}
?>