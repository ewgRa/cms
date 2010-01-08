<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ViewFile extends AutoViewFile
	{
		/**
		 * @return ViewFile
		 */
		public static function create()
		{
			return new self;
		}
	}
?>
