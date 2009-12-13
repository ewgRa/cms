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
			
			$dbResult = $this->db()->query(
				$dbQuery,
				array($navigation->getId(), $language->getId())
			);
			
			if(!$dbResult->recordCount())
				throw NotFoundException::create();
			
			return $this->build($dbResult->fetchArray());
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
			
			$dbQuery = "SELECT * FROM " . $this->db()->getTable('NavigationData');

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
			
			$dbResult = $this->db()->query(
				$dbQuery,
				$params
			);
			
			if(!$dbResult->recordCount())
				throw NotFoundException::create();
			
			return $this->buildList($dbResult->fetchList());
		}
		
		private function buildList(array $arrayList) {
			$result = array();
			
			foreach ($arrayList as $array) {
				$object = $this->build($array);
				$result[$object->getId()] = $object;
			}
			
			return $result;
		}
		
		private function build(array $array) {
			return
				NavigationData::create()->
					setNavigationId($array['navigation_id'])->
					setLanguageId($array['language_id'])->
					setText($array['text']);
		}
	}
?>