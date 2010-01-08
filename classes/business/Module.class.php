<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Module extends AutoModule
	{
		/**
		 * @return Module
		 */
		public static function create()
		{
			return new self;
		}
	}
?>
