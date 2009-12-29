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
				
		public function getByAlias($alias)
		{
			$dbQuery = 'SELECT * FROM '.$this->getTable().' WHERE alias = ?';

			return $this->getCachedByQuery($dbQuery, array($alias));
		}
		
		protected function build(array $array)
		{
			return
				Site::create()->
					setId($array['id'])->
					setAlias($array['alias']);
		}
	}
?>