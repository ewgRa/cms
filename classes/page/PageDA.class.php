<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageDA extends CmsDatabaseRequester
	{
		/**
		 * @return PageDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
				
		public function getPage($pageId)
		{
			$dbQuery = "
				SELECT
					t1.*, t2.file_id as layout_file_id
				FROM " . $this->db()->getTable('Page') . " t1
				LEFT JOIN " . $this->db()->getTable('Layout') . " t2
					ON( t2.id =	t1.layout_id)
				WHERE t1.id = ?
			";

			$dbResult = $this->db()->query(
				$dbQuery,
				array($pageId)
			);
			
			return $dbResult->fetchArray();
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
	}
?>