<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class UserRightDA extends AutoUserRightDA
	{
		/**
		 * @return UserRightDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getIdsByUser(User $user)
		{
			$userRights = $this->getByUser($user);
			
			$rightIds = array();
			
			foreach ($userRights as $userRight)
				$rightIds[] = $userRight->getRightId();
			
			return $rightIds;
		}
		
		public function getByUser(User $user)
		{
			$dbQuery = "SELECT * FROM ".$this->getTable()." WHERE user_id = ?";
			
			return $this->getListCachedByQuery($dbQuery, array($user->getId()));
		}
	}
?>