<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * //FIXME: tested?
	*/
	class UserController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$user = User::create();
			
			$request->setAttached(AttachedAliases::USER, $user);
			
			if(Session::me()->isStarted())
				$user->onSessionStarted();
			
			return parent::handleRequest($request, $mav);
		}
	}
?>