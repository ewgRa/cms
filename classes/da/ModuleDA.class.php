<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ModuleDA extends AutoModuleDA
	{
		/**
		 * @return ModuleDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function getById($id)
		{
			$dbQuery = "SELECT * FROM ".$this->getTable()." WHERE id = ?";
			
			return $this->getCachedByQuery($dbQuery, array($id));
		}
	}
?>