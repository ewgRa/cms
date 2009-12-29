<?php
	/* $Id$ */

	class PageHeadModule extends CmsModule
	{
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
				$pageData =
					PageData::da()->get(
						$this->getPage(),
						$this->getRequestLanguage()
					);
			} catch(NotFoundException $e) {
				$pageData = PageData::create();
			}
				
			return Model::create()->setData(
				array('pageData' => $pageData)
			);
		}
	}
?>