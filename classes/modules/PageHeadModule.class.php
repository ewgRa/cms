<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageHeadModule extends CmsModule
	{
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$pageData =
				PageData::da()->get(
					$this->getPage(),
					$this->getRequestLanguage()
				);
			
			if (!$pageData)
				$pageData = PageData::create();
					
			$this->replaceData($pageData);
			
			return
				Model::create()->setData(
					array('pageData' => $pageData)
				);
		}
		
		private function replaceData(PageData $pageData)
		{
			foreach ($this->getDispatcher()->getModules() as $module) {
				if ($module instanceof PageHeadReplacerInterface)
					$module->replacePageData($pageData);
			}
			
			return $this;
		}
	}
?>