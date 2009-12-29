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
			
			if (
				Session::me()->isStarted()
				&& $userId = Session::me()->has('userId')) {
				
				$user = User::da()->getById($userId)->loadRights();
			}
			
			$request->setAttachedVar(AttachedAliases::USER, $user);
			
			return parent::handleRequest($request, $mav);
		}
	}
?>