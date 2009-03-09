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
		
		public function importSettings($settings)
		{
			if(Cache::me()->hasTicketParams('page'))
			{
				$localizer = $this->getRequest()->getAttached(AttachedAliases::LOCALIZER);
				
				$this->setCacheTicket(
					Cache::me()->createTicket('page')->
						setKey(
							$this->getRequest()->getAttached(AttachedAliases::PAGE)->getId(),
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
		public function getModel()
		{
			$localizer = $this->getRequest()->getAttached(AttachedAliases::LOCALIZER);
			
			try {
				$head =
					$this->da()->getPageHead(
						$this->getRequest()->getAttached(AttachedAliases::PAGE)->getId(),
						$localizer->getRequestLanguage()->getId()
					);
			} catch(NotFoundException $e) {
				$head = array();
			}
				
			return Model::create()->setData($head);
		}
	}
?>