<?php
	/* $Id$ */

	class NavigationDA extends DatabaseRequester
	{
		public static function create()
		{
			return new self;
		}
		
		public function getByCategory($category, $language)
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
					$language,
					$category
				)
			);
			
			if(!$this->db()->recordCount($dbResult))
				throw
					ExceptionsMapper::me()->createException('NotFound')->
						setMessage(
							'No navigation for category "' . $category
							. '" and language "' . $language . '"'
						);
			
			return $this->db()->resourceToArray($dbResult);
		}
	}
?>