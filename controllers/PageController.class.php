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
			
			$localizer = $request->getAttached(AttachedAliases::LOCALIZER);
			
			$clearPath = $localizer->getClearRequestPath();
			
			$pageId = $this->getPagePathMapper()->getPageId($clearPath);

			if(!$pageId)
			{
				throw
					ExceptionsMapper::me()->createException('Page')->
						setCode(PageException::PAGE_NOT_FOUND)->
						setUrl($clearPath);
			}
			
			try
			{
				$cacheTicket = Cache::me()->createTicket('page')->
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

			// FIXME: operation with user
			$page->checkAccessPage(User::me()->getRights());

			$mav->setView(
				ViewFactory::createByFileId($page->getLayoutFileId())
			);
			
			$request->setAttached(AttachedAliases::PAGE, $page);
			
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
				$cacheTicket = Cache::me()->createTicket('pagePathMapper')->
					restoreData();
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
			$debugItem = DebugItem::create()->
				setData($page)->
				setType(DebugItem::PAGE)->
				setStartTime($startTime)->
				setEndTime($endTime);
			
			Debug::me()->addItem($debugItem);
			
			return $this;
		}
	}
?>