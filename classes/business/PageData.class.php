<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageData extends AutoPageData
	{
		/**
		 * @return PageData
		 */
		public static function create()
		{
			return new self;
		}
	}
?>
