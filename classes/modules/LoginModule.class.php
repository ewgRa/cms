<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class LoginModule extends CmsModule
	{
		const SUCCESS_LOGIN		= 1;
		const WRONG_LOGIN		= 2;
		const WRONG_PASSWORD	= 3;
		
		private $source = null;
		
		/**
		 * @return LoginModule
		 */
		public function setSource($source)
		{
			$this->source = $source;
			return $this;
		}
		
		public function getSource()
		{
			return $this->source;
		}
				
		/**
		 * @return LoginModule
		 */
		public function importSettings(array $settings = null)
		{
			$this->setSource($settings['source']);

			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$user = null;
			$loginResult = null;

			try {
				$requestModel = $this->getRequestModel();
			} catch (BadRequestException $e) {
				$requestModel = Model::create();
			}
			
			if ($requestModel->has('login')) {
				Session::me()->start();
				Session::me()->drop('userId');
				Session::me()->save();

				$loginResult = self::SUCCESS_LOGIN;
				
				try {
					$user = User::da()->getByLogin($requestModel->get('login'));
				} catch(NotFoundException $exception) {
					$loginResult = self::WRONG_LOGIN;
				}
				
				if ($user && $user->getPassword() != md5($requestModel->get('password')))
					$loginResult = self::WRONG_PASSWORD;
				
				if ($loginResult == self::SUCCESS_LOGIN) {
					$this->getRequest()->setAttachedVar(AttachedAliases::USER, $user);
	
					Session::me()->set('userId', $user->getId());
					Session::me()->save();
					
					if ($requestModel->has('backurl')) {
						$this->getPageHeader()->addRedirect(
							HttpUrl::createFromString(
								base64_decode($requestModel->get('backurl'))
							)
						);
					}
				}
			}

			$backurl =
				$requestModel->has('backurl')
					? $requestModel->get('backurl')
					: null;
					
			if (!$backurl) {
				$backurl =
					$this->getRequest()->hasGetVar('backurl')
						? $this->getRequest()->getGetVar('backurl')
						: null;
			}
			
			return
				Model::create()->
					set('source', $this->getSource())->
					set('backurl', $backurl)->
					set('user', $user)->
					set('loginResult', $loginResult);
		}

		/**
		 * @return Model
		 * TODO: Form and primitives
		 */
		private function getRequestModel()
		{
			$result = Model::create();
			
			$keys = array(
				'login' => 'login',
				'password' => 'password',
				'backurl' => 'backurl'
			);
			
			$function = null;
			
			switch ($this->getSource()) {
				case 'get':
					$function = 'getGetVar';
					break;
				case 'server':
					$function = 'getServerVar';
					
					$keys = array(
						'login' => 'PHP_AUTH_USER',
						'password' => 'PHP_AUTH_PW'
					);
					
					break;
				case 'post':
					$function = 'getPostVar';
					break;
				default:
					Assert::isUnreachable();
					break;
			}
			
			foreach ($keys as $key => $varKey) {
				try {
					$result->set($key, $this->getRequest()->{$function}($varKey));
				} catch (MissingArgumentException $e) {
					if ($key != 'backurl')
						throw BadRequestException::create();
				}
			}
			
			return $result;
		}
	}
?>