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
			
			try
			{
				$cacheTicket = Cache::me()->createTicket('controllerDispatcher')->
					setKey(Page::me()->getId())->
					restoreData();
			}
			catch(MissingArgumentException $e)
			{
				$cacheTicket = null;
			}
			
			if(!$cacheTicket || $cacheTicket->isExpired())
			{
				ControllerDispatcher::me()->loadControllers(Page::me()->getId());
				
				if($cacheTicket)
					$cacheTicket->setData(ControllerDispatcher::me())->storeData();
			}
			else
				Singleton::setInstance(
					'ControllerDispatcher',
					$cacheTicket->getData()
				);
			
			$result->setModel(
				ControllerDispatcher::me()->getModel()
			);
			
			return $result;
		}
	}
?>