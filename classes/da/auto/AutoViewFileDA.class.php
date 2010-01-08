<?php
	/* $Id */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoViewFileDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'ViewFile';
		
		/**
		 * @return ViewFile
		 */		
		public function insert(ViewFile $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getContentType())) {
				$queryParts[] = 'content_type = ?';
				$queryParams[] = $object->getContentType()->getId();
			}
			
			if (!is_null($object->getPath())) {
				$queryParts[] = 'path = ?';
				$queryParams[] = $object->getPath();
			}
			
			if (!is_null($object->getJoinable())) {
				$queryParts[] = 'joinable = ?';
				$queryParams[] = $object->getJoinable()->getId();
			}
			
			$dbQuery .= join(', ', $queryParts);
			$this->db()->query($dbQuery, $queryParams);
			 
			$object->setId($this->db()->getInsertedId());
			
			return $object;
		}

		/**
		 * @return ViewFile
		 */
		protected function build(array $array)
		{
			return
				ViewFile::create()->
					setId($array['id'])->
					setContentType(ContentType::create($array['content_type']))->
					setPath(Config::me()->replaceVariables($array['path']))->
					setJoinable($array['joinable'] == 1);
		}
	}
?>