<?php
	/* $Id */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoFileSourceDA extends CmsDatabaseRequester
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
				$queryParts[] = 'alias = ?';
				$queryParams[] = $object->getAlias();
			}
			
			$dbQuery .= join(', ', $queryParts);
			$this->db()->query($dbQuery, $queryParams);
			
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
			
			$queryParts[] = 'alias = ?';
			$queryParams[] = $object->getAlias();
			
			$whereParts = array();
			
			$whereParts[] = 'id = ?';
			$queryParams[] = $object->getId();
			
			$dbQuery .= join(', ', $queryParts). ' WHERE '.join(' AND ', $whereParts);
			$this->db()->query($dbQuery, $queryParams);
			 
			$this->dropCache();
			
			return $object;
		}

		/**
		 * @return FileSource
		 */
		protected function build(array $array)
		{
			return
				FileSource::create()->
					setId($array['id'])->
					setAlias($array['alias']);
		}
	}
?>