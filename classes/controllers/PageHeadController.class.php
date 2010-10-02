<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageHeadController extends \ewgraFramework\ChainController
	{
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$pageData =
				PageData::da()->get(
					$request->getAttachedVar(AttachedAliases::PAGE),
					$request->
						getAttachedVar(AttachedAliases::LOCALIZER)->
						getRequestLanguage()
				);
			
			if (!$pageData)
				$pageData = PageData::create();
					
			$this->replaceData($pageData);
			
			$mav->getModel()->set('pageData', $pageData);
			
			return parent::handleRequest($request, $mav);
		}
		
		private function replaceData(PageData $pageData)
		{
			$outerController = $this;

			while ($outerController = $outerController->getOuter()) {
				if ($outerController instanceof PageHeadReplacerInterface)
					$outerController->replacePageData($pageData);
			}
			
			return $this;
		}
	}
?>