<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * //FIXME: tested?
	*/
	class Auth401Controller extends ChainController
	{
		private $requiredRights = array('root');
		
		/**
		 * @return ModelAndView
		 */
		public function handleRequest()
		{
			$result = parent::handleRequest();

			if(!User::me()->getId() && isset($_SERVER['PHP_AUTH_USER']))
				User::me()->login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
			
			if(
				User::me()->getId()
				&& 	array_intersect(
					array_values(User::me()->getRights()),
					$this->requiredRights
				) == $this->requiredRights
			)
				return $result;
			
			return $this->unAuthorized('Enter you auth data', 'Need auth');
		}
		
		private function unAuthorized($realm, $cancelMessage)
		{
			header('WWW-Authenticate: Basic realm="' . $realm . '"');
			header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');
			echo $cancelMessage;
			die();
		}
	}
?>