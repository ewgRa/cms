<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoCategoryDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Category';
		
		/**
		 * @return Category
		 */
		protected function build(array $array)
		{
			return
				Category::create()->
					setId($array['id'])->
					setAlias($array['alias']);
		}
	}
?>