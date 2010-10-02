<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DefaultCacheWorker extends Singleton implements CacheWorkerInterface
	{
		/**
		 * @return DefaultCacheWorker
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return CacheTicket
		 */
		public function createTicket(DatabaseRequester $requester)
		{
			Assert::isTrue(
				Cache::me()->hasPool($requester->getPoolAlias()),
				'define pool for '.$requester->getPoolAlias()
			);
			
			$pool = Cache::me()->getPool($requester->getPoolAlias());
			
			return $pool->createTicket()->setPrefix(get_class($requester));
		}

		public function getCachedByQuery(
			DatabaseQueryInterface $dbQuery,
			DatabaseRequester $requester
		)
		{
			$cacheTicket = $this->createTicket($requester);
			
			$result =
				$cacheTicket->
				setKey($dbQuery)->
				restoreData();
				
			if ($cacheTicket->isExpired()) {
				$result = $requester->getByQuery($dbQuery);

				$cacheTicket->storeData($result);
				$this->addTicketToTag($cacheTicket, $requester);
			}
			
			return $result;
		}
		
		public function getListCachedByQuery(
			DatabaseQueryInterface $dbQuery,
			DatabaseRequester $requester
		)
		{
			$cacheTicket = $this->createTicket($requester);
			
			$result =
				$cacheTicket->
					setKey(get_class($requester), $dbQuery)->
					restoreData();
				
			if ($cacheTicket->isExpired()) {
				$result = $requester->getListByQuery($dbQuery);

				$cacheTicket->storeData($result);
				$this->addTicketToTag($cacheTicket, $requester);
			}
			
			return $result;
		}
		
		/**
		 * @return DefaultCacheWorker
		 */
		public function addTicketToTag(
			CacheTicket $cacheTicket,
			DatabaseRequester $requester
		)
		{
			$tagTicket = $this->createTicket($requester);
			$tagTicket->setKey('tag');
				
			$data = $tagTicket->restoreData();

			if ($tagTicket->isExpired())
				$data = array();
				
			$data[] = $cacheTicket->getCacheInstance()->compileKey($cacheTicket);
			
			$tagTicket->storeData($data);
			
			return $this;
		}
		
		/**
		 * @return DefaultCacheWorker
		 */
		public function dropCache(DatabaseRequester $requester)
		{
			$tagTicket = $this->createTicket($requester);

			$data = $tagTicket->setKey('tag')->restoreData();
	
			if ($tagTicket->isExpired())
				$data = array();

			foreach ($data as $cacheKey)
				$tagTicket->getCacheInstance()->dropByKey($cacheKey);
				
			$tagTicket->drop();

			return $this;
		}
	}	
?>