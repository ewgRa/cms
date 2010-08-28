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
		public function createTicket(CmsDatabaseRequester $requester)
		{
			return 
				CacheTicket::create()->
				setCacheInstance($this->cacheInstance);
		}

		public function getCachedByQuery(
			DatabaseQueryInterface $dbQuery,
			CmsDatabaseRequester $requester
		)
		{
			return $requester->getByQuery($dbQuery);
		}
		
		public function getListCachedByQuery(
			DatabaseQueryInterface $dbQuery,
			CmsDatabaseRequester $requester
		)
		{
			return $requester->getListByQuery($dbQuery);
		}
		
		/**
		 * @return NullCacheWorker
		 */
		public function addTicketToTag(
			CacheTicket $cacheTicket,
			CmsDatabaseRequester $requester
		)
		{
			return $this;
		}
		
		/**
		 * @return NullCacheWorker
		 */
		public function dropCache(CmsDatabaseRequester $requester)
		{
			return $this;
		}
	}	
?>