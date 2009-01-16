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
			if(Session::me()->getCookie('languageId'))
			{
				Localizer::me()->setCookieLanguage(
					Language::create()->
						setId(Session::me()->getCookie('languageId'))->
						setAbbr(Session::me()->getCookie('languageAbbr'))
				);
			}
			
			Localizer::me()->defineLanguage();
			
			//try set cookie
			Session::me()->
				setCookie(
					'languageId', Localizer::me()->getRequestLanguage()->getId()
				)->
				setCookie(
					'languageAbbr', Localizer::me()->getRequestLanguage()->getAbbr()
				);
			
			return parent::handleRequest($request, $mav);
		}
	}
?>