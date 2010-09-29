<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class CmsDebugController extends ChainController
	{
		private $alreadySubscribed = false;
		
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			if ($this->alreadySubscribed)
				return parent::handleRequest($request, $mav);
			
			$this->alreadySubscribed = true;
			
			$innerController = $this;
			
			while ($innerController = $innerController->getInner()) {
				if ($innerController instanceof CmsPageController) {
					$innerController->
						getObserverManager()->
						addObserver(
							CmsPageControllerObserverManager::PAGE_DEFINED_EVENT,
							array($this, 'pageDefined') 
						);
				}
			}

			$hashes = array();
			
			foreach (Database::me()->getPools() as $pool) {
				foreach ($hashes as $hash) {
					if ($pool->hasObserver($hash))
						continue 2;	
				}
				
				$hashes[] = 
					$pool->addObserver(
						BaseDatabase::QUERY_EVENT, 
						array($this, 'databaseQuery')
					);
			}
			
			$hashes = array(); 
			
			foreach (Cache::me()->getPools() as $pool) {
				foreach ($hashes as $hash) {
					if ($pool->hasObserver($hash))
						continue 2;	
				}
				
				$hashes[] = 
					$pool->addObserver(
						BaseCache::GET_TICKET_EVENT, 
						array($this, 'cacheTicketGot')
					);
			}
			
			return parent::handleRequest($request, $mav);
		}
		
		public function pageDefined(Model $model)
		{
			$page = $model->get('page');
			
			$debugItem =
				DebugItem::create()->
				setTrace(Debug::traceToDisplay(debug_backtrace()))->
				setData(
					array(
						'page' => array(
							'path' => $page->getPath(),
							'id' => $page->getId(),
							'layoutId' => $page->getLayout()->getId()
						)
					)
				);
			
			Debug::me()->addItem($debugItem);

			return $this;
		}

		public function databaseQuery(Model $model)
		{
			$debugItem =
				DebugItem::create()->
				setTrace(Debug::traceToDisplay(debug_backtrace()))->
				setData(
					array('query' => $model->get('query'))
				)->
				setStartTime($model->get('startTime'))->
				setEndTime($model->get('endTime'));
				
			Debug::me()->addItem($debugItem);

			return $this;
		}

		public function cacheTicketGot(Model $model)
		{
			$ticket = $model->get('ticket');
			
			$debugItem =
				DebugItem::create()->
				setTrace(Debug::traceToDisplay(debug_backtrace()))->
				setData(
					array(
						'cacheTicket' => array(
							'prefix' => $ticket->getPrefix(),
							'key' => 
								$ticket->getCacheInstance()->compileKey($ticket),
							'cacheInstance' => get_class($ticket->getCacheInstance()),
							'expiredTime' =>
								$ticket->getExpiredTime()
									? date('Y-m-d h:i:s', $ticket->getExpiredTime())
									: null,
							'lifeTime' =>
								$ticket->getLifeTime()
									? date('Y-m-d h:i:s', $ticket->getLifeTime())
									: null,
							'status' => $ticket->isExpired() ? 'expired' : 'actual'
						)
					)
				);
				
			Debug::me()->addItem($debugItem);

			return $this;
		}
	}
?>