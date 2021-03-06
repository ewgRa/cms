<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DebugController extends \ewgraFramework\ChainController
	{
		private $alreadySubscribed = false;

		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			if ($this->alreadySubscribed)
				return parent::handleRequest($request, $mav);

			$this->alreadySubscribed = true;

			$innerController = $this;

			while ($innerController = $innerController->getInner()) {
				if ($innerController instanceof DefinePageController) {
					$innerController->
						getObserverManager()->
						addObserver(
							DefinePageControllerObserverManager::PAGE_DEFINED_EVENT,
							array($this, 'pageDefined')
						);
				}
			}

			$hashes = array();

			foreach (\ewgraFramework\Database::me()->getPools() as $pool) {
				foreach ($hashes as $hash) {
					if ($pool->hasObserver($hash))
						continue 2;
				}

				$hashes[] =
					$pool->addObserver(
						\ewgraFramework\BaseDatabase::QUERY_EVENT,
						array($this, 'databaseQuery')
					);
			}

			$hashes = array();

			foreach (\ewgraFramework\Cache::me()->getPools() as $pool) {
				foreach ($hashes as $hash) {
					if ($pool->hasObserver($hash))
						continue 2;
				}

				$hashes[] =
					$pool->addObserver(
						\ewgraFramework\BaseCache::GET_TICKET_EVENT,
						array($this, 'cacheTicketGot')
					);
			}

			return parent::handleRequest($request, $mav);
		}

		public function pageDefined(
			\ewgraFramework\Model $model,
			\ewgraFramework\ObservableInterface $observable,
			$observerHash
		) {
			$page = $model->get('page');

			$debugItem =
				DebugItem::create()->
				setAlias('page')->
				setTrace(Debug::traceToDisplay(debug_backtrace()))->
				setData(
					array(
						'path' => $page->getPath(),
						'id' => $page->getId(),
						'layoutId' => $page->getLayout()->getId()
					)
				);

			Debug::me()->addItem($debugItem);

			return $this;
		}

		public function databaseQuery(
			\ewgraFramework\Model $model,
			\ewgraFramework\ObservableInterface $observable,
			$observerHash
		) {
			$debugItem =
				DebugItem::create()->
				setAlias('databaseQuery')->
				setTrace(Debug::traceToDisplay(debug_backtrace()))->
				setData($model->get('query'))->
				setStartTime($model->get('startTime'))->
				setEndTime($model->get('endTime'));

			Debug::me()->addItem($debugItem);

			return $this;
		}

		public function cacheTicketGot(
			\ewgraFramework\Model $model,
			\ewgraFramework\ObservableInterface $observable,
			$observerHash
		) {
			$ticket = $model->get('ticket');

			$debugItem =
				DebugItem::create()->
				setAlias('cacheTicket')->
				setTrace(Debug::traceToDisplay(debug_backtrace()))->
				setData(
					array(
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
				);

			Debug::me()->addItem($debugItem);

			return $this;
		}
	}
?>