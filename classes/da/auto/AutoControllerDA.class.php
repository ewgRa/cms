<?php
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoControllerDA extends DatabaseRequester
	{
		protected $tableAlias = 'Controller';
		
		/**
		 * @return Controller
		 */
		public function insert(Controller $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getName())) {
				$queryParts[] = 'name = ?';
				$queryParams[] = $object->getName();
			}
			
			if (!is_null($object->getSettings())) {
				$queryParts[] = 'settings = ?';
				$queryParams[] = serialize($object->getSettings());
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
		 * @return AutoControllerDA
		 */
		public function save(Controller $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';
			
			$queryParts = array();
			$whereParts = array();
			$queryParams = array();
			
			$queryParts[] = 'name = ?';
			$queryParams[] = $object->getName();
			$queryParts[] = 'settings = ?';
			$queryParams[] = serialize($object->getSettings());
			
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
		 * @return Controller
		 */
		protected function build(array $array)
		{
			return
				Controller::create()->
				setId($array['id'])->
				setName($array['name'])->
				setSettings($array['settings'] ? unserialize($array['settings']) : null);
		}

		public function dropCache()
		{
			PageController::da()->dropCache();
			return parent::dropCache();
		}
	}
?>