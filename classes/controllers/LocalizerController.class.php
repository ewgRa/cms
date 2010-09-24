<?php
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
			
			$localizer->setLanguages(Language::da()->getList());
			
			$projectOptions = Config::me()->getOption('project');

			if (isset($projectOptions['defaultLanguage'])) {
				$localizer->selectDefaultLanguage(
					$projectOptions['defaultLanguage']
				);
			} else
				throw DefaultException::create('no default language');
			
			if ($request->hasCookieVar('languageId')) {
				$localizer->setCookieLanguage(
					Language::da()->getById($request->getCookieVar('languageId'))
				);
			}
			
			$localizer->defineLanguage($request->getUrl());
			
			CookieManager::me()->
				setCookie(
					'languageId', 
					$localizer->getRequestLanguage()->getId()
				);
			
			return parent::handleRequest($request, $mav);
		}
	}
?>