<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Page';
		
		/**
		 * @return PageDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getModules($pageId)
		{
			$dbQuery = '
				SELECT
					t1.*, t2.section_id, t2.position_in_section, t2.module_settings,
					t2.view_file_id
				FROM ' . $this->db()->getTable('Module') . ' t1
				INNER JOIN ' . $this->db()->getTable('PageModule_ref') . ' t2
					ON(
						t1.id = t2.module_id
						AND t2.page_id = ?
					)
				ORDER BY load_priority, load_priority IS NULL
			';
			
			$dbResult = $this->db()->query($dbQuery, array($pageId));

			return $dbResult->fetchList();
		}
		
		public function getList()
		{
			$dbQuery = 'SELECT * FROM '.$this->getTable().' WHERE status = \'normal\'';

			$dbResult = $this->db()->query($dbQuery);
			
			return $this->buildList($dbResult->fetchList());
		}

		public function getById($id)
		{
			$dbQuery = 'SELECT * FROM '.$this->getTable().' WHERE status = \'normal\' AND id=?';

			$dbResult = $this->db()->query($dbQuery, array($id));
			
			if (!$dbResult->recordCount())
				throw new NotFoundException();
				
			return $this->build($dbResult->fetchArray());
		}

		private function buildList(array $arrayList) {
			$result = array();
			
			foreach ($arrayList as $array) {
				$object = $this->build($array);
				$result[$object->getId()] = $object;
			}
			
			return $result;
		}
		
		private function build(array $array) {
			return
				Page::create()->
					setId($array['id'])->
					// FIXME: realy needed?
					setPath(Config::me()->replaceVariables($array['path']))->
					setPreg($array['preg'])->
					setLayoutId($array['layout_id'])->
					setStatus($array['status'])->
					setModified($array['modified']);
		}
	}
?>