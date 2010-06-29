<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ModuleDispatcherController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$moduleDispatcher = ModuleDispatcher::create()->setRequest($request);
			
			$page = $request->getAttachedVar(AttachedAliases::PAGE);
					
			$moduleDispatcher->loadModules(PageModule::da()->getByPage($page));

			$mav->getModel()->mergeModel($moduleDispatcher->getModel());
			
			return parent::handleRequest($request, $mav);
		}
	}
?>