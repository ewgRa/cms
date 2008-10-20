<?php
	/* $Id$ */

	class PageViewFilesDA extends DatabaseRequester
	{
		public static function create()
		{
			return new self;
		}
		
		public function getPageViewFiles($pageId)
		{
			$dbQuery = "
				SELECT view_file_id
				FROM " . $this->db()->getTable('PagesControllers_ref') . "
				WHERE page_id = ?
			";
			
			$dbResult = $this->db()->query($dbQuery, array($pageId));
			
			return $this->db()->resourceToArray($dbResult);
		}
		
		public function getFiles($pageId, $fileIds)
		{
			$dbQuery = '
				SELECT
					t2.id, t2.path, t2.is_can_splited, t1.recursive_include,
					t2.`content-type`
				FROM ' . $this->db()->getTable('ViewFilesIncludes') . ' t1
				INNER JOIN ' . $this->db()->getTable('ViewFiles') . ' t2
					ON(t2.id = t1.include_file_id AND t1.file_id IN(?))
				WHERE t1.page_id IS NULL or t1.page_id = ?
				ORDER BY t1.position ASC
			';
			
			$dbResult = $this->db()->query($dbQuery, array($fileIds, $pageId));
						
			return $this->db()->resourceToArray($dbResult);
		}
	}
?>