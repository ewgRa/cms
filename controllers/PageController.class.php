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
		public function handleRequest()
		{
			$result = parent::handleRequest();
			
			$startTime = microtime(true);
			
			$pageId = $this->getPagePathMapper()->getPageId(
				UrlHelper::me()->getEnginePagePath()
			);
			
			if(!$pageId)
			{
				throw
					ExceptionsMapper::me()->createException('Page')->
						setCode(PageException::PAGE_NOT_FOUND)->
						setUrl(UrlHelper::me()->getEnginePagePath());
			}
			
			$cacheTicket = Cache::me()->createTicket('page')->
				setKey($pageId)->
				restoreData();
			
			if($cacheTicket->isExpired())
			{
				Page::me()->load($pageId);
				$cacheTicket->setData(Page::me())->storeData();
			}
			else
				Singleton::setInstance('Page', $cacheTicket->getData());

			// FIXME: operation with user
			Page::me()->checkAccessPage(User::me()->getRights());

			
			$result->setView(
				ViewFactory::createByFileId(Page::me()->getLayoutFileId())
			);
			
			if(Singleton::hasInstance('Debug') && Debug::me()->isEnabled())
				$this->addDebug($startTime, microtime(true));
			
			return $result;
		}

		/**
		 * @return PagePathMapper
		 */
		private function getPagePathMapper()
		{
			$result = null;
			
			$cacheTicket = Cache::me()->createTicket('pagePathMapper')->
				restoreData();

			if($cacheTicket->isExpired())
			{
				$result = PagePathMapper::create()->loadMap();

				$cacheTicket->setData($result)->storeData();
			}
			else
				$result = $cacheTicket->getData();
				
			return $result;
		}
		
		/**
		 * @return PageController
		 */
		private function addDebug($startTime, $endTime)
		{
			$debugItem = DebugItem::create()->
				setData(Page::me())->
				setType(DebugItem::PAGE)->
				setStartTime($startTime)->
				setEndTime($endTime);
			
			Debug::me()->addItem($debugItem);
			
			return $this;
		}
	}
?>