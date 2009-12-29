<?php
	/* $Id$ */

	final class NavigationDataDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'NavigationData';
		
		/**
		 * @return NavigationDataDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		/**
		 * @return NavigationData
		 */
		public function get(Navigation $navigation, Language $language)
		{
			$dbQuery = "
				SELECT * FROM " . $this->getTable() . "
				WHERE navigation_id = ? AND language_id = ?
			";
			
			return $this->getCachedByQuery(
				$dbQuery,
				array($navigation->getId(), $language->getId())
			);
		}
		
		public function getList(
			$navigationList = null,
			$languageList = null
		) {
			if (!is_array($navigationList) && $navigationList) {
				$navigationList = array(
					$navigationList->getId() => $navigationList
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
			
			if ($navigationList) {
				$params[] = array_keys($navigationList);
				
				$queryParts[] = 'navigation_id IN(?)';
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
				NavigationData::create()->
					setNavigationId($array['navigation_id'])->
					setLanguageId($array['language_id'])->
					setText($array['text']);
		}
	}
?>