<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageRightDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'PageRight';
		
		/**
		 * @return RightDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getByPage(Page $page)
		{
			$dbQuery = "SELECT * FROM ".$this->getTable()." WHERE page_id = ?";
			
			return $this->getListCachedByQuery($dbQuery, array($page->getId()));
		}

		/**
		 * @return PageRight
		 */
		protected function build(array $array) {
			return
				PageRight::create()->
					setPageId($array['page_id'])->
					setRightId($array['right_id'])->
					setRedirectPageId($array['redirect_page_id']);
		}
	}
?>