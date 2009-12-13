<?php
	/* $Id$ */

	class NavigationModule extends Module
	{
		private $category = null;
		
		/**
		 * @var NavigationDA
		 */
		private $da = null;
		
		public function importSettings($settings)
		{
			$this->setCategory($settings['category']);

			if(Cache::me()->getPool('cms')->hasTicketParams('navigation'))
			{
				$this->setCacheTicket(
					Cache::me()->getPool('cms')->createTicket('navigation')->
						setKey(
							$this->getCategory(),
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
			$result = $this->da()->getByCategory(
				$this->getCategory(),
				$this->getLocalizer()->getRequestLanguage()
			);

			$result['baseUrl'] = $this->getPage()->getBaseUrl()->getPath();
			
			return Model::create()->setData($result);
		}

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