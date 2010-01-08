<?php
	/* $Id$ */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoUser
	{
		private $id = null;
		
		private $login = null;
		
		private $password = null;
		
		/**
		 * @return UserDA
		 */
		public static function da()
		{
			return UserDA::me();
		}
		
		/**
		 * @return AutoUser
		 */
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}
		
		public function getId()
		{
			Assert::isNotNull($this->id);
			return $this->id;
		}
		
		/**
		 * @return AutoUser
		 */
		public function setLogin($login)
		{
			$this->login = $login;
			return $this;
		}
		
		public function getLogin()
		{
			Assert::isNotNull($this->login);
			return $this->login;
		}
		
		/**
		 * @return AutoUser
		 */
		public function setPassword($password)
		{
			$this->password = $password;
			return $this;
		}
		
		public function getPassword()
		{
			Assert::isNotNull($this->password);
			return $this->password;
		}
	}
?>