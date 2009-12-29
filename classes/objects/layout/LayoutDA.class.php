<?php
	/* $Id$ */

	final class LayoutDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Layout';
		
		/**
		 * @return LayoutDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		/**
		 * @return Layout
		 */
		public function getById($id)
		{
			$dbQuery = "SELECT * FROM ".$this->getTable()." WHERE id = ?";
			
			return $this->getCachedByQuery($dbQuery, array($id));
		}
		
		protected function build(array $array) {
			return
				Layout::create()->
					setId($array['id'])->
					setViewFileId($array['view_file_id']);
		}
	}
?>