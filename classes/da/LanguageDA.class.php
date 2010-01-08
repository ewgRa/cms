<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class LanguageDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Language';
		
		/**
		 * @return LanguageDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
				
		public function getById($id)
		{
			$dbQuery = "SELECT * FROM ".$this->getTable()." WHERE id=?";
			
			return $this->getCachedByQuery($dbQuery, array($id));
		}
		
		public function getList()
		{
			$dbQuery = "SELECT * FROM ".$this->getTable();
			
			return $this->getListCachedByQuery($dbQuery);
		}
		
		protected function build(array $array)
		{
			return
				Language::create()->
					setId($array['id'])->
					setAbbr($array['abbr']);
		}
	}
?>