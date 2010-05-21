<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class FileSourceDA extends AutoFileSourceDA
	{
		/**
		 * @return FileSourceDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		/**
		 * @return FileSource
		 */
		public function getById($id)
		{
			return $this->getCachedByQuery(
				DatabaseQuery::create()->
				setQuery("SELECT * FROM ".$this->getTable()." WHERE id = ?")->
				setValues(array($id))
			);
		}
	}
?>