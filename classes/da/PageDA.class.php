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
			$dbQuery = 'SELECT * FROM '.$this->getTable().' WHERE status = \'normal\'';

			return $this->getListCachedByQuery($dbQuery);
		}

		/**
		 * @return Page
		 */
		public function getById($id)
		{
			$dbQuery = 'SELECT * FROM '.$this->getTable().' WHERE status = \'normal\' AND id=?';

			return $this->getCachedByQuery($dbQuery, array($id));
		}
	}
?>