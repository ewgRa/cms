<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DefaultCacheWorker extends \ewgraFramework\Singleton 
		implements CacheWorkerInterface
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
		public function createTicket(CacheableRequesterInterface $requester)
		{
			\ewgraFramework\Assert::isTrue(
				\ewgraFramework\Cache::me()->hasPool($requester->getPoolAlias()),
				'define pool for '.$requester->getPoolAlias()
			);
			
			$pool = \ewgraFramework\Cache::me()->getPool($requester->getPoolAlias());
			
			return $pool->createTicket()->setPrefix(get_class($requester));
		}

		public function getCustomCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		)
		{
			$cacheTicket = $this->createTicket($requester);
			
			$result =
				$cacheTicket->
				setKey(__FUNCTION__, $dbQuery)->
				restoreData();
				
			if ($cacheTicket->isExpired()) {
				$result = $requester->getCustomByQuery($dbQuery);

				$cacheTicket->storeData($result);
				$this->addTicketToTag($cacheTicket, $requester);
			}
			
			return $result;
		}
		
		public function getCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		)
		{
			$cacheTicket = $this->createTicket($requester);
			
			$result =
				$cacheTicket->
				setKey(__FUNCTION__, $dbQuery)->
				restoreData();
				
			if ($cacheTicket->isExpired()) {
				$result = $requester->getByQuery($dbQuery);

				$cacheTicket->storeData($result);
				$this->addTicketToTag($cacheTicket, $requester);
			}
			
			return $result;
		}
		
		public function getCustomListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		)
		{
			$cacheTicket = $this->createTicket($requester);
			
			$result =
				$cacheTicket->
					setKey(__FUNCTION__, get_class($requester), $dbQuery)->
					restoreData();
				
			if ($cacheTicket->isExpired()) {
				$result = $requester->getCustomListByQuery($dbQuery);

				$cacheTicket->storeData($result);
				$this->addTicketToTag($cacheTicket, $requester);
			}
			
			return $result;
		}
		
		public function getListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		)
		{
			$cacheTicket = $this->createTicket($requester);
			
			$result =
				$cacheTicket->
					setKey(__FUNCTION__, get_class($requester), $dbQuery)->
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
			\ewgraFramework\CacheTicket $cacheTicket,
			CacheableRequesterInterface $requester
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
		public function dropCache(CacheableRequesterInterface $requester)
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