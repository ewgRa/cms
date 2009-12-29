<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class User
	{
		private $id			= null;
		private $login		= null;
		private $password	= null;
		
		/**
		 * @return User
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return UserDA
		 */
		public static function da()
		{
			return UserDA::me();
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		/**
		 * @return User
		 */
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}
		
		public function getLogin()
		{
			return $this->login;
		}
		
		/**
		 * @return User
		 */
		public function setLogin($login)
		{
			$this->login = $login;
			return $this;
		}
		
		public function getPassword()
		{
			return $this->password;
		}
		
		/**
		 * @return User
		 */
		public function setPassword($password)
		{
			$this->password = $password;
			return $this;
		}
	}
?>