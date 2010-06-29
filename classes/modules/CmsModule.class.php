<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class CmsModule
	{
		/**
		 * @var CacheTicket
		 */
		private $cacheTicket = null;
		
		/**
		 * @var ViewInterface
		 */
		private $view		 = null;
		
		/**
		 * @var HttpRequest
		 */
		private $request	 = null;
		
		/**
		 * @var ModuleDispatcher
		 */
		private $dispatcher  = null;
		
		/**
		 * @return Model
		 */
		abstract public function getModel();

		public function hasCacheTicket()
		{
			return !is_null($this->cacheTicket);
		}
		
		/**
		 * @return CacheTicket
		 */
		public function getCacheTicket()
		{
			return $this->cacheTicket;
		}
		
		/**
		 * @return CmsModule
		 */
		protected function setCacheTicket(CacheTicket $cacheTicket)
		{
			$this->cacheTicket = $cacheTicket;
			return $this;
		}
		
		/**
		 * @return CmsModule
		 */
		protected function storeCacheTicketData($data)
		{
			$this->getCacheTicket()->setData($data)->storeData();
			return $this;
		}
		
		/**
		 * @return ModuleDispatcher
		 */
		public function getDispatcher()
		{
			return $this->dispatcher;
		}
		
		/**
		 * @return CmsModule
		 */
		public function setDispatcher(ModuleDispatcher $dispatcher)
		{
			$this->dispatcher = $dispatcher;
			return $this;
		}
		
		/**
		 * @return HttpRequest
		 */
		public function getRequest()
		{
			return $this->request;
		}
		
		/**
		 * @return CmsModule
		 */
		public function setRequest(HttpRequest $request)
		{
			$this->request = $request;
			return $this;
		}
		
		/**
		 * @return CmsModule
		 */
		public function setView(ViewInterface $view)
		{
			$this->view = $view;
			return $this;
		}
		
		/**
		 * @return ViewInterface
		 */
		public function getView()
		{
			return $this->view;
		}
		
		/**
		 * @return CmsModule
		 */
		public function importSettings(array $settings = null)
		{
			return $this;
		}
		
		public function getRenderedModel()
		{
			$renderedModel = null;
			
			if ($this->hasCacheTicket()) {
				$renderedModel = $this->getCacheTicket()->restoreData();
				
				if ($this->getCacheTicket()->isExpired()) {
					$renderedModel = $this->renderModel();
					
					$this->getCacheTicket()->storeData($renderedModel);
				}
			}

			if (is_null($renderedModel))
				$renderedModel = $this->renderModel();
			
			return $renderedModel;
		}
		
		/**
		 * @return Localizer
		 */
		public function getLocalizer()
		{
			return
				$this->getRequest()->getAttachedVar(AttachedAliases::LOCALIZER);
		}

		/**
		 * @return Language
		 */
		public function getRequestLanguage()
		{
			return $this->getLocalizer()->getRequestLanguage();
		}

		/**
		 * @return Page
		 */
		public function getPage()
		{
			return $this->getRequest()->getAttachedVar(AttachedAliases::PAGE);
		}
		
		public function hasUser()
		{
			return $this->getRequest()->hasAttachedVar(AttachedAliases::USER);
		}
		
		/**
		 * @return User
		 */
		public function getUser()
		{
			return $this->getRequest()->getAttachedVar(AttachedAliases::USER);
		}
		
		/**
		 * @return HttpUrl
		 */
		public function getBaseUrl()
		{
			return $this->getRequest()->getAttachedVar(AttachedAliases::BASE_URL);
		}
		
		/**
		 * @return PageHeader
		 */
		public function getPageHeader()
		{
			return $this->getRequest()->getAttachedVar(AttachedAliases::PAGE_HEADER);
		}
		
		private function renderModel()
		{
			return
				ModelAndView::create()->
					setModel($this->getModel())->
					setView($this->getView())->
					render();
		}
	}
?>