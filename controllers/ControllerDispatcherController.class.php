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
		public function handleRequest(ModelAndView $mav)
		{
			ControllerDispatcher::me()->loadControllers(Page::me()->getId());

			$mav->getModel()->mergeModel(
				ControllerDispatcher::me()->getModel()
			);
			
			return parent::handleRequest($mav);
		}
	}
?>