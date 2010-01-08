<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageModule extends AutoPageModule
	{
		/**
		 * @return PageModule
		 */
		public static function create()
		{
			return new self;
		}
	}
?>
