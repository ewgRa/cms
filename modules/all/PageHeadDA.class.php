<?php
	/* $Id$ */

	class PageHeadDA extends CmsDatabaseRequester
	{
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
				
		public function getPageHead(Page $page, Language $language)
		{
			$dbQuery = "
				SELECT title, description, keywords
				FROM " . $this->db()->getTable('PageData') . "
				WHERE
					page_id = ?
					AND language_id = ?
			";
			
			$dbResult = $this->db()->query(
				$dbQuery,
				array($page->getId(), $language->getId())
			);
			
			if(!$dbResult->recordCount()) {
				throw
					NotFoundException::create(
						'No page head for page "' . $page->getId()
						. '" and language "' . $language->getId() . '"'
					);
			}

			return $dbResult->fetchArray();
		}
	}
?>