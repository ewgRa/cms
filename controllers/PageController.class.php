<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * //FIXME: tested?
	*/
	class PageController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$startTime = microtime(true);
			
			$localizer = $request->getAttachedVar(AttachedAliases::LOCALIZER);
			
			$clearPath =
				$localizer->
					removeLanguageFromUrl($request->getUrl())->
					getPath();

			$pageId = $this->getPagePathMapper()->getPageId($clearPath);

			if(!$pageId)
				throw PageException::pageNotFound()->setUrl($clearPath);
			
			try
			{
				$cacheTicket = Cache::me()->getPool('cms')->
					createTicket('page');
				
				$cacheTicket->
					setKey($pageId)->
					restoreData();
			}
			catch(MissingArgumentException $e)
			{
				$cacheTicket = null;
			}
			
			$page = null;
			
			if(!$cacheTicket || $cacheTicket->isExpired())
			{
				$page = Page::create();
				$page->load($pageId);
				
				if($cacheTicket)
					$cacheTicket->setData($page)->storeData();
			}
			else
				$page = $cacheTicket->getData();

			$user = $request->getAttachedVar(AttachedAliases::USER);
			
			if(!$user)
				$user = User::create();
			
			$this->checkAccessPage($page, $user);

			$mav->setView(
				ViewFactory::createByFileId($page->getLayoutFileId())
			);
			
			$baseUrl = HttpUrl::create()->setPath('');
			
			if(
				$localizer->isLanguageInUrl()
				&& $localizer->getSource() != Localizer::SOURCE_LANGUAGE_URL_AND_COOKIE
			) {
				$baseUrl->setPath(
					'/' . $localizer->getRequestLanguage()->getAbbr()
					. $baseUrl->getPath()
				);
			}
			
			$page->setBaseUrl($baseUrl);
			
			$request->setAttachedVar(AttachedAliases::PAGE, $page);
			
			if(Singleton::hasInstance('Debug') && Debug::me()->isEnabled())
				$this->addDebug($startTime, microtime(true), $page);
			
			return parent::handleRequest($request, $mav);
		}

		/**
		 * @return PagePathMapper
		 */
		private function getPagePathMapper()
		{
			$result = null;
			
			try
			{
				$cacheTicket = Cache::me()->getPool('cms')->
					createTicket('pagePathMapper');
				
				$cacheTicket->restoreData();
			}
			catch(MissingArgumentException $e)
			{
				$cacheTicket = null;
			}

			if(!$cacheTicket || $cacheTicket->isExpired())
			{
				$result = PagePathMapper::create()->loadMap();

				if($cacheTicket)
					$cacheTicket->setData($result)->storeData();
			}
			else
				$result = $cacheTicket->getData();
				
			return $result;
		}
		
		/**
		 * @return PageController
		 */
		private function addDebug($startTime, $endTime, Page $page)
		{
			$debugItem = CmsDebugItem::create()->
				setData($page)->
				setType(CmsDebugItem::PAGE)->
				setStartTime($startTime)->
				setEndTime($endTime);
			
			Debug::me()->addItem($debugItem);
			
			return $this;
		}

		private function checkAccessPage(Page $page, User $user)
		{
			if($page->getRights())
			{
				$intersectRights = array_intersect(
					array_merge($page->getRightIds(), $page->getInheritanceRightIds()),
					$user->getRightIds()
				);

				if(!count($intersectRights))
				{
					$noRights = array_diff(
						array_keys($page->getRights()), $intersectRights
					);
					
					throw
						PageException::noRightsToAccess()->
							setNoRights($noRights)->
							setPageRights($page->getRights());
					}
			}

			return $this;
		}
	}
?>