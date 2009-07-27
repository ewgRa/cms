<?php
	/* $Id$ */

	class PageHeadModule extends Module
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
			if(Cache::me()->getPool('cms')->hasTicketParams('page'))
			{
				$localizer = $this->getRequest()->getAttachedVar(AttachedAliases::LOCALIZER);
				
				$this->setCacheTicket(
					Cache::me()->getPool('cms')->createTicket('page')->
						setKey(
							$this->getRequest()->getAttachedVar(AttachedAliases::PAGE)->getId(),
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
			$localizer = $this->getRequest()->getAttachedVar(AttachedAliases::LOCALIZER);
			
			try {
				$head =
					$this->da()->getPageHead(
						$this->getRequest()->getAttachedVar(AttachedAliases::PAGE)->getId(),
						$localizer->getRequestLanguage()->getId()
					);
			} catch(NotFoundException $e) {
				$head = array();
			}
				
			return Model::create()->setData($head);
		}
	}
?>