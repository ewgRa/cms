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
		public function createTicket(DatabaseRequester $requester);

		public function getCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			DatabaseRequester $requester
		);
		
		public function getListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			DatabaseRequester $requester
		);
		
		/**
		 * @return DatabaseRequester
		 */
		public function addTicketToTag(
			\ewgraFramework\CacheTicket $cacheTicket,
			DatabaseRequester $requester
		);
		
		/**
		 * @return DatabaseRequester
		 */
		public function dropCache(DatabaseRequester $requester);
	}	
?>