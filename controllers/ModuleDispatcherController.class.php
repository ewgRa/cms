<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * //FIXME: tested?
	*/
	class ModuleDispatcherController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$moduleDispatcher =
				ModuleDispatcher::create()->
					setRequest($request);
			
			$moduleDispatcher->loadModules($request);

			$mav->getModel()->mergeModel(
				$moduleDispatcher->getModel()
			);
			
			return parent::handleRequest($request, $mav);
		}
	}
?>