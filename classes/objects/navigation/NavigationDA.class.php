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
			
			return $this->getListCachedByQuery($dbQuery, array($category->getId()));
		}
		
		protected function build(array $array)
		{
			return
				Navigation::create()->
					setId($array['id'])->
					setCategoryId($array['category_id'])->
					setUri(HttpUrl::createFromString($array['uri']));
		}
	}
?>