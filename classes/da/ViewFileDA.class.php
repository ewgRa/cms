<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ViewFileDA extends AutoViewFileDA
	{
		/**
		 * @return ViewFileDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
	
		public function getByPage(Page $page)
		{
			$dbQuery = "
				SELECT DISTINCT t1.* FROM ".$this->getTable()." t1
				INNER JOIN ".PageModule::da()->getTable()." t2
					ON(t2.view_file_id = t1.id)
				WHERE t2.page_id = ?
				UNION
				SELECT DISTINCT t3.* FROM ".Page::da()->getTable()." t1
				INNER JOIN ".Layout::da()->getTable()." t2
					ON(t2.id = t1.layout_id)
				INNER JOIN ".$this->getTable()." t3
					ON(t3.id = t2.view_file_id)
				WHERE t1.id = ?
			";
			
			return $this->getListCachedByQuery(
				DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues(array($page->getId(), $page->getId()))
			);
		}
		
		public function getInheritanceByIds(array $ids)
		{
			$dbQuery = "
				SELECT t1.* FROM ".$this->getTable()." t1
				INNER JOIN ".$this->quoteTable('ViewFile_inheritance')." t2
					ON(t2.child_view_file_id = t1.id)
				WHERE t2.view_file_id IN (?)
				ORDER BY position
			";
			
			return $this->getListCachedByQuery(
				DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues(array($ids))
			);
		}

		/**
		 * @return ViewFile
		 */
		public function getById($id)
		{
			return $this->getCachedByQuery(
				DatabaseQuery::create()->
				setQuery("SELECT * FROM ".$this->getTable()." WHERE id = ?")->
				setValues(array($id))
			);
		}
	}
?>