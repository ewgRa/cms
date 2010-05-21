<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ContentDataDA extends AutoContentDataDA
	{
		/**
		 * @return ContentDataDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		/**
		 * @return ContentData
		 */
		public function get(Content $content, Language $language)
		{
			return $this->getCachedByQuery(
				DatabaseQuery::create()->
				setQuery(
					"SELECT * FROM ".$this->getTable()
					." WHERE content_id = ? AND language_id = ?"
				)->
				setValues(array($content->getId(), $language->getId()))
			);
		}
		
		public function getList(
			array $contentList = null,
			array $languageList = null
		) {
			$dbQuery = "SELECT * FROM " . $this->getTable();

			$queryParts = array('1');
			$params = array();
			
			if ($contentList) {
				$params[] = ArrayUtils::getObjectIds($contentList);
				$queryParts[] = 'content_id IN(?)';
			}
			
			if ($languageList) {
				$params[] = ArrayUtils::getObjectIds($languageList);
				$queryParts[] = 'language_id IN(?)';
			}
			
			$dbQuery .= ' WHERE '.join(' AND ', $queryParts);
			
			return $this->getListCachedByQuery(
				DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($params)
			);
		}
	}
?>