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
			
			$dbResult = $this->db()->query($dbQuery, array($id));
			
			return $this->build($dbResult->fetchArray());
		}
		
		public function getList()
		{
			$dbQuery = "SELECT * FROM ".$this->getTable();
			
			$dbResult = $this->db()->query($dbQuery);
			
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
				Language::create()->
					setId($array['id'])->
					setAbbr($array['abbr']);
		}
	}
?>