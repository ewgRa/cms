<?php
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoPageRightDA extends CmsDatabaseRequester
	{

		protected $tableAlias = 'PageRight';
		
		/**
		 * @return PageRight
		 */
		public function insert(PageRight $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getPageId())) {
				$queryParts[] = 'page_id = ?';
				$queryParams[] = $object->getPageId();
			}
			
			if (!is_null($object->getRightId())) {
				$queryParts[] = 'right_id = ?';
				$queryParams[] = $object->getRightId();
			}
			
			if (!is_null($object->getRedirectPageId())) {
				$queryParts[] = 'redirect_page_id = ?';
				$queryParams[] = $object->getRedirectPageId();
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
		 * @return AutoPageRightDA
		 */
		public function save(PageRight $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';
			
			$queryParts = array();
			$whereParts = array();
			$queryParams = array();
			
			$queryParts[] = 'page_id = ?';
			$queryParams[] = $object->getPageId();
			$queryParts[] = 'right_id = ?';
			$queryParams[] = $object->getRightId();
			$queryParts[] = 'redirect_page_id = ?';
			$queryParams[] = $object->getRedirectPageId();
			
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
		 * @return PageRight
		 */
		protected function build(array $array)
		{
			return
				PageRight::create()->
				setId($array['id'])->
				setPageId($array['page_id'])->
				setRightId($array['right_id'])->
				setRedirectPageId($array['redirect_page_id']);
		}
	}
?>