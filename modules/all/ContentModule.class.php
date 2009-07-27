<?php
	/* $Id: ContentController.class.php 56 2008-08-17 17:31:53Z ewgraf $ */

	class ContentModule extends Module
	{
		private $units = null;
		
		/**
		 * @var ContentDA
		 */
		private $da	= null;

		/**
		 * @var ContentCacheWorker
		 */
		private $cacheWorker = null;
		
		/**
		 * @return ContentDA
		 */
		public function da()
		{
			if(!$this->da)
				$this->da = ContentDA::create();

			return $this->da;
		}
		
		/**
		 * @return ContentCacheWorker
		 */
		public function cacheWorker()
		{
			if(!$this->cacheWorker)
				$this->cacheWorker = ContentCacheWorker::create()->
					setModule($this);

			return $this->cacheWorker;
		}
		
		public function importSettings($settings)
		{
			Assert::isArray($settings['units']);
			$this->setUnits($settings['units']);

			if($cacheTicket = $this->cacheWorker()->createTicket())
				$this->setCacheTicket($cacheTicket);
			
			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$localizer = $this->getRequest()->getAttachedVar(AttachedAliases::LOCALIZER);
			
			$result = $this->da()->getUnitsContent(
				$this->getUnits(),
				$localizer->getRequestLanguage()->getId()
			);

			$page = $this->getRequest()->getAttachedVar(AttachedAliases::PAGE);
			
			$replace = array(
				'pattern' => array('%baseUrl%'),
				'replace' => array($page->getBaseUrl()->getPath())
			);

			if(defined('MEDIA_HOST'))
			{
				$replace['pattern'][] = '%MEDIA_HOST%';
				$replace['replace'][] = MEDIA_HOST;
			}

			foreach($result as &$contentRow)
				$contentRow['text'] = str_replace(
					$replace['pattern'],
					$replace['replace'],
					$contentRow['text']
				);
		
			return Model::create()->setData($result);
		}

		public function getUnits()
		{
			return $this->units;
		}
		
		private function setUnits($units)
		{
			$this->units = $units;
			return $this;
		}
	}
?>