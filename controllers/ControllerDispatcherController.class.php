<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * //FIXME: tested?
	*/
	class ControllerDispatcherController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$contrllerDispatcher = ControllerDispatcher::create();
			$contrllerDispatcher->loadControllers($request);

			$mav->getModel()->mergeModel(
				$contrllerDispatcher->getModel($request)
			);
			
			return parent::handleRequest($request, $mav);
		}
	}
?>