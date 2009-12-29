<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageDataDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'PageData';
		
		/**
		 * @return PageDataDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		/**
		 * @return PageData
		 */
		public function get(Page $page, Language $language)
		{
			$dbQuery = "
				SELECT * FROM " . $this->getTable() . "
				WHERE page_id = ? AND language_id = ?
			";
			
			return $this->getCachedByQuery(
				$dbQuery,
				array($page->getId(), $language->getId())
			);
		}
		
		public function getList(
			$pageList = null,
			$languageList = null
		) {
			if (!is_array($pageList) && $pageList) {
				$pageList = array(
					$pageList->getId() => $pageList
				);
			}
			
			if (!is_array($languageList) && $languageList) {
				$languageList = array(
					$languageList->getId() => $languageList
				);
			}
			
			$dbQuery = "SELECT * FROM " . $this->getTable();

			$queryParts = array('1');
			$params = array();
			
			if ($pageList) {
				$params[] = array_keys($pageList);
				
				$queryParts[] = 'page_id IN(?)';
			}
			
			if ($languageList) {
				$params[] = array_keys($languageList);
				
				$queryParts[] = 'language_id IN(?)';
			}
			
			$dbQuery .= ' WHERE '.join(' AND ', $queryParts);
			
			return $this->getListCachedByQuery(
				$dbQuery,
				$params
			);
		}
		
		protected function build(array $array) {
			return
				PageData::create()->
					setPageId($array['page_id'])->
					setLanguageId($array['language_id'])->
					setTitle($array['title'])->
					setDescription($array['description'])->
					setKeywords($array['keywords']);
		}
	}
?>