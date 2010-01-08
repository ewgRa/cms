<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SiteDA extends AutoSiteDA
	{
		/**
		 * @return SiteDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		/**
		 * @return Site
		 */
		public function getByAlias($alias)
		{
			$dbQuery = 'SELECT * FROM '.$this->getTable().' WHERE alias = ?';

			return $this->getCachedByQuery($dbQuery, array($alias));
		}
	}
?>