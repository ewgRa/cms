<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
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
			
			$request->setAttachedVar(AttachedAliases::USER, $user);
			
			if(Session::me()->isStarted())
				$user->onSessionStarted();
			
			return parent::handleRequest($request, $mav);
		}
	}
?>