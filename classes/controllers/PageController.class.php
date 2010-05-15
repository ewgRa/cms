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

			$page = PagePathMapper::create()->loadMap()->getPageByPath($clearPath);

			if (!$page)
				throw PageNotFoundException::create()->setUrl($clearPath);

			$user =
				$request->hasAttachedVar(AttachedAliases::USER)
					? $request->getAttachedVar(AttachedAliases::USER)
					: null;
			
			$request->setAttachedVar(AttachedAliases::PAGE, $page);
			
			$this->checkAccessPage($page, $user);

			$mav->setView(
				$page->getLayout()->getViewFile()->createView()
			);
			
			$baseUrl = HttpUrl::create()->setPath('');
			
			if (
				$localizer->getSource()->isLanguageInUrl()
				&& $localizer->getSource()->getId()
					!= LocalizerLanguageSource::URL_AND_COOKIE
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

		private function checkAccessPage(Page $page, User $user = null)
		{
			$result = true;
			
			$pageRights = PageRight::da()->getByPage($page);
			$rightIds = array();
			
			foreach ($pageRights as $pageRight)
				$rightIds[] = $pageRight->getRightId();
			
			$rights = Right::da()->getByIds($rightIds);

			if ($rights && !$user)
				$result = false;
				
			if ($result && $rights && $user)
				$result = $user->checkAccess($rights);
			
			if (!$result)
				throw PageAccessDeniedException::create();

			return $this;
		}
	}
?>