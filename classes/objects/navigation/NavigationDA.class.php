<?php
	/* $Id$ */

	final class NavigationDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Navigation';
		
		/**
		 * @return NavigationDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function getByCategory(Category $category)
		{
			$dbQuery = "
				SELECT * FROM ".$this->getTable()."
				WHERE category_id = ?
			";
			
			$dbResult = $this->db()->query($dbQuery, array($category->getId()));
			
			if(!$dbResult->recordCount()) {
				throw
					NotFoundException::create(
						'No navigation for category "' . $category->getId() . '"'
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
				Navigation::create()->
					setId($array['id'])->
					setCategoryId($array['category_id'])->
					setUri(HttpUrl::createFromString($array['uri']));
		}
	}
?>