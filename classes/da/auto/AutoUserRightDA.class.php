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
			$queryParams = array();
			
			if ($object->hasUserId())) {
				$dbQuery .= 'user_id = ?';
				$queryParams[] = $object->getUserId();
			}
			
			if ($object->hasRightId())) {
				$dbQuery .= 'right_id = ?';
				$queryParams[] = $object->getRightId();
			}
			
			$this->db()->query($dbQuery, $queryParams);
			 
			$object->setId($this->db()->getInsertedId());
			
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