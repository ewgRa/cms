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
			
			return $this->getListCachedByQuery($dbQuery, array($ids));
		}
				
		protected function build(array $array)
		{
			return
				Content::create()->
					setId($array['id'])->
					setStatus(ContentStatus::create($array['status']));
		}
	}
?>