<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NavigationModule extends CmsModule
	{
		private $categoryIds = null;
		
		/**
		 * @return NavigationModule
		 */
		public function setCategoryIds(array $categoryIds)
		{
			$this->categoryIds = $categoryIds;
			return $this;
		}
		
		public function getCategoryIds()
		{
			return $this->categoryIds;
		}
		
		/**
		 * @return NavigationModule
		 */
		public function importSettings(array $settings = null)
		{
			$this->setCategoryIds($settings['categories']);
			
			// TODO XXX: 'needRights'

			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$result = array();
			
			$result['navigationList'] =
				Navigation::da()->getByCategoryIds(
					$this->getCategoryIds()
				);

			$result['navigationDataList'] = array();
			
			$navigationDataList =
				NavigationData::da()->getList(
					$result['navigationList'],
					array($this->getLocalizer()->getRequestLanguage())
				);
				
			foreach ($navigationDataList as $navigationData) {
				$result['navigationDataList'][$navigationData->getNavigationId()] =
					$navigationData;
			}
			
			$result['baseUrl'] = $this->getBaseUrl();

			return Model::create()->setData($result);
		}
	}
?>