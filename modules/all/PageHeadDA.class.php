<?php
	/* $Id$ */

	class PageHeadDA extends DatabaseRequester
	{
		public static function create()
		{
			return new self;
		}
		
		public function getPageHead($pageId, $language)
		{
			$dbQuery = "
				SELECT title, description, keywords
				FROM " . $this->db()->getTable('PagesData') . "
				WHERE
					page_id = ?
					AND language_id = ?
			";
			
			$dbResult = $this->db()->query(
				$dbQuery,
				array($pageId, $language)
			);
			
			if(!$this->db()->recordCount($dbResult))
				throw
					NotFoundException::create()->
						setMessage(
							'No page head for page "' . $pageId
							. '" and language "' . $language . '"'
						);

			return $this->db()->fetchArray($dbResult);
		}
	}
?>