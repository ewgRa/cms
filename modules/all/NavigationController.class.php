<?php
	/* $Id$ */

	class NavigationController extends Controller
	{
		private $category = null;
		
		/**
		 * @var NavigationDA
		 */
		private $da = null;
		
		protected function beforeRenderModel()
		{
			$this->da = NavigationDA::create();
			
			return parent::beforeRenderModel();
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
				$this->setCacheTicket(
					Cache::me()->createTicket('navigation')->
						setKey(
							$this->getCategory(),
							Localizer::me()->getRequestLanguage(),
							Localizer::me()->getSource()
						)
				);
			}
			
			return $this;
		}
		
		public function getModel()
		{
			$result = $this->da->getByCategory(
				$this->getCategory(),
				Localizer::me()->getRequestLanguage()->getId()
			);

			$result['localizerPath'] = UrlHelper::me()->getLocalizerPath();
			
			return $result;
		}
	}
?>