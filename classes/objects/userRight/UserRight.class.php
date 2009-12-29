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
		 * @var Right
		 */
		private $right = null;

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
			$this->right 	= null;
			return $this;
		}
		
		public function getRightId()
		{
			return $this->rightId;
		}

		/**
		 * @return UserRight
		 */
		public function setRight(Right $right)
		{
			$this->right 	= $right;
			$this->rightId 	= $right->getId();
			return $this;
		}
		
		/**
		 * @return Right
		 */
		public function getRight()
		{
			if (!$this->right && $this->getRightId()) {
				$this->setRight(
					Right::da()->getById($this->getRightId())
				);
			}
			
			return $this->right;
		}
	}
?>