<?php
	/* $Id$ */

	class ContentDA extends CmsDatabaseRequester
	{
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
				
		public function getUnitsContent($units, $language)
		{
			$dbQuery = "
				SELECT * FROM " . $this->db()->getTable('Content') . " t1
				INNER JOIN " . $this->db()->getTable('ContentData') . " t2
					ON( t1.id = t2.content_id AND t2.language_id = ? )
				WHERE
					t1.id IN (?) AND t1.status = 'normal'
			";
			
			$dbResult = $this->db()->query(
				$dbQuery,
				array(
					$language,
					$units
				)
			);
			
			if($dbResult->recordCount() != count($units)) {
				throw
					NotFoundException::create(
						'No content for one or more units "' . join('" , "', $units)
						. '" and language "' . $language . '"'
					);
			}

			return $dbResult->fetchList();
		}
	}
?>