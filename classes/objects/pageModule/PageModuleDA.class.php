<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageModuleDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'PageModule';
		
		/**
		 * @return RightDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getByPage(Page $page)
		{
			$dbQuery = "
				SELECT * FROM ".$this->getTable()." WHERE page_id = ?
				ORDER BY priority, priority IS NULL
			";
			
			return $this->getListCachedByQuery($dbQuery, array($page->getId()));
		}

		/**
		 * @return PageModule
		 */
		protected function build(array $array) {
			$settings =
				$array['settings']
					? unserialize($array['settings'])
					: null;
			
			return
				PageModule::create()->
					setPageId($array['page_id'])->
					setModuleId($array['module_id'])->
					setSection($array['section'])->
					setPosition($array['position'])->
					setPriority($array['priority'])->
					setSettings($settings)->
					setViewFileId($array['view_file_id']);
		}
	}
?>