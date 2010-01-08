<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoRightDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Right';
		
		/**
		 * @return Right
		 */
		protected function build(array $array)
		{
			return
				Right::create()->
					setId($array['id'])->
					setAlias($array['alias'])->
					setName($array['name'])->
					setRole($array['role'] == 1);
		}
	}
?>