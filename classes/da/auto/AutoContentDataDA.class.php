<?php
	/* $Id */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoContentDataDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'ContentData';
		
		/**
		 * @return ContentData
		 */		
		public function insert(ContentData $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getContentId())) {
				$queryParts[] = 'content_id = ?';
				$queryParams[] = $object->getContentId();
			}
			
			if (!is_null($object->getLanguageId())) {
				$queryParts[] = 'language_id = ?';
				$queryParams[] = $object->getLanguageId();
			}
			
			if (!is_null($object->getText())) {
				$queryParts[] = 'text = ?';
				$queryParams[] = $object->getText();
			}
			
			$dbQuery .= join(', ', $queryParts);
			$this->db()->query($dbQuery, $queryParams);
			 
			$object->setId($this->db()->getInsertedId());
			
			return $object;
		}

		/**
		 * @return ContentData
		 */
		protected function build(array $array)
		{
			return
				ContentData::create()->
					setContentId($array['content_id'])->
					setLanguageId($array['language_id'])->
					setText($array['text']);
		}
	}
?>