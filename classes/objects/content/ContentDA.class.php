<?php
	/* $Id$ */

	final class ContentDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Content';
		
		/**
		 * @return ContentDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function getByIds(array $ids)
		{
			$dbQuery = "
				SELECT * FROM ".$this->getTable()."
				WHERE id IN (?) AND status = 'normal'
			";
			
			$dbResult = $this->db()->query($dbQuery, array($ids));
			
			if($dbResult->recordCount() != count($ids)) {
				throw
					NotFoundException::create(
						'No content for one or more units "' . join('" , "', $ids)
					);
			}

			return $this->buildList($dbResult->fetchList());
		}
				
		private function buildList(array $arrayList)
		{
			$result = array();
			
			foreach ($arrayList as $array)
				$result[$array['id']] = $this->build($array);
			
			return $result;
		}
		
		private function build(array $array)
		{
			return
				Content::create()->
					setId($array['id'])->
					setStatus(ContentStatus::create($array['status']));
		}
	}
?>