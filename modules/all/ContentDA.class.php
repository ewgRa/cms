<?php
	/* $Id$ */

	class ContentDA extends DatabaseRequester
	{
		public static function create()
		{
			return new self;
		}
		
		public function getUnitsContent($units, $language)
		{
			$dbQuery = "
				SELECT *
				FROM " . $this->db()->getTable('Contents') . " t1
				INNER JOIN " . $this->db()->getTable('ContentsData') . " t2
					ON( t1.id = t2.content_id AND t2.language_id = ? )
				WHERE
					t1.id IN (?)
			";
			
			$dbResult = $this->db()->query(
				$dbQuery,
				array(
					$language,
					$units
				)
			);

			return $this->db()->resourceToArray($dbResult);
		}
	}
?>