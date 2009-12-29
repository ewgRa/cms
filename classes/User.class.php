<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class User
	{
		const WRONG_PASSWORD	= 1;
		const WRONG_LOGIN		= 2;
		const SUCCESS_LOGIN		= 3;
		
		private $id		= null;
		private $login	= null;
		private $rights	= array();
		private $session = null;

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
		public function da()
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
		protected function setId($id)
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
		private function setLogin($login)
		{
			$this->login = $login;
			return $this;
		}
		
		public function getSession()
		{
			return $this->session;
		}
		
		/**
		 * @return BaseSession
		 */
		private function setSession(BaseSession $session)
		{
			$this->session = $session;
			return $this;
		}
		
		public function hasRight(UserRight $userRight)
		{
			return isset($this->rights[$userRight->getId()]);
		}
		
		public function getRights()
		{
			return $this->rights;
		}
		
		/**
		 * @return User
		 */
		public function dropRights()
		{
			$this->rights = array();
			return $this;
		}
		
		/**
		 * @return User
		 */
		public function setRights(array $rights)
		{
			$this->rights = $rights;
			return $this;
		}
		
		/**
		 * @return User
		 */
		public function addRight(UserRight $userRight)
		{
			$this->rights[$userRight->getId()] = $userRight;
			return $this;
		}
		
		public function getRightIds()
		{
			$result = array();
			
			foreach ($this->getRights() as $userRight) {
				$result[] = $userRight->getRightId();
			}
			
			return $result;
		}
		
		public function login($login, $password)
		{
			Session::me()->start();
			Session::me()->drop('user');
			Session::me()->save();
			
			$checkPassword = $this->da()->checkLogin($login, $password);

			if($checkPassword)
			{
				if($checkPassword['verify_password'])
				{
					$this->setId($checkPassword['id']);
					$this->setLogin($checkPassword['login']);
					$this->loadRights();
					
					Session::me()->set(
						'user',
						array(
							'id' => $this->getId(),
							'login' => $this->getLogin(),
							'rights' => $this->getRights()
						)
					);
					
					Session::me()->save();
					return self::SUCCESS_LOGIN;
				}
				else return self::WRONG_PASSWORD;
			}
			else return self::WRONG_LOGIN;
		}

		
		// FIXME: move this method to anywhere :)
		public function onSessionStarted()
		{
			if(Session::me()->has('user'))
			{
				$user = Session::me()->get('user');

				$this->
					setId($user['id'])->
					setLogin($user['login'])->
					setRights($user['rights']);
			}
			
			return $this;
		}

		/**
		 * @return User
		 */
		protected function loadRights()
		{
			$this->dropRights();
			
			if($this->getId())
			{
				foreach(UserRight::da()->getByUser($this) as $right)
				{
					if($this->hasRight($right))
						continue;

					$this->addRight($right);
				}
			}
			
			return $this;
		}
	}
?>