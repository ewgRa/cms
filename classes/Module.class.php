<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class Module
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
		 * @return Module
		 */
		protected function setCacheTicket(CacheTicket $cacheTicket)
		{
			$this->cacheTicket = $cacheTicket;
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
		 * @return Module
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
		 * @return Module
		 */
		public function setRequest(HttpRequest $request)
		{
			$this->request = $request;
			return $this;
		}
		
		/**
		 * @return Module
		 */
		public function setView($view)
		{
			Assert::isImplement($view, 'ViewInterface');
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
		 * @return Module
		 */
		public function importSettings($settings)
		{
			return $this;
		}
		
		public function getRenderedModel()
		{
			$renderedModel = null;
			
			if($this->hasCacheTicket())
			{
				$this->getCacheTicket()->restoreData();
				
				if($this->getCacheTicket()->isExpired())
				{
					$renderedModel = $this->renderModel();
					
					$this->getCacheTicket()->
						setData($renderedModel)->
						storeData();
				}
				else
					$renderedModel = $this->getCacheTicket()->getData();
			}

			if(is_null($renderedModel))
				$renderedModel = $this->renderModel();
			
			return $renderedModel;
		}
		
		private function renderModel()
		{
			$view	= $this->getView();
			$model	= $this->getModel();
			
			return $view
				? ModelAndView::create()->
					setModel($model)->
					setView($view)->
					render()
				: null;
		}
	}
?>