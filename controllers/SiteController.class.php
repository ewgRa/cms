<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * //FIXME: tested?
	*/
	class SiteController extends ChainController
	{
		private $siteAlias = null;

		/**
		 * @return SiteController
		 */
		public function setSiteAlias($alias)
		{
			$this->siteAlias = $alias;
			return $this;
		}

		public function getSiteAlias()
		{
			return $this->siteAlias;
		}
		
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			Assert::notNull($this->getSiteAlias());
			
			$request->setAttached(
				AttachedAliases::SITE,
				$this->getSite()
			);
			
			return parent::handleRequest($request, $mav);
		}
		
		private function getSite()
		{
			try {
				$cacheTicket = Cache::me()->createTicket('site')->
					setKey($this->getSiteAlias())->
					restoreData();
			} catch(MissingArgumentException $e) {
				$cacheTicket = null;
			}

			if(!$cacheTicket || $cacheTicket->isExpired())
			{
				$site = Site::da()->getSiteByAlias($this->getSiteAlias());

				if($cacheTicket)
					$cacheTicket->setData($site)->storeData();
			}
			else
				$site = $cacheTicket->getData();
				
			return $site;
		}
	}
?>