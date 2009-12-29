<?php
	/* $Id$ */

	final class ModuleDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Module';
		
		/**
		 * @return ModuleDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function getById($id)
		{
			$dbQuery = "SELECT * FROM ".$this->getTable()." WHERE id = ?";
			
			$dbResult = $this->db()->query($dbQuery, array($id));
			
			if(!$dbResult->recordCount())
				throw new NotFoundException();

			return $this->build($dbResult->fetchArray());
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
			$settings =
				$array['settings']
					? unserialize($array['settings'])
					: null;
			
			return
				Module::create()->
					setId($array['id'])->
					setName($array['name'])->
					setSettings($settings);
		}
	}
?>