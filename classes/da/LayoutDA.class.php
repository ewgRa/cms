<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class LayoutDA extends AutoLayoutDA
	{
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
			return $this->getCachedByQuery(
				DatabaseQuery::create()->
				setQuery("SELECT * FROM ".$this->getTable()." WHERE id = ?")->
				setValues(array($id))
			);
		}
	}
?>