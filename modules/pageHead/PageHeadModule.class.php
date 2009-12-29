<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageHeadModule extends CmsModule
	{
		/**
		 * @var ContentCacheWorker
		 */
		private $cacheWorker = null;
		
		/**
		 * @return ContentCacheWorker
		 */
		public function cacheWorker()
		{
			if(!$this->cacheWorker)
				$this->cacheWorker = PageHeadCacheWorker::create()->
					setModule($this);

			return $this->cacheWorker;
		}
		
		public function importSettings(array $settings = null)
		{
			if($cacheTicket = $this->cacheWorker()->createTicket())
				$this->setCacheTicket($cacheTicket);
						
			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$pageData = null;
			
			try {
				$pageData =
					PageData::da()->get(
						$this->getPage(),
						$this->getRequestLanguage()
					);
			} catch(NotFoundException $e) {
				$pageData = PageData::create();
			}
				
			return
				Model::create()->setData(
					array('pageData' => $pageData)
				);
		}
	}
?>