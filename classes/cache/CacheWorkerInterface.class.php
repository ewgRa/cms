<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface CacheWorkerInterface
	{
		/**
		 * @return CacheTicket
		 */
		public function createTicket(CacheableRequesterInterface $requester);

		public function getCustomCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		);
		
		public function getCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		);
		
		public function getListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		);
		
		/**
		 * @return CacheWorkerInterface
		 */
		public function addTicketToTag(
			\ewgraFramework\CacheTicket $cacheTicket,
			CacheableRequesterInterface $requester
		);
		
		/**
		 * @return CacheWorkerInterface
		 */
		public function dropCache(CacheableRequesterInterface $requester);
	}	
?>