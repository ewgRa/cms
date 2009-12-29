<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Auth401Controller extends ChainController
	{
		// FIXME: remove direct id
		private $requiredRights = array(1);
		
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$user = $request->getAttachedVar(AttachedAliases::USER);
			
			if (
				$user && !$user->getId()
				&& $request->hasServerVar('PHP_AUTH_USER')
			) {
				$user->login(
					$request->getServerVar('PHP_AUTH_USER'),
					$request->getServerVar('PHP_AUTH_PW')
				);
			}
			
			if (
				$user && $user->getId()
				&& array_intersect(
					$user->getRightIds(),
					$this->requiredRights
				) == $this->requiredRights
			)
				return parent::handleRequest($request, $mav);
			
			return $this->unAuthorized('Enter you auth data', 'Need auth', $request);
		}
		
		private function unAuthorized($realm, $cancelMessage, HttpRequest $request)
		{
			header('WWW-Authenticate: Basic realm="' . $realm . '"');
			header($request->getServerVar('SERVER_PROTOCOL').' 401 Unauthorized');
			echo $cancelMessage;
			die();
		}
	}
?>