<?php
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoLayoutDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Layout';
		
		/**
		 * @return Layout
		 */
		public function insert(Layout $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getViewFileId())) {
				$queryParts[] = 'view_file_id = ?';
				$queryParams[] = $object->getViewFileId();
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
		 * @return AutoLayoutDA
		 */
		public function save(Layout $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';
			
			$queryParts = array();
			$whereParts = array();
			$queryParams = array();
			
			$queryParts[] = 'view_file_id = ?';
			$queryParams[] = $object->getViewFileId();
			
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
		 * @return Layout
		 */
		public function build(array $array)
		{
			return
				Layout::create()->
				setId($array['id'])->
				setViewFileId($array['view_file_id']);
		}

		public function dropCache()
		{
			Page::da()->dropCache();
			return parent::dropCache();
		}
	}
?>