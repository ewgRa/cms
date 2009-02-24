<?php
	/* $Id$ */

	class PageHeadController extends Controller
	{
		/**
		 * @var PageHeadDA
		 */
		private $da = null;
		
		protected function da()
		{
			if(!$this->da)
				$this->da = PageHeadDA::create();
			
			return $this->da;
		}
		
		public function importSettings(HttpRequest $request, $settings)
		{
			if(Cache::me()->getPool()->hasTicketParams('page'))
			{
				$localizer = $request->getAttached(AttachedAliases::LOCALIZER);
				
				$this->setCacheTicket(
					Cache::me()->getPool()->createTicket('page')->
						setKey(
							$request->getAttached(AttachedAliases::PAGE)->getId(),
							$localizer->getRequestLanguage(),
							__CLASS__, __FUNCTION__
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
			
			try {
				$head =
					$this->da()->getPageHead(
						$request->getAttached(AttachedAliases::PAGE)->getId(),
						$localizer->getRequestLanguage()->getId()
					);
			} catch(NotFoundException $e) {
				$head = array();
			}
				
			return Model::create()->setData($head);
		}
	}
?>