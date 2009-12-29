<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class LocalizerController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$localizer = $request->getAttachedVar(AttachedAliases::LOCALIZER);
			
			if ($cookieLanguageId = $request->getCookieVar('languageId')) {
				$localizer->setCookieLanguage(
					Language::da()->getById($cookieLanguageId)
				);
			}
			
			$localizer->defineLanguage($request->getUrl());
			
			CookieManager::me()->
				setCookie(
					'languageId', $localizer->getRequestLanguage()->getId()
				);
			
			return parent::handleRequest($request, $mav);
		}
	}
?>