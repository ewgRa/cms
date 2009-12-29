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
				
				$user = User::da()->getById($userId);
			}
			
			$request->setAttachedVar(AttachedAliases::USER, $user);
			
			$userRights = UserRight::da()->getByUser($user);
			
			$rights = array();
			
			foreach ($userRights as $userRight)
				$rights[$userRight->getRightId()] = $userRight->getRight();
			
			$request->setAttachedVar(AttachedAliases::USER_RIGHTS, $rights);
			
			return parent::handleRequest($request, $mav);
		}
	}
?>