<?php
	/* $Id$ */

	class NavigationModule extends Module
	{
		private $categoryAlias = null;
		
		public function importSettings($settings)
		{
			$this->setCategoryAlias($settings['category']);

			if(Cache::me()->getPool('cms')->hasTicketParams('navigation'))
			{
				$this->setCacheTicket(
					Cache::me()->getPool('cms')->createTicket('navigation')->
						setKey(
							$this->getCategoryAlias(),
							$this->getLocalizer()->getRequestLanguage(),
							$this->getLocalizer()->getSource(),
							$this->getPage()->getBaseUrl()->getPath()
						)
				);
			}
			
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

		private function setCategoryAlias($categoryAlias)
		{
			$this->categoryAlias = $categoryAlias;
			return $this;
		}
		
		private function getCategoryAlias()
		{
			return $this->categoryAlias;
		}
		
		private function getLocalizer()
		{
			return
				$this->getRequest()->getAttachedVar(AttachedAliases::LOCALIZER);
		}

		private function getPage()
		{
			return
				$this->getRequest()->getAttachedVar(AttachedAliases::PAGE);
		}
	}
?>