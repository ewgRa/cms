<?php
	/* $Id$ */

	class PageHeadController extends Controller
	{
		/**
		 * @var PageHeadDA
		 */
		private $da = null;
		
		protected function beforeRenderModel()
		{
			$this->da = PageHeadDA::create();
			
			return parent::beforeRenderModel();
		}
		
		public function importSettings($settings)
		{
			if(Cache::me()->hasTicketParams('page'))
			{
				$this->setCacheTicket(
					Cache::me()->createTicket('page')->
						setKey(
							Page::me()->getId(),
							Localizer::me()->getRequestLanguage(),
							__CLASS__, __FUNCTION__
						)
				);
			}
			
			return $this;
		}
		
		public function getModel()
		{
			return $this->da->getPageHead(
				Page::me()->getId(),
				Localizer::me()->getRequestLanguage()->getId()
			);
		}
	}
?>