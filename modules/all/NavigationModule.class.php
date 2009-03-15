<?php
	/* $Id$ */

	class NavigationModule extends Module
	{
		private $category = null;
		
		/**
		 * @var NavigationDA
		 */
		private $da = null;
		
		protected function da()
		{
			if(!$this->da)
				$this->da = NavigationDA::create();
			
			return $this->da;
		}
		
		private function setCategory($category)
		{
			$this->category = $category;
			return $this;
		}
		
		private function getCategory()
		{
			return $this->category;
		}
		
		public function importSettings($settings)
		{
			$this->setCategory($settings['category']);

			if(Cache::me()->hasTicketParams('navigation'))
			{
				$localizer = $this->getRequest()->getAttached(AttachedAliases::LOCALIZER);
				
				$page = $this->getRequest()->getAttached(AttachedAliases::PAGE);
			
				$this->setCacheTicket(
					Cache::me()->createTicket('navigation')->
						setKey(
							$this->getCategory(),
							$localizer->getRequestLanguage(),
							$localizer->getSource(),
							$page->getBaseUrl()->getPath()
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
			$localizer = $this->getRequest()->getAttached(AttachedAliases::LOCALIZER);
			
			$result = $this->da()->getByCategory(
				$this->getCategory(),
				$localizer->getRequestLanguage()->getId()
			);

			$page = $this->getRequest()->getAttached(AttachedAliases::PAGE);
			
			$result['baseUrl'] = $page->getBaseUrl()->getPath();
			
			return Model::create()->setData($result);
		}
	}
?>