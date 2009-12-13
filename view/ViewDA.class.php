<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ViewDA extends CmsDatabaseRequester
	{
		/**
		 * @return ViewDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
				
		public function getFile($fileId)
		{
			$dbQuery = '
				SELECT * FROM ' . $this->db()->getTable('ViewFile') . '
				WHERE id = ?
			';
			
			$dbResult = $this->db()->query($dbQuery, array($fileId));

			return $dbResult->recordCount()
				? $dbResult->fetchArray()
				: null;
		}
	}
?>