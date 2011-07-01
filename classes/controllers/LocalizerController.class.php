<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class LocalizerController extends \ewgraFramework\ChainController
	{
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$localizer = $request->getAttachedVar(AttachedAliases::LOCALIZER);

			$localizer->setLanguages(Language::da()->getList());

			if ($defaultLanguage = Config::me()->getOption('defaultLanguage'))
				$localizer->selectDefaultLanguage($defaultLanguage);
			else
				throw \ewgraFramework\DefaultException::create('no default language');

			if ($request->hasCookieVar('languageId')) {
				$localizer->setCookieLanguage(
					Language::da()->getById($request->getCookieVar('languageId'))
				);
			}

			$localizer->defineLanguage($request->getUrl());

			\ewgraFramework\CookieManager::me()->
				set(
					'languageId',
					$localizer->getRequestLanguage()->getId()
				);

			return parent::handleRequest($request, $mav);
		}
	}
?>