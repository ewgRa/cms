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

		public function insert(DebugData $data)
		{
			$dbQuery = "INSERT INTO " . $this->getTable()
				. " SET session = ?, data = ?";
			
			$this->db()->query(
				$dbQuery,
				array($data->getSession(), serialize($data->getData()))
			);
			
			return $this->db()->getInsertedId();
		}
	}
?>