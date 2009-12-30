<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Page';
		
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

		/**
		 * @return Page
		 */
		protected function build(array $array) {
			return
				Page::create()->
					setId($array['id'])->
					setPath($array['path'])->
					setPreg($array['preg'] == 1)->
					setLayoutId($array['layout_id'])->
					setStatus($array['status'])->
					setModified($array['modified']);
		}
	}
?>