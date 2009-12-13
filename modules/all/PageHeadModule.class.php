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
			return PageHeadDA::me();
		}
		
		public function importSettings($settings)
		{
			if(Cache::me()->getPool('cms')->hasTicketParams('page'))
			{
				$this->setCacheTicket(
					Cache::me()->getPool('cms')->createTicket('page')->
						setKey(
							$this->getPage()->getId(),
							$this->getRequestLanguage(),
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
			try {
				$head =
					$this->da()->getPageHead(
						$this->getPage(),
						$this->getRequestLanguage()
					);
			} catch(NotFoundException $e) {
				$head = array();
			}
				
			return Model::create()->setData($head);
		}
	}
?>