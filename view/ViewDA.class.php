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
		public static function create()
		{
			return new self;
		}
		
		public function getFile($fileId)
		{
			$dbQuery = '
				SELECT * FROM ' . $this->db()->getTable('ViewFiles') . '
				WHERE id = ?
			';
			
			$dbResult = $this->db()->query($dbQuery, array($fileId));

			return $dbResult->recordCount()
				? $dbResult->fetchArray()
				: null;
		}
	}
?>