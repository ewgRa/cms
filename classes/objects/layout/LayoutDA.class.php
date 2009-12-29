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
			
			$dbResult = $this->db()->query($dbQuery, array($id));
			
			if(!$dbResult->recordCount())
				throw NotFoundException::create();
			
			return $this->build($dbResult->fetchArray());
		}
		
		private function buildList(array $arrayList) {
			$result = array();
			
			foreach ($arrayList as $array) {
				$object = $this->build($array);
				$result[$object->getId()] = $object;
			}
			
			return $result;
		}
		
		private function build(array $array) {
			return
				Layout::create()->
					setId($array['id'])->
					setViewFileId($array['view_file_id']);
		}
	}
?>