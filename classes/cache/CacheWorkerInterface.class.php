<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface CacheWorkerInterface
	{
		public function getCustomCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester,
			array $tags
		);

		public function getCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester,
			array $tags
		);

		public function getCustomListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester,
			array $tags
		);

		public function getListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester,
			array $tags
		);

		/**
		 * @return CacheWorkerInterface
		 */
		public function dropCache(
			CacheableRequesterInterface $requester,
			array $tags
		);
	}
?>