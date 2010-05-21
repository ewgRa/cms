<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class LanguageDA extends AutoLanguageDA
	{
		/**
		 * @return LanguageDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function getById($id)
		{
			return $this->getCachedByQuery(
				DatabaseQuery::create()->
				setQuery("SELECT * FROM ".$this->getTable()." WHERE id = ?")->
				setValues(array($id))
			);
		}
		
		public function getList()
		{
			return
				$this->getListCachedByQuery(
					DatabaseQuery::create()->
					setQuery("SELECT * FROM ".$this->getTable())
				);
		}
	}
?>