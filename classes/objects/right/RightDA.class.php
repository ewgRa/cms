<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class RightDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Right';
		
		/**
		 * @return RightDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getById($id)
		{
			$dbQuery = "SELECT * FROM ".$this->getTable()." WHERE id = ?";
			
			$dbResult = $this->db()->query($dbQuery, array($id));
			
			return $this->build($dbResult->fetchArray());
		}

		public function getByInheritanceIds(array $ids)
		{
			$dbQuery = "
				SELECT t1.* FROM ".$this->getTable()." t1
				INNER JOIN ".$this->db()->getTable('Right_inheritance')." t2
					ON(t2.right_id = t1.id)
				WHERE t2.child_right_id IN (?)
			";
			
			$dbResult = $this->db()->query($dbQuery, array($ids));
			
			return $this->buildList($dbResult->fetchList());
		}

		private function buildList(array $arrayList) {
			$result = array();
			
			foreach ($arrayList as $array) {
				$object = $this->build($array);
				$result[$object->getId()] = $object;
			}
			
			return $result;
		}
		
		private function build(array $array) {
			return
				Right::create()->
					setId($array['id'])->
					setAlias($array['alias'])->
					setName($array['name'])->
					setRole($array['role']);
		}
	}
?>