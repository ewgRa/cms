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
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
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
					setKey($_SERVER['HTTP_HOST'])->
					restoreData();
			} catch(MissingArgumentException $e) {
				$cacheTicket = null;
			}

			if(!$cacheTicket || $cacheTicket->isExpired())
			{
				$site = Site::create();
				$site->setHost($_SERVER['HTTP_HOST'])->define();

				if($cacheTicket)
					$cacheTicket->setData($site)->storeData();
			}
			else
				$site = $cacheTicket->getData();
				
			return $site;
		}
	}
?>