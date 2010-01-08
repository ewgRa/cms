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
		public function getByAliases(array $aliases)
		{
			$dbQuery = "SELECT * FROM ".$this->getTable()." WHERE alias IN(?)";
			
			return $this->getListCachedByQuery($dbQuery, array($aliases));
		}
		
		/**
		 * @return Right
		 */
		public function getById($id)
		{
			$dbQuery = "SELECT * FROM ".$this->getTable()." WHERE id = ?";
			
			return $this->getCachedByQuery($dbQuery, array($id));
		}

		public function getByInheritanceIds(array $ids)
		{
			$dbQuery = "
				SELECT t1.* FROM ".$this->getTable()." t1
				INNER JOIN ".$this->db()->getTable('Right_inheritance')." t2
					ON(t2.right_id = t1.id)
				WHERE t2.child_right_id IN (?)
			";
			
			return $this->getListCachedByQuery($dbQuery, array($ids));
		}
	}
?>