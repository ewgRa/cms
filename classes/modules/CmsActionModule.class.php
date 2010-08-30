<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class CmsActionModule extends CmsModule
	{
		private $requestAction = null;
		private $defaultAction = null;
		private $actionList = array();
		
		public function __construct()
		{
			parent::__construct();	
		}
		
		/**
		 * @return CmsActionModule
		 */
		public function setRequestAction($requestAction)
		{
			$this->requestAction = $requestAction;
			return $this;
		}
		
		public function getRequestAction()
		{
			return $this->requestAction;
		}
				
		/**
		 * @return CmsActionModule
		 */
		public function setDefaultAction($defaultAction)
		{
			$this->defaultAction = $defaultAction;
			return $this;
		}
		
		public function getDefaultAction()
		{
			return $this->defaultAction;
		}
		
		/**
		 * @return CmsActionModule
		 */
		public function addAction($action, $function)
		{
			$this->actionList[$action] = $function;
			return $this;
		}
		
		/**
		 * @return CmsActionModule
		 */
		public function importSettings(array $settings = null)
		{
			$this->setRequestAction(
				isset($settings['action'])
					? $settings['action']
					: null
			);
			
			$form = 
				Form::create()->
				addPrimitive(PrmitiveString::create('action'));
			
			$form->import(
				$this->getRequest()->getPost()
				+ $this->getRequest()->getGet()
			);
			
			if ($action = $form->getValue('action'))
				$this->setRequestAction($action);
			
			return parent::importSettings($settings);
		}

		/**
		 * @return Model
		 */
		public function getModel()
		{
			$action = $this->getRequestAction();
			
			if (!$action)
				$action = $this->getDefaultAction();
			
			Assert::isNotNull($action, 'action is not defined');
			
			if(!isset($this->actionList[$action]))
				throw BadRequestException::create();
			
			return $this->{$this->actionList[$action]}();
		}

		/**
		 * @return CmsModule
		 */
		protected function setCacheTicket(CacheTicket $cacheTicket)
		{
			$cacheTicket->addKey($this->getAction());
			return parent::setCacheTicket($cacheTicket);
		}
	}
?>