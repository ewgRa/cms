<?php
	/* $Id$ */

	final class ContentDataDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'ContentData';
		
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
			$dbQuery = "
				SELECT * FROM " . $this->getTable() . "
				WHERE content_id = ? AND language_id = ?
			";
			
			return $this->getListCachedByQuery(
				$dbQuery,
				array($content->getId(), $language->getId())
			);
		}
		
		public function getList(
			$contentList = null,
			$languageList = null
		) {
			if (!is_array($contentList) && $contentList) {
				$contentList = array(
					$contentList->getId() => $contentList
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
			
			if ($contentList) {
				$params[] = array_keys($contentList);
				
				$queryParts[] = 'content_id IN(?)';
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
				ContentData::create()->
					setContentId($array['content_id'])->
					setLanguageId($array['language_id'])->
					setText($array['text']);
		}
	}
?>