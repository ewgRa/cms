<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class CmsPageController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$localizer = $request->getAttachedVar(AttachedAliases::LOCALIZER);
			
			$clearPath =
				$localizer->
					removeLanguageFromUrl($request->getUrl())->
					getPath();

			$page = PagePathMapper::create()->loadMap()->getPageByPath($clearPath);
			
			if (!$page)
				throw PageNotFoundException::create();

			$user =
				$request->hasAttachedVar(AttachedAliases::USER)
					? $request->getAttachedVar(AttachedAliases::USER)
					: null;
			
			$request->setAttachedVar(AttachedAliases::PAGE, $page);
			
			$mav->setView($page->getLayout()->getViewFile()->createView());
			
			$baseUrl = HttpUrl::create()->setPath('');
			
			if (
				$localizer->getSource()->isLanguageInUrl()
				&& $localizer->getSource()->getId()
					!= LocalizerLanguageSource::URL_AND_COOKIE
			) {
				$baseUrl->setPath(
					'/'.$localizer->getRequestLanguage()->getAbbr()
					.$baseUrl->getPath()
				);
			}
			
			$request->setAttachedVar(AttachedAliases::BASE_URL, $baseUrl);
			
			$request->setAttachedVar(
				AttachedAliases::PAGE_HEADER,
				PageHeader::create()
			);
			
			return parent::handleRequest($request, $mav);
		}
	}
?>