<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Page extends AutoPage
	{
		/**
		 * @return Page
		 */
		public static function create()
		{
			return new self;
		}
	}
?>
