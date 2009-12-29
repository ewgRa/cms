<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class PageHeadModule extends CmsModule
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
				
			return
				Model::create()->setData(
					array('pageData' => $pageData)
				);
		}
	}
?>