<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoContentDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Content';
		
		/**
		 * @return Content
		 */
		protected function build(array $array)
		{
			return
				Content::create()->
					setId($array['id'])->
					setStatus(ContentStatus::create($array['status']));
		}
	}
?>