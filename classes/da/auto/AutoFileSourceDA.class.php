<?php
	namespace ewgraCms;
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoFileSourceDA extends DatabaseRequester
	{
		protected $tableAlias = 'FileSource';
		
		/**
		 * @return FileSource
		 */
		public function insert(FileSource $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getAlias())) {
				$queryParts[] = '`alias` = ?';
				$queryParams[] = $object->getAlias();
			}
			
			$dbQuery .= join(', ', $queryParts);
			
			$this->db()->query(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);
			
			$object->setId($this->db()->getInsertedId());
			
			$this->dropCache();
			
			return $object;
		}

		/**
		 * @return AutoFileSourceDA
		 */
		public function save(FileSource $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';
			
			$queryParts = array();
			$whereParts = array();
			$queryParams = array();
			
			$queryParts[] = '`alias` = ?';
			$queryParams[] = $object->getAlias();
			
			$whereParts[] = 'id = ?';
			$queryParams[] = $object->getId();
			\ewgraFramework\Assert::isNotEmpty($whereParts);
			
			$dbQuery .= join(', ', $queryParts).' WHERE '.join(' AND ', $whereParts);

			$this->db()->query(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);
			 
			$this->dropCache();
			
			return $object;
		}

		/**
		 * @return FileSource
		 */
		public function build(array $array)
		{
			return
				FileSource::create()->
				setId($array['id'])->
				setAlias($array['alias']);
		}

		public function dropCache()
		{
			ViewFile::da()->dropCache();
			return parent::dropCache();
		}
	}
?>