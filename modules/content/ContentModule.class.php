<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ContentModule extends CmsModule
	{
		private $units = null;
		
		public function importSettings(array $settings = null)
		{
			Assert::isArray($settings['units']);
			$this->setUnits($settings['units']);

			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
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
		
		/**
		 * @return ContentModule
		 */
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
				'replace' => array($this->getBaseUrl()->getPath())
			);

			if (defined('MEDIA_HOST')) {
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