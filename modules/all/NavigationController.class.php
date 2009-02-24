<?php
	/* $Id$ */

	class NavigationController extends Controller
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
		
		public function importSettings(HttpRequest $request, $settings)
		{
			$this->setCategory($settings['category']);

			if(Cache::me()->getPool()->hasTicketParams('navigation'))
			{
				$localizer = $request->getAttached(AttachedAliases::LOCALIZER);
				
				$page = $request->getAttached(AttachedAliases::PAGE);
			
				$this->setCacheTicket(
					Cache::me()->getPool()->createTicket('navigation')->
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
		public function getModel(HttpRequest $request)
		{
			$localizer = $request->getAttached(AttachedAliases::LOCALIZER);
			
			$result = $this->da()->getByCategory(
				$this->getCategory(),
				$localizer->getRequestLanguage()->getId()
			);

			$page = $request->getAttached(AttachedAliases::PAGE);
			
			$result['baseUrl'] = $page->getBaseUrl()->getPath();
			
			return Model::create()->setData($result);
		}
	}
?>