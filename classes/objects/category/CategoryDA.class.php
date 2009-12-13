<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class CategoryDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Category';
		
		/**
		 * @return CategoryDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return Category
		 */
		public function getByAlias($alias)
		{
			$dbQuery = "SELECT * FROM ".$this->getTable()." WHERE alias = ?";
			
			$dbResult = $this->db()->query($dbQuery, array($alias));
			
			if(!$dbResult->recordCount()) {
				throw
					NotFoundException::create(
						'No category with alias "'.$alias.'"'
					);
			}
			
			return $this->build($dbResult->fetchArray());
		}
		
		/**
		 * @return Category
		 */
		private function build(array $array)
		{
			return
				Category::create()->
					setId($array['id'])->
					setAlias($array['alias']);
		}
	}
?>