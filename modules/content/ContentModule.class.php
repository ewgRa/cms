<?php
	/* $Id: ContentController.class.php 56 2008-08-17 17:31:53Z ewgraf $ */

	class ContentModule extends CmsModule
	{
		private $units = null;
		
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
				$this->cacheWorker = ContentCacheWorker::create()->
					setModule($this);

			return $this->cacheWorker;
		}
		
		public function importSettings(array $settings = null)
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
			
			$result['contentList'] = Content::da()->getByIds($this->getUnits());
			
			$result['contentDataList'] = array();
			
			$contentDataList =
				ContentData::da()->getList(
					$result['contentList'],
					$this->getLocalizer()->getRequestLanguage()
				);
			
			foreach ($contentDataList as $contentData) {
				$result['contentDataList'][$contentData->getContentId()] =
					$contentData;
			}
			
			$result['replaceFilter'] = $this->getReplaceFilter();
		
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
		
		/**
		 * @return StringReplaceImportFilter
		 */
		private function getReplaceFilter()
		{
			$result = StringReplaceFilter::create();
			
			$params = array(
				'search' => array('%baseUrl%'),
				'replace' => array($this->getPage()->getBaseUrl()->getPath())
			);

			if(defined('MEDIA_HOST'))
			{
				$params['search'][] = '%MEDIA_HOST%';
				$params['replace'][] = MEDIA_HOST;
			}
			
			$result->
				setSearch($params['search'])->
				setReplace($params['replace']);
			
			return $result;
		}
	}
?>