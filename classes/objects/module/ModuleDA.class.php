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
			
			return $this->getCachedByQuery($dbQuery, array($id));
		}
		
		protected function build(array $array)
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