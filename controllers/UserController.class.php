<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class UserController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$user = null;
			
			if (
				Session::me()->isStarted()
				&& $userId = Session::me()->has('userId')) {
				
				$user = User::da()->getById($userId);
			}
			
			if ($user) {
				$request->setAttachedVar(AttachedAliases::USER, $user);
			
				$request->setAttachedVar(
					AttachedAliases::USER_RIGHTS,
					UserRights::create()->setUser($user)
				);
			}
			
			return parent::handleRequest($request, $mav);
		}
	}
?>