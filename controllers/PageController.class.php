<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageController extends ChainController
	{
		/**
		 * @var HttpRequest
		 */
		private $request = null;
		
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$this->request = $request;
			
			$startTime = microtime(true);
			
			$localizer = $request->getAttachedVar(AttachedAliases::LOCALIZER);
			
			$clearPath =
				$localizer->
					removeLanguageFromUrl($request->getUrl())->
					getPath();

			$page = $this->getPagePathMapper()->getPageByPath($clearPath);

			if (!$page)
				throw PageException::pageNotFound()->setUrl($clearPath);

			$user = $request->getAttachedVar(AttachedAliases::USER);
			
			$this->checkAccessPage($page, $user);

			$mav->setView(
				ViewFactory::createByViewFile($page->getLayout()->getViewFile())
			);
			
			$request->setAttachedVar(AttachedAliases::PAGE, $page);
			
			$baseUrl = HttpUrl::create()->setPath('');
			
			if (
				$localizer->isLanguageInUrl()
				&& $localizer->getSource() != Localizer::SOURCE_LANGUAGE_URL_AND_COOKIE
			) {
				$baseUrl->setPath(
					'/' . $localizer->getRequestLanguage()->getAbbr()
					. $baseUrl->getPath()
				);
			}
			
			$request->setAttachedVar(AttachedAliases::BASE_URL, $baseUrl);
			
			$request->setAttachedVar(
				AttachedAliases::PAGE_HEADER,
				PageHeader::create()
			);
			
			if (Singleton::hasInstance('Debug') && Debug::me()->isEnabled())
				$this->addDebug($startTime, microtime(true), $page);
			
			return parent::handleRequest($request, $mav);
		}

		/**
		 * @return PagePathMapper
		 */
		private function getPagePathMapper()
		{
			$result = null;
			
			try {
				$cacheTicket = Cache::me()->getPool('cms')->
					createTicket('pagePathMapper');
				
				$cacheTicket->restoreData();
			} catch(MissingArgumentException $e) {
				$cacheTicket = null;
			}

			if (!$cacheTicket || $cacheTicket->isExpired()) {
				$result = PagePathMapper::create()->loadMap();

				if ($cacheTicket)
					$cacheTicket->setData($result)->storeData();
			} else
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
			$rights = PageRight::da()->getByPage($page);
			
			$rightIds = array();
			
			foreach ($rights as $right) {
				$rightIds[] = $right->getRightId();
			}
			
			$inheritanceRights = Right::da()->getByInheritanceIds($rightIds);
			
			$nextInheritanceRights = $inheritanceRights;
			
			while ($nextInheritanceRights) {
				$inheritanceIds = array();
				
				foreach ($inheritanceRights as $right) {
					if (!isset($this->inheritanceRights[$right->getId()])) {
						$inheritanceRights[$right->getId()] = $right;
						$inheritanceIds[] = $right->getId();
					}
				}

				$nextInheritanceRights = Right::da()->getByInheritanceIds($inheritanceIds);
			}
			
			if ($rights) {
				$userRights = $this->request->getAttachedVar(AttachedAliases::USER_RIGHTS);
			
				$intersectRights = array_intersect(
					array_merge($rightIds, array_keys($inheritanceRights)),
					array_keys($userRights->getList())
				);

				if (!count($intersectRights)) {
					$noRights = array_diff(
						array_keys($rights), $intersectRights
					);
					
					throw
						PageException::noRightsToAccess()->
							setNoRights($noRights)->
							setPageRights($rights);
				}
			}

			return $this;
		}
	}
?>