<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoUserRightDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'UserRight';
		
		/**
		 * @return UserRight
		 */
		protected function build(array $array)
		{
			return
				UserRight::create()->
					setUserId($array['user_id'])->
					setRightId($array['right_id']);
		}
	}
?>