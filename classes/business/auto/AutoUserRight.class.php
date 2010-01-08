<?php
	/* $Id$ */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoUserRight
	{
		private $userId = null;
		
		private $rightId = null;
		
		/**
		 * @return UserRightDA
		 */
		public static function da()
		{
			return UserRightDA::me();
		}
		
		public function getId()
		{
			return $this->getUserId().'_'.$this->getUserId().'_'.$this->getRightId().'_'.$this->getRightId();
		}
		
		/**
		 * @return AutoUserRight
		 */
		public function setUserId($userId)
		{
			$this->userId = $userId;
			return $this;
		}
		
		public function getUserId()
		{
			Assert::isNotNull($this->userId);
			return $this->userId;
		}
		
		/**
		 * @return AutoUserRight
		 */
		public function setUser(User $user)
		{
			$this->userId = $user->getId();
			return $this;
		}
		
		/**
		 * @return User
		 */
		public function getUser()
		{
			return User::da()->getById($this->getUserId());
		}
		
		/**
		 * @return AutoUserRight
		 */
		public function setRightId($rightId)
		{
			$this->rightId = $rightId;
			return $this;
		}
		
		public function getRightId()
		{
			Assert::isNotNull($this->rightId);
			return $this->rightId;
		}
		
		/**
		 * @return AutoUserRight
		 */
		public function setRight(Right $right)
		{
			$this->rightId = $right->getId();
			return $this;
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