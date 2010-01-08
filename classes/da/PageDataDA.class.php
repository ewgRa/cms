<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageDataDA extends AutoPageDataDA
	{
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
			array $pageList = null,
			array $languageList = null
		) {
			$dbQuery = "SELECT * FROM " . $this->getTable();

			$queryParts = array('1');
			$params = array();
			
			if ($pageList) {
				$params[] = ArrayUtils::getObjectIds($pageList);
				$queryParts[] = 'page_id IN(?)';
			}
			
			if ($languageList) {
				$params[] = ArrayUtils::getObjectIds($languageList);
				$queryParts[] = 'language_id IN(?)';
			}
			
			$dbQuery .= ' WHERE '.join(' AND ', $queryParts);
			
			return $this->getListCachedByQuery(
				$dbQuery,
				$params
			);
		}
	}
?>