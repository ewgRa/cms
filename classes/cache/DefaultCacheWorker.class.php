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
		 * @var \ewgraFramework\Cache
		 */
		private $cache = null;

		/**
		 * @var \ewgraFramework\Database
		 */
		private $database = null;

		/**
		 * @return DefaultCacheWorker
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		/**
		 * @return DefaultCacheWorker
		 */
		protected function __construct()
		{
			$this->cache = \ewgraFramework\Cache::me();
			$this->database = \ewgraFramework\Database::me();
			parent::__construct();
		}

		public function restoreTicketData(\ewgraFramework\CacheTicket $cacheTicket)
		{
			$cacheWorker =
				\ewgraFramework\DefaultCacheWorker::createFromTicket($cacheTicket);

			return $cacheWorker->restoreTicketData($cacheTicket);
		}

		/**
		 * @return DefaultCacheWorker
		 */
		public function storeTicketData(
			\ewgraFramework\CacheTicket $cacheTicket,
			$data,
			array $tags
		) {
			$cacheWorker =
				\ewgraFramework\DefaultCacheWorker::createFromTicket($cacheTicket);

			$cacheWorker->storeTicketData($cacheTicket, $data, $tags);

			return $this;
		}

		public function getCustomCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester,
			array $tags
		)
		{
			$worker = $this->createDatabaseCacheWorker($requester);

			return $worker->getCached($dbQuery, $tags);
		}

		public function getCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester,
			array $tags
		) {
			$worker = $this->createDatabaseCacheWorker($requester);

			return $worker->getCached(
				$dbQuery,
				$tags,
				function() use ($dbQuery, $requester) {
					return $requester->getByQuery($dbQuery);
				}
			);
		}

		public function getCustomListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester,
			array $tags
		) {
			$worker = $this->createDatabaseCacheWorker($requester);

			return $worker->getCachedList(
				$dbQuery,
				$tags
			);
		}

		public function getListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester,
			array $tags
		) {
			$worker = $this->createDatabaseCacheWorker($requester);

			return $worker->getCachedList(
				$dbQuery,
				$tags,
				function() use ($dbQuery, $requester) {
					return $requester->getListByQuery($dbQuery);
				}
			);
		}

		/**
		 * @return DefaultCacheWorker
		 */
		public function dropCache(
			CacheableRequesterInterface $requester,
			array $tags
		) {
			$worker = $this->createDatabaseCacheWorker($requester);

			$worker->dropCache($tags);

			return $this;
		}

		/**
		 * @return CacheTicket
		 */
		private function createDatabaseCacheWorker(
			CacheableRequesterInterface $requester
		) {
			$poolAlias = $requester->getPoolAlias();

			\ewgraFramework\Assert::isTrue(
				$this->cache->hasPool($poolAlias),
				'define pool for '.$poolAlias
			);

			\ewgraFramework\Assert::isTrue(
				$this->database->hasPool($poolAlias),
				'define pool for '.$poolAlias
			);

			return \ewgraFramework\DefaultDatabaseCacheWorker::create(
				$this->cache->getPool($poolAlias),
				$this->database->getPool($poolAlias)
			);
		}
	}
?>