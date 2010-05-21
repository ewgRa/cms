<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageDA extends AutoPageDA
	{
		/**
		 * @return PageDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function getList()
		{
			$dbQuery =
				'SELECT * FROM '.$this->getTable()
				.' WHERE status = '.PageStatus::NORMAL;

			return $this->getListCachedByQuery(
				DatabaseQuery::create()->
				setQuery($dbQuery)
			);
		}

		/**
		 * @return Page
		 */
		public function getById($id)
		{
			$dbQuery =
				'SELECT * FROM '.$this->getTable()
				.' WHERE status = '.PageStatus::NORMAL.' AND id=?';

			return $this->getCachedByQuery(
				DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues(array($id))
			);
		}
	}
?>