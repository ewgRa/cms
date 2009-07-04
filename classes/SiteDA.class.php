<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * // FIXME: tested?
	*/
	final class SiteDA extends CmsDatabaseRequester
	{
		/**
		 * @return SiteDA
		 */
		public static function create()
		{
			return new self;
		}
		
		public function getSiteByAlias($alias)
		{
			$result = null;
			
			$dbQuery = '
				SELECT id
				FROM ' . $this->db()->getTable('Site') . '
				WHERE alias = ?
			';

			$dbResult = $this->db()->query($dbQuery, array($alias));

			if($dbResult->recordCount())
				$result = $dbResult->fetchArray();
			else
				throw NotFoundException::create();
			
			return
				Site::create()->
					setAlias($alias)->
					setId($result['id']);
		}
	}
?>