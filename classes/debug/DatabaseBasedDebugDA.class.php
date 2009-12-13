<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseBasedDebugDA extends CmsDatabaseRequester
	{
		/**
		 * @return DatabaseBasedDebugDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
				
		public function insertItem($sessionId, $data)
		{
			$dbQuery = "INSERT INTO " . $this->db()->getTable('DebugData')
				. " SET session_id = ?, data = ?";
			
			$this->db()->query(
				$dbQuery,
				array($sessionId, $data)
			);
			
			return $this->db()->getInsertedId();
		}
	}
?>