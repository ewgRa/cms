<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * // FIXME: tested?
	*/
	final class SiteDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Site';
		
		/**
		 * @return SiteDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
				
		public function getSiteByAlias($alias)
		{
			$result = null;
			
			$dbQuery = 'SELECT id FROM '.$this->getTable().' WHERE alias = ?';

			$dbResult = $this->db()->query($dbQuery, array($alias));

			if(!$dbResult->recordCount())
				throw NotFoundException::create();
			
			$result = $dbResult->fetchArray();
				
			return
				Site::create()->
					setAlias($alias)->
					setId($result['id']);
		}
	}
?>