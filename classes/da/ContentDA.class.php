<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ContentDA extends AutoContentDA
	{
		/**
		 * @return ContentDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function getByIds(array $ids)
		{
			$dbQuery = "
				SELECT * FROM ".$this->getTable()."
				WHERE id IN (?) AND status = 'normal'
			";
			
			return $this->getListCachedByQuery($dbQuery, array($ids));
		}
	}
?>