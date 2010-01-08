<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NavigationDA extends AutoNavigationDA
	{
		/**
		 * @return NavigationDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getByCategoryIds(array $ids)
		{
			$dbQuery = "
				SELECT * FROM ".$this->getTable()."
				WHERE category_id = ?
			";
			
			return $this->getListCachedByQuery($dbQuery, array($ids));
		}
	}
?>