<?php
	/* $Id */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoPageDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Page';
		
		/**
		 * @return Page
		 */		
		public function insert(Page $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getPath())) {
				$queryParts[] = 'path = ?';
				$queryParams[] = $object->getPath();
			}
			
			if (!is_null($object->getPreg())) {
				$queryParts[] = 'preg = ?';
				$queryParams[] = $object->getPreg()->getId();
			}
			
			if (!is_null($object->getLayoutId())) {
				$queryParts[] = 'layout_id = ?';
				$queryParams[] = $object->getLayoutId();
			}
			
			if (!is_null($object->getStatus())) {
				$queryParts[] = 'status = ?';
				$queryParams[] = $object->getStatus()->getId();
			}
			
			if (!is_null($object->getModified())) {
				$queryParts[] = 'modified = ?';
				$queryParams[] = $object->getModified();
			}
			
			$dbQuery .= join(', ', $queryParts);
			$this->db()->query($dbQuery, $queryParams);
			 
			$object->setId($this->db()->getInsertedId());
			
			return $object;
		}

		/**
		 * @return Page
		 */
		protected function build(array $array)
		{
			return
				Page::create()->
					setId($array['id'])->
					setPath($array['path'])->
					setPreg($array['preg'] == 1)->
					setLayoutId($array['layout_id'])->
					setStatus(PageStatus::create($array['status']))->
					setModified($array['modified']);
		}
	}
?>