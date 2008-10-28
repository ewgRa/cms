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
		public function handleRequest()
		{
			$result = parent::handleRequest();
			
			if(!$result->getModel())
				$result->setModel(array());
			
			ControllerDispatcher::me()->loadControllers(Page::me()->getId());

			$result->setModel(
				array_merge(
					$result->getModel(),
					ControllerDispatcher::me()->getModel()
				)
			);
			
			return $result;
		}
	}
?>