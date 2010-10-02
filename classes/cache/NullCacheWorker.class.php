<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NullCacheWorker extends Singleton implements CacheWorkerInterface
	{
		private $cacheInstance = null;
		
		/**
		 * @return NullCacheWorker
		 */
		protected function __construct()
		{
			parent::__construct();
			$this->cacheInstance = NullCache::create();
		}
		
		/**
		 * @return NullCacheWorker
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
			return 
				CacheTicket::create()->
				setCacheInstance($this->cacheInstance);
		}

		public function getCachedByQuery(
			DatabaseQueryInterface $dbQuery,
			DatabaseRequester $requester
		)
		{
			return $requester->getByQuery($dbQuery);
		}
		
		public function getListCachedByQuery(
			DatabaseQueryInterface $dbQuery,
			DatabaseRequester $requester
		)
		{
			return $requester->getListByQuery($dbQuery);
		}
		
		/**
		 * @return NullCacheWorker
		 */
		public function addTicketToTag(
			CacheTicket $cacheTicket,
			DatabaseRequester $requester
		)
		{
			return $this;
		}
		
		/**
		 * @return NullCacheWorker
		 */
		public function dropCache(DatabaseRequester $requester)
		{
			return $this;
		}
	}	
?>