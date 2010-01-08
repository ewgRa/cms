<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class User extends AutoUser
	{
		/**
		 * @return User
		 */
		public static function create()
		{
			return new self;
		}
		
		public function getRights()
		{
			return UserRight::da()->getByUser($this);
		}
	}
?>
