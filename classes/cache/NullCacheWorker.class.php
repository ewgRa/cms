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
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
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

		/**
		 * @return NullCacheWorker
		 */
		public function dropCache(CacheableRequesterInterface $requester)
		{
			return $this;
		}
	}
?>