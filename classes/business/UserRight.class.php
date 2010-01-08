<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class UserRight extends AutoUserRight
	{
		/**
		 * @return UserRight
		 */
		public static function create()
		{
			return new self;
		}
	}
?>
