<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Right extends AutoRight
	{
		/**
		 * @return Right
		 */
		public static function create()
		{
			return new self;
		}
	}
?>
