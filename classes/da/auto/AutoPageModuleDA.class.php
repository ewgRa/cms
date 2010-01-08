<?php
	/* $Id */
	
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
			$queryParams = array();
			
			if ($object->hasPageId())) {
				$dbQuery .= 'page_id = ?';
				$queryParams[] = $object->getPageId();
			}
			
			if ($object->hasModuleId())) {
				$dbQuery .= 'module_id = ?';
				$queryParams[] = $object->getModuleId();
			}
			
			if ($object->hasSection())) {
				$dbQuery .= 'section = ?';
				$queryParams[] = $object->getSection();
			}
			
			if ($object->hasPosition())) {
				$dbQuery .= 'position = ?';
				$queryParams[] = $object->getPosition();
			}
			
			if ($object->hasPriority())) {
				$dbQuery .= 'priority = ?';
				$queryParams[] = $object->getPriority();
			}
			
			if ($object->hasSettings())) {
				$dbQuery .= 'settings = ?';
				$queryParams[] = serialize($object->getSettings());
			}
			
			if ($object->hasViewFileId())) {
				$dbQuery .= 'view_file_id = ?';
				$queryParams[] = $object->getViewFileId();
			}
			
			$this->db()->query($dbQuery, $queryParams);
			 
			$object->setId($this->db()->getInsertedId());
			
			return $object;
		}

		/**
		 * @return PageModule
		 */
		protected function build(array $array)
		{
			return
				PageModule::create()->
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