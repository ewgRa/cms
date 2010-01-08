<?php
	/* $Id */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoUserRightDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'UserRight';
		
		/**
		 * @return UserRight
		 */		
		public function insert(UserRight $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getUserId())) {
				$queryParts[] = 'user_id = ?';
				$queryParams[] = $object->getUserId();
			}
			
			if (!is_null($object->getRightId())) {
				$queryParts[] = 'right_id = ?';
				$queryParams[] = $object->getRightId();
			}
			
			$dbQuery .= join(', ', $queryParts);
			$this->db()->query($dbQuery, $queryParams);
			 
			$object->setId($this->db()->getInsertedId());
			
			$this->dropCache();
			
			return $object;
		}

		/**
		 * @return UserRight
		 */
		protected function build(array $array)
		{
			return
				UserRight::create()->
					setUserId($array['user_id'])->
					setRightId($array['right_id']);
		}
	}
?>