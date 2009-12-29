<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
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
			array $navigationList = null,
			array $languageList = null
		) {
			$dbQuery = "SELECT * FROM " . $this->getTable();

			$queryParts = array('1');
			$params = array();
			
			if ($navigationList) {
				$params[] = ArrayUtils::getObjectIds($navigationList);
				$queryParts[] = 'navigation_id IN(?)';
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
		
		protected function build(array $array) {
			return
				NavigationData::create()->
					setNavigationId($array['navigation_id'])->
					setLanguageId($array['language_id'])->
					setText($array['text']);
		}
	}
?>