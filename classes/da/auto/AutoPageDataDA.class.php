<?php
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoPageDataDA extends DatabaseRequester
	{
		protected $tableAlias = 'PageData';
		
		/**
		 * @return PageData
		 */
		public function insert(PageData $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getPageId())) {
				$queryParts[] = 'page_id = ?';
				$queryParams[] = $object->getPageId();
			}
			
			if (!is_null($object->getLanguageId())) {
				$queryParts[] = 'language_id = ?';
				$queryParams[] = $object->getLanguageId();
			}
			
			if (!is_null($object->getTitle())) {
				$queryParts[] = 'title = ?';
				$queryParams[] = $object->getTitle();
			}
			
			if (!is_null($object->getDescription())) {
				$queryParts[] = 'description = ?';
				$queryParams[] = $object->getDescription();
			}
			
			if (!is_null($object->getKeywords())) {
				$queryParts[] = 'keywords = ?';
				$queryParams[] = $object->getKeywords();
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
		 * @return AutoPageDataDA
		 */
		public function save(PageData $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';
			
			$queryParts = array();
			$whereParts = array();
			$queryParams = array();
			
			$queryParts[] = 'page_id = ?';
			$queryParams[] = $object->getPageId();
			$queryParts[] = 'language_id = ?';
			$queryParams[] = $object->getLanguageId();
			$queryParts[] = 'title = ?';
			$queryParams[] = $object->getTitle();
			$queryParts[] = 'description = ?';
			$queryParams[] = $object->getDescription();
			$queryParts[] = 'keywords = ?';
			$queryParams[] = $object->getKeywords();
			
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
		 * @return PageData
		 */
		protected function build(array $array)
		{
			return
				PageData::create()->
				setId($array['id'])->
				setPageId($array['page_id'])->
				setLanguageId($array['language_id'])->
				setTitle($array['title'])->
				setDescription($array['description'])->
				setKeywords($array['keywords']);
		}
	}
?>