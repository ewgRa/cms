<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NullCacheWorker extends \ewgraFramework\Singleton implements CacheWorkerInterface
	{
		private $cacheInstance = null;

		/**
		 * @return NullCacheWorker
		 */
		protected function __construct()
		{
			parent::__construct();
			$this->cacheInstance = \ewgraFramework\NullCache::create();
		}

		/**
		 * @return NullCacheWorker
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		public function getCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		)
		{
			return $requester->getByQuery($dbQuery);
		}

		public function getListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		)
		{
			return $requester->getListByQuery($dbQuery);
		}

		public function getCustomCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		)
		{
			return $requester->getCustomByQuery($dbQuery);
		}

		public function getCustomListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		)
		{
			return $requester->getCustomListByQuery($dbQuery);
		}

		/**
		 * @return NullCacheWorker
		 */
		public function dropCache(CacheableRequesterInterface $requester)
		{
			return $this;
		}
	}
?>