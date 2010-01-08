<?php
	/* $Id */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoRightDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Right';
		
		/**
		 * @return Right
		 */		
		public function insert(Right $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParams = array();
			
			if ($object->hasAlias())) {
				$dbQuery .= 'alias = ?';
				$queryParams[] = $object->getAlias();
			}
			
			if ($object->hasName())) {
				$dbQuery .= 'name = ?';
				$queryParams[] = $object->getName();
			}
			
			if ($object->hasRole())) {
				$dbQuery .= 'role = ?';
				$queryParams[] = $object->getRole()->getId();
			}
			
			$this->db()->query($dbQuery, $queryParams);
			 
			$object->setId($this->db()->getInsertedId());
			
			return $object;
		}

		/**
		 * @return Right
		 */
		protected function build(array $array)
		{
			return
				Right::create()->
					setId($array['id'])->
					setAlias($array['alias'])->
					setName($array['name'])->
					setRole($array['role'] == 1);
		}
	}
?>