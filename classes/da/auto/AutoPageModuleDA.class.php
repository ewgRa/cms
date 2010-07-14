<?php
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoPageModuleDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'PageModule';
		
		/**
		 * @return PageModule
		 */
		public function insert(PageModule $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getPageId())) {
				$queryParts[] = 'page_id = ?';
				$queryParams[] = $object->getPageId();
			}
			
			if (!is_null($object->getModuleId())) {
				$queryParts[] = 'module_id = ?';
				$queryParams[] = $object->getModuleId();
			}
			
			if (!is_null($object->getSection())) {
				$queryParts[] = 'section = ?';
				$queryParams[] = $object->getSection();
			}
			
			if (!is_null($object->getPosition())) {
				$queryParts[] = 'position = ?';
				$queryParams[] = $object->getPosition();
			}
			
			if (!is_null($object->getPriority())) {
				$queryParts[] = 'priority = ?';
				$queryParams[] = $object->getPriority();
			}
			
			if (!is_null($object->getSettings())) {
				$queryParts[] = 'settings = ?';
				$queryParams[] = serialize($object->getSettings());
			}
			
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
		 * @return AutoPageModuleDA
		 */
		public function save(PageModule $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';
			
			$queryParts = array();
			$whereParts = array();
			$queryParams = array();
			
			$queryParts[] = 'page_id = ?';
			$queryParams[] = $object->getPageId();
			$queryParts[] = 'module_id = ?';
			$queryParams[] = $object->getModuleId();
			$queryParts[] = 'section = ?';
			$queryParams[] = $object->getSection();
			$queryParts[] = 'position = ?';
			$queryParams[] = $object->getPosition();
			$queryParts[] = 'priority = ?';
			$queryParams[] = $object->getPriority();
			$queryParts[] = 'settings = ?';
			$queryParams[] = serialize($object->getSettings());
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
		 * @return PageModule
		 */
		protected function build(array $array)
		{
			return
				PageModule::create()->
				setId($array['id'])->
				setPageId($array['page_id'])->
				setModuleId($array['module_id'])->
				setSection($array['section'])->
				setPosition($array['position'])->
				setPriority($array['priority'])->
				setSettings($array['settings'] ? unserialize($array['settings']) : null)->
				setViewFileId($array['view_file_id']);
		}
	}
?>