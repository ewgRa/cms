<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class RightDA extends AutoRightDA
	{
		/**
		 * @return RightDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		/**
		 * @return Right
		 */
		public function getByAlias($alias)
		{
			return $this->getCachedByQuery(
				DatabaseQuery::create()->
				setQuery("SELECT * FROM ".$this->getTable()." WHERE alias = ?")->
				setValues(array($alias))
			);
		}
		
		/**
		 * @return Right
		 */
		public function getByAliases(array $aliases)
		{
			return $this->getListCachedByQuery(
				DatabaseQuery::create()->
				setQuery("SELECT * FROM ".$this->getTable()." WHERE alias IN(?)")->
				setValues(array($aliases))
			);
		}
		
		/**
		 * @return Right
		 */
		public function getById($id)
		{
			return $this->getCachedByQuery(
				DatabaseQuery::create()->
				setQuery('SELECT * FROM '.$this->getTable().' WHERE id = ?')->
				setValues(array($id))
			);
		}

		/**
		 * @return Right
		 */
		public function getByIds(array $ids)
		{
			return $this->getListCachedByQuery(
				DatabaseQuery::create()->
				setQuery("SELECT * FROM ".$this->getTable()." WHERE id IN (?)")->
				setValues(array($ids))
			);
		}
		
		public function getByInheritanceIds(array $ids)
		{
			$dbQuery = "
				SELECT t1.* FROM ".$this->getTable()." t1
				INNER JOIN ".$this->escapeTable('Right_inheritance')." t2
					ON(t2.right_id = t1.id)
				WHERE t2.child_right_id IN (?)
			";
			
			return $this->getListCachedByQuery(
				DatabaseQuery::create()->
				setQuery($dbQquery)->
				setValues(array($ids))
			);
		}
	}
?>