<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PagePathMapperDA extends CmsDatabaseRequester
	{
		/**
		 * @return PagePathMapperDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
				
		public function getMap()
		{
			$dbQuery = '
				SELECT path, id, preg
				FROM ' . $this->db()->getTable('Page') . '
				WHERE status = \'normal\'
			';

			$dbResult = $this->db()->query($dbQuery);
			
			return $dbResult->fetchList();
		}
	}
?>