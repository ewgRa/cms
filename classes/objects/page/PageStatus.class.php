<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageStatus extends Enumeration
	{
		const NORMAL	= 'normal';
		const DELETED	= 'deleted';
		
		protected $names = array(
			self::NORMAL 	=> self::NORMAL,
			self::DELETED 	=> self::DELETED
		);
		
		/**
		 * @return ContentStatus
		 */
		public static function create($id)
		{
			return new self($id);
		}
		
		/**
		 * @return ContentStatus
		 */
		public static function any()
		{
			return self::normal();
		}
		
		/**
		 * @return ContentStatus
		 */
		public static function normal()
		{
			return self::create(self::NORMAL);
		}

		/**
		 * @return ContentStatus
		 */
		public static function deleted()
		{
			return self::create(self::DELETED);
		}
	}
?>