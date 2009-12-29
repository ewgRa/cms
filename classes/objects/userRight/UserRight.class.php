<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class UserRight
	{
		private $userId = null;
		
		private $rightId = null;
		
		/**
		 * @return UserRight
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return UserRightDA
		 */
		public static function da()
		{
			return UserRightDA::me();
		}

		public function getId()
		{
			return $this->getUserId().'_'.$this->getRightId();
		}
		
		/**
		 * @return UserRight
		 */
		public function setUserId($userId)
		{
			$this->userId = $userId;
			return $this;
		}
		
		public function getUserId()
		{
			return $this->userId;
		}

		/**
		 * @return UserRight
		 */
		public function setRightId($rightId)
		{
			$this->rightId 	= $rightId;
			return $this;
		}
		
		public function getRightId()
		{
			return $this->rightId;
		}

		/**
		 * @return Right
		 */
		public function getRight()
		{
			return Right::da()->getById($this->getRightId());
		}
	}
?>