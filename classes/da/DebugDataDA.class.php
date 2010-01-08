<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DebugDataDA extends AutoDebugDataDA
	{
		/**
		 * @return DebugDataDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
	}
?>