<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class UserRights
	{
		/**
		 * @var User
		 */
		private $user		= null;
		
		/**
		 * @return UserRights
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return UserRights
		 */
		public function setUser(User $user)
		{
			$this->user = $user;
			return $this;
		}

		public function getList()
		{
			$userRights = UserRight::da()->getByUser($this->user);
			
			$rights = array();
			
			foreach ($userRights as $userRight)
				$rights[$userRight->getRightId()] = $userRight->getRight();
			
			return $rights;
		}
	}
?>