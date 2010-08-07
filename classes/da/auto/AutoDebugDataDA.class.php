<?php
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoDebugDataDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'DebugData';
		
		/**
		 * @return DebugData
		 */
		public function insert(DebugData $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getSession())) {
				$queryParts[] = 'session = ?';
				$queryParams[] = $object->getSession();
			}
			
			if (!is_null($object->getData())) {
				$queryParts[] = 'data = ?';
				$queryParams[] = serialize($object->getData());
			}
			
			if (!is_null($object->getDate())) {
				$queryParts[] = 'date = ?';
				$queryParams[] = $object->getDate();
			}
			
			$dbQuery .= join(', ', $queryParts);
			
			$this->db()->query(
				DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);
			
			$object->setId($this->db()->getInsertedId());
			
			$this->dropCache();
			
			return $object;
		}

		/**
		 * @return AutoDebugDataDA
		 */
		public function save(DebugData $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';
			
			$queryParts = array();
			$whereParts = array();
			$queryParams = array();
			
			$queryParts[] = 'session = ?';
			$queryParams[] = $object->getSession();
			$queryParts[] = 'data = ?';
			$queryParams[] = serialize($object->getData());
			$queryParts[] = 'date = ?';
			$queryParams[] = $object->getDate();
			
			$whereParts[] = 'id = ?';
			$queryParams[] = $object->getId();
			Assert::isNotEmpty($whereParts);
			
			$dbQuery .= join(', ', $queryParts).' WHERE '.join(' AND ', $whereParts);

			$this->db()->query(
				DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);
			 
			$this->dropCache();
			
			return $object;
		}

		/**
		 * @return DebugData
		 */
		public function build(array $array)
		{
			return
				DebugData::create()->
				setId($array['id'])->
				setSession($array['session'])->
				setData($array['data'] ? unserialize($array['data']) : null)->
				setDate($array['date']);
		}
	}
?>