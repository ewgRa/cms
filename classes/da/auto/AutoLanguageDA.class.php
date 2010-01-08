<?php
	/* $Id */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoLanguageDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Language';
		
		/**
		 * @return Language
		 */		
		public function insert(Language $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getAbbr())) {
				$queryParts[] = 'abbr = ?';
				$queryParams[] = $object->getAbbr();
			}
			
			$dbQuery .= join(', ', $queryParts);
			$this->db()->query($dbQuery, $queryParams);
			 
			$object->setId($this->db()->getInsertedId());
			
			$this->dropCache();
			
			return $object;
		}

		/**
		 * @return Language
		 */
		protected function build(array $array)
		{
			return
				Language::create()->
					setId($array['id'])->
					setAbbr($array['abbr']);
		}
	}
?>