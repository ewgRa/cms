<?php
	/* $Id$ */

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
				
					
			$this->replaceData($pageData);
			
			return
				Model::create()->setData(
					array('pageData' => $pageData)
				);
		}
		
		private function replaceData(PageData $pageData)
		{
			foreach ($this->getDispatcher()->getModules() as $module) {
				if ($module instanceof PageHeadReplacer)
					$module->replacePageData($pageData);
			}
			
			return $this;
		}
	}
?>