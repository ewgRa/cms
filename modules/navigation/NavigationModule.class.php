<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NavigationModule extends CmsModule
	{
		private $categoryAlias = null;
		
		/**
		 * @var NavigationCacheWorker
		 */
		private $cacheWorker = null;
		
		public function getCategoryAlias()
		{
			return $this->categoryAlias;
		}
		
		public function setCategoryAlias($categoryAlias)
		{
			$this->categoryAlias = $categoryAlias;
			return $this;
		}
		
		/**
		 * @return NavigationCacheWorker
		 */
		public function cacheWorker()
		{
			if (!$this->cacheWorker) {
				$this->cacheWorker = NavigationCacheWorker::create()->
					setModule($this);
			}

			return $this->cacheWorker;
		}
		
		/**
		 * @return NavigationModule
		 */
		public function importSettings(array $settings = null)
		{
			$this->setCategoryAlias($settings['category']);

			if ($cacheTicket = $this->cacheWorker()->createTicket())
				$this->setCacheTicket($cacheTicket);
			
			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$result = array();
			
			$result['navigationList'] =
				Navigation::da()->getByCategory(
					Category::da()->getByAlias($this->getCategoryAlias())
				);

			$result['navigationDataList'] = array();
			
			$navigationDataList =
				NavigationData::da()->getList(
					$result['navigationList'],
					$this->getLocalizer()->getRequestLanguage()
				);
				
			foreach ($navigationDataList as $navigationData) {
				$result['navigationDataList'][$navigationData->getNavigationId()] =
					$navigationData;
			}
			
			$result['baseUrl'] = $this->getPage()->getBaseUrl()->getPath();
			
			return Model::create()->setData($result);
		}
	}
?>