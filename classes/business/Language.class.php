<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Language extends AutoLanguage
	{
		/**
		 * @return Language
		 */
		public static function create()
		{
			return new self;
		}
	}
?>
