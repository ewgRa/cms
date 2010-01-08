<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageModuleDA extends AutoPageModuleDA
	{
		/**
		 * @return PageModuleDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function getByPage(Page $page)
		{
			$dbQuery = "
				SELECT * FROM ".$this->getTable()." WHERE page_id = ?
				ORDER BY priority, priority IS NULL
			";
			
			return $this->getListCachedByQuery($dbQuery, array($page->getId()));
		}
	}
?>