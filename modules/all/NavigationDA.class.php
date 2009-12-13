<?php
	/* $Id$ */

	class NavigationDA extends CmsDatabaseRequester
	{
		public static function create()
		{
			return new self;
		}
		
		public function getByCategory($category, Language $language)
		{
			$dbQuery = "
				SELECT *
				FROM " . $this->db()->getTable('Navigations') . " t1
				INNER JOIN " . $this->db()->getTable('NavigationsData') . " t2
					ON(t1.id = t2.navigation_id AND t2.language_id = ?)
				WHERE
					t1.category_id = (
						SELECT id FROM " . $this->db()->getTable('Categories') . "
						WHERE alias = ?
					)
			";
			
			$dbResult = $this->db()->query(
				$dbQuery,
				array(
					$language->getId(),
					$category
				)
			);
			
			if(!$dbResult->recordCount()) {
				throw
					NotFoundException::create(
						'No navigation for category "' . $category
						. '" and language "' . $language->getId() . '"'
					);
			}
			
			return $dbResult->fetchList();
		}
	}
?>