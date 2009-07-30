<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * //FIXME: tested?
	*/
	class LocalizerController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$localizer = $request->getAttachedVar(AttachedAliases::LOCALIZER);
			
			if($request->getCookieVar('languageId'))
			{
				$localizer->setCookieLanguage(
					Language::create()->
						setId($request->getCookieVar('languageId'))->
						setAbbr($request->getCookieVar('languageAbbr'))
				);
			}
			
			$localizer->defineLanguage($request->getUrl());
			
			//try set cookie
			Session::me()->
				setCookie(
					'languageId', $localizer->getRequestLanguage()->getId()
				)->
				setCookie(
					'languageAbbr', $localizer->getRequestLanguage()->getAbbr()
				);
			
			return parent::handleRequest($request, $mav);
		}
	}
?>