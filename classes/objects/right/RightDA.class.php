<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class RightDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Right';
		
		/**
		 * @return RightDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
	}
?>