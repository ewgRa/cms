<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoNavigationDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Navigation';
		
		/**
		 * @return Navigation
		 */
		protected function build(array $array)
		{
			return
				Navigation::create()->
					setId($array['id'])->
					setCategoryId($array['category_id'])->
					setUri(HttpUrl::createFromString($array['uri']));
		}
	}
?>